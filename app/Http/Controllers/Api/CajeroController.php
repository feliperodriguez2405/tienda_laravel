<?php

   namespace App\Http\Controllers\Api;

   use App\Http\Controllers\Controller;
   use App\Models\Orden;
   use App\Models\DetalleOrden;
   use App\Models\Producto;
   use App\Models\Pago;
   use App\Models\MetodoPago;
   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;
   use Illuminate\Support\Facades\Mail;
   use Illuminate\Support\Facades\Log;

   class CajeroController extends Controller
   {
       public function sale(Request $request)
       {
           $request->validate([
               'productos' => 'required|array|min:1',
               'cantidades' => 'required|array|min:1',
               'metodo_pago' => 'required|in:efectivo,nequi',
           ]);

           if (count($request->productos) !== count($request->cantidades)) {
               return response()->json(['error' => 'Mismatch between productos and cantidades'], 400);
           }

           $subtotal = 0;
           $items = [];
           foreach ($request->productos as $index => $producto_id) {
               $producto = Producto::find($producto_id);
               if (!$producto) {
                   return response()->json(['error' => "Producto ID {$producto_id} not found"], 404);
               }
               $cantidad = (int) $request->cantidades[$index];
               if ($producto->stock < $cantidad) {
                   return response()->json(['error' => "Insufficient stock for {$producto->nombre}"], 400);
               }
               $subtotal += $producto->precio * $cantidad;
               $items[] = ['producto' => $producto, 'cantidad' => $cantidad];
           }

           $iva = $subtotal * 0.19;
           $total = $subtotal * 1.19;

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
                       'subtotal' => $item['producto']->precio * $item['cantidad'] * 1.19,
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

               return response()->json([
                   'message' => 'Sale registered successfully',
                   'orden_id' => $orden->id,
                   'requires_payment_confirmation' => $request->metodo_pago === 'nequi',
               ]);
           } catch (\Exception $e) {
               DB::rollBack();
               Log::error("Error creating sale: {$e->getMessage()}");
               return response()->json(['error' => 'Error registering sale: ' . $e->getMessage()], 500);
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

           $ordenes = $query->latest()->paginate(10);

           return response()->json($ordenes);
       }

       public function close(Request $request)
       {
           try {
               $pagos = Pago::where('estado', 'completado')
                   ->whereDate('created_at', today())
                   ->with('orden.detalles.producto')
                   ->get();
               $totalVentas = $pagos->sum('monto');

               $admin = User::where('role', 'admin')->first();
               if (!$admin) {
                   return response()->json(['error' => 'No admin user found'], 400);
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

               return response()->json(['message' => 'Cash register closure sent to admin']);
           } catch (\Exception $e) {
               Log::error("Error closing cash register: {$e->getMessage()}");
               return response()->json(['error' => 'Error closing cash register: ' . $e->getMessage()], 500);
           }
       }
   }