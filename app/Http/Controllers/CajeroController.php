<?php

namespace App\Http\Controllers;

use App\Models\{Orden, DetalleOrden, Producto, User, Pago, MetodoPago};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class CajeroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:cajero');
    }

    public function dashboard()
    {
        return view('cajero.dashboard');
    }

    public function sale(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'productos' => 'required|array|min:1',
                'cantidades' => 'required|array|min:1',
                'metodo_pago' => 'required|in:efectivo,nequi',
            ]);

            if (count($request->productos) !== count($request->cantidades)) {
                Log::error('Mismatch between productos and cantidades arrays', ['request' => $request->all()]);
                return back()->with('error', 'Error en los datos de la venta. Por favor, intenta de nuevo.');
            }

            $subtotal = 0;
            $items = [];
            foreach ($request->productos as $index => $producto_id) {
                $producto = Producto::where('id', $producto_id)->where('estado', 'activo')->first();
                if (!$producto) {
                    Log::error("Producto ID {$producto_id} not found or inactive");
                    return back()->with('error', 'Producto no encontrado o inactivo.');
                }
                $cantidad = (int) $request->cantidades[$index];
                if ($producto->stock < $cantidad) {
                    Log::error("Insufficient stock for producto ID {$producto_id}: {$producto->stock} < {$cantidad}");
                    return back()->with('error', "Stock insuficiente para {$producto->nombre}.");
                }
                $subtotal += $producto->precio * $cantidad;
                $items[] = ['producto' => $producto, 'cantidad' => $cantidad];
            }

            $iva = $subtotal * 0.19;
            $total = $subtotal + $iva;

            try {
                DB::beginTransaction();

                $metodoPago = MetodoPago::where('nombre', $request->metodo_pago)->first();
                if (!$metodoPago) {
                    throw new \Exception("Método de pago {$request->metodo_pago} no encontrado.");
                }

                $orden = Orden::create([
                    'user_id' => Auth::id(),
                    'total' => $total,
                    'iva' => $iva,
                    'estado' => $request->metodo_pago === 'efectivo' ? 'entregado' : 'procesando',
                    'metodo_pago' => $request->metodo_pago,
                ]);

                foreach ($items as $item) {
                    DetalleOrden::create([
                        'orden_id' => $orden->id,
                        'producto_id' => $item['producto']->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['producto']->precio,
                        'subtotal' => $item['producto']->precio * $item['cantidad'] * (1 + 0.19),
                    ]);
                    $item['producto']->decrement('stock', $item['cantidad']);
                }

                Pago::create([
                    'orden_id' => $orden->id,
                    'metodo_pago_id' => $metodoPago->id,
                    'monto' => $total,
                    'estado' => $request->metodo_pago === 'efectivo' ? 'completado' : 'pendiente',
                ]);

                DB::commit();

                Log::info("Sale created successfully for order ID {$orden->id}", ['total' => $total, 'metodo_pago' => $request->metodo_pago]);

                if ($request->metodo_pago === 'nequi') {
                    return redirect()->route('cajero.payment', $orden->id);
                }

                return redirect()->route('cajero.transactions')->with('success', 'Venta registrada correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error creating sale: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
                return back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
            }
        }

        $query = Producto::where('estado', 'activo');
        $search = $request->query('search');
        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }
        $productos = $query->paginate(12)->appends(['search' => $search]);

        Log::debug('Productos type: ' . get_class($productos), ['count' => $productos->count()]);

        return view('cajero.sale', compact('productos'));
    }

    public function payment($orden_id)
    {
        $orden = Orden::findOrFail($orden_id);
        if ($orden->metodo_pago !== 'nequi' || $orden->estado !== 'procesando') {
            Log::warning("Invalid access to payment view for order: {$orden_id}", [
                'metodo_pago' => $orden->metodo_pago,
                'estado' => $orden->estado
            ]);
            return redirect()->route('cajero.transactions')->with('error', 'Esta orden no requiere confirmación de pago Nequi.');
        }
        $nequiNumber = env('NEQUI_NUMBER', '3152971513');
        return view('cajero.payment', compact('orden', 'nequiNumber'));
    }

    public function confirmPayment(Request $request, $orden_id)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:255',
        ]);

        $orden = Orden::findOrFail($orden_id);
        if ($orden->metodo_pago !== 'nequi' || $orden->estado !== 'procesando') {
            Log::error("Invalid payment attempt for order: {$orden_id}", [
                'metodo_pago' => $orden->metodo_pago,
                'estado' => $orden->estado
            ]);
            return redirect()->route('cajero.transactions')->with('error', 'No se puede procesar el pago para esta orden.');
        }

        try {
            DB::beginTransaction();
            $pago = $orden->pagos()->where('estado', 'pendiente')->first();
            if (!$pago) {
                Log::error("No pending payment found for order: {$orden_id}");
                throw new \Exception('No se encontró un pago pendiente para esta orden.');
            }

            $pago->update([
                'estado' => 'completado',
                'transaction_id' => $request->transaction_id,
            ]);
            $orden->update(['estado' => 'entregado']);
            DB::commit();

            Log::info("Payment confirmed for order: {$orden_id}", ['transaction_id' => $request->transaction_id]);
            return redirect()->route('cajero.transactions')->with('success', 'Pago Nequi confirmado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error confirming payment for order: {$orden_id}: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cajero.transactions')->with('error', 'Error al confirmar el pago: ' . $e->getMessage());
        }
    }

    public function transactions(Request $request)
    {
        $query = Orden::with(['detalles.producto', 'user', 'pagos']);

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        if ($fecha_inicio = $request->query('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $fecha_inicio);
        }
        if ($fecha_fin = $request->query('fecha_fin')) {
            $query->whereDate('created_at', '<=', $fecha_fin);
        }

        $ordenes = $query->latest()->paginate(10)->appends($request->query());

        Log::info("Transactions fetched: " . $ordenes->total() . " orders");

        return view('cajero.transactions', compact('ordenes'));
    }

    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,procesando,entregado,cancelado',
        ]);

        try {
            $orden = Orden::findOrFail($id);
            $orden->update(['estado' => $request->estado]);
            Log::info("Order {$id} updated to estado: {$request->estado}");
            return redirect()->route('cajero.transactions')->with('success', 'Estado de la orden actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error updating order {$id}: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cajero.transactions')->with('error', 'Error al actualizar la orden: ' . $e->getMessage());
        }
    }

    public function deleteOrder($id)
    {
        try {
            DB::beginTransaction();
            $orden = Orden::findOrFail($id);

            foreach ($orden->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            $orden->pagos()->delete();
            $orden->detalles()->delete();
            $orden->delete();
            DB::commit();

            Log::info("Order {$id} deleted");
            return redirect()->route('cajero.transactions')->with('success', 'Orden eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting order {$id}: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cajero.transactions')->with('error', 'Error al eliminar la orden: ' . $e->getMessage());
        }
    }

    public function payOrder($id)
    {
        $orden = Orden::findOrFail($id);
        if ($orden->metodo_pago !== 'efectivo' || $orden->estado === 'entregado' || $orden->estado === 'cancelado') {
            Log::error("Invalid payment attempt for order: {$id}", [
                'metodo_pago' => $orden->metodo_pago,
                'estado' => $orden->estado
            ]);
            return redirect()->route('cajero.transactions')->with('error', 'No se puede procesar el pago para esta orden.');
        }

        try {
            DB::beginTransaction();
            $pago = $orden->pagos()->where('estado', 'pendiente')->first();
            if (!$pago) {
                $metodoPago = MetodoPago::where('nombre', 'efectivo')->first();
                if (!$metodoPago) {
                    throw new \Exception('Método de pago efectivo no encontrado.');
                }
                $pago = Pago::create([
                    'orden_id' => $orden->id,
                    'metodo_pago_id' => $metodoPago->id,
                    'monto' => $orden->total,
                    'estado' => 'pendiente',
                ]);
                Log::info("Created new pending payment for order: {$id}");
            } elseif ($pago->estado === 'completado') {
                throw new \Exception('El pago ya está completado.');
            }
            $pago->update(['estado' => 'completado']);
            $orden->update(['estado' => 'entregado']);
            DB::commit();

            Log::info("Payment completed for order: {$id}");
            return redirect()->route('cajero.transactions')->with('success', 'Pago en efectivo confirmado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error paying order {$id}: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cajero.transactions')->with('error', 'Error al confirmar el pago: ' . $e->getMessage());
        }
    }

    public function exportTransactions(Request $request)
    {
        $query = Orden::with(['detalles.producto', 'user', 'pagos']);

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        if ($fecha_inicio = $request->query('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $fecha_inicio);
        }
        if ($fecha_fin = $request->query('fecha_fin')) {
            $query->whereDate('created_at', '<=', $fecha_fin);
        }

        $ordenes = $query->latest()->get();

        $csv = Writer::createFromString('');
        $csv->insertOne(['ID', 'Cliente', 'Fecha', 'Total', 'Método de Pago', 'Estado', 'Detalles']);

        foreach ($ordenes as $orden) {
            $detalles = $orden->detalles->map(function ($detalle) {
                return $detalle->producto->nombre . " (x" . $detalle->cantidad . ")";
            })->implode('; ');
            $metodo_pago = isset($orden->metodo_pago) ? ucfirst($orden->metodo_pago) : 'N/A';
            $csv->insertOne([
                $orden->id,
                isset($orden->user->name) ? $orden->user->name : 'N/A',
                $orden->created_at->format('d/m/Y H:i'),
                number_format($orden->total, 2, ',', '.'),
                $metodo_pago,
                ucfirst($orden->estado),
                $detalles ? $detalles : 'N/A',
            ]);
        }

        return response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transacciones_' . now()->format('Ymd_His') . '.csv"',
        ]);
    }

    public function close(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $pagos = Pago::where('estado', 'completado')
                    ->whereDate('created_at', today())
                    ->with('orden.detalles.producto')
                    ->get();
                $totalVentas = $pagos->sum('monto');

                $admin = User::where('role', 'admin')->first();
                if (!$admin) {
                    Log::warning('No admin user found for cash register closure');
                    throw new \Exception('No se encontró un administrador para enviar el cierre de caja.');
                }

                $report = "Reporte de cierre de caja:\n" .
                          "Cajero: " . Auth::user()->name . "\n" .
                          "Total ventas del día: $" . number_format($totalVentas, 2) . "\n" .
                          "Número de transacciones: " . $pagos->count() . "\n" .
                          "Transacciones de cajero: " . $pagos->where('orden.user_id', Auth::id())->count() . "\n" .
                          "Transacciones de clientes: " . $pagos->where('orden.user_id', '!=', Auth::id())->count();

                Mail::raw($report, function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject('Cierre de Caja - ' . now()->toDateString());
                });

                Log::info("Cash register closed by user: " . Auth::id(), ['total_ventas' => $totalVentas]);

                return redirect()->route('cajero.close')->with('success', 'Cierre de caja enviado al administrador.');
            } catch (\Exception $e) {
                Log::error("Error closing cash register: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
                return redirect()->route('cajero.close')->with('error', 'Error al enviar el cierre de caja: ' . $e->getMessage());
            }
        }

        $pagos = Pago::where('estado', 'completado')
            ->whereDate('created_at', today())
            ->with('orden.detalles.producto')
            ->get();
        $ordenes = $pagos->pluck('orden')->unique('id');
        $totalVentas = $pagos->sum('monto');

        $metodosPago = $pagos->groupBy('metodo_pago_id')->map(function ($group) {
            return $group->count();
        })->mapWithKeys(function ($count, $metodo_pago_id) {
            $metodo = MetodoPago::find($metodo_pago_id);
            return [$metodo ? $metodo->nombre : 'Desconocido' => $count];
        })->toArray();

        $ventasPorHora = $pagos->groupBy(function ($pago) {
            return $pago->created_at->format('H');
        })->map(function ($group) {
            return [
                'hora' => $group->first()->created_at->format('H'),
                'total' => $group->sum('monto')
            ];
        })->values();

        $productosMasVendidos = DetalleOrden::whereIn('orden_id', $ordenes->pluck('id'))
            ->join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.subtotal) as total')
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->take(5)
            ->get();
        return view('cajero.close', compact('ordenes', 'totalVentas', 'metodosPago', 'ventasPorHora', 'productosMasVendidos'));
    }
}