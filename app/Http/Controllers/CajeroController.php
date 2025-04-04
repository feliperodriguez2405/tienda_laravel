<?php

namespace App\Http\Controllers;

use App\Models\{Orden, DetalleOrden, Producto};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
                'productos' => 'required|array',
                'cantidades' => 'required|array',
                'metodo_pago' => 'required|in:efectivo,nequi',
            ]);

            $total = 0;
            foreach ($request->productos as $index => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$index];
                if ($producto->stock < $cantidad) {
                    return back()->with('error', "Stock insuficiente para {$producto->nombre}");
                }
                $total += $producto->precio * $cantidad;
            }

            $orden = Orden::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'estado' => 'procesando',
            ]);

            foreach ($request->productos as $index => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$index];
                DetalleOrden::create([
                    'orden_id' => $orden->id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                ]);
                $producto->decrement('stock', $cantidad);
            }

            if ($request->metodo_pago === 'nequi') {
                return redirect()->route('cajero.payment', $orden->id);
            }

            return redirect()->route('cajero.transactions')->with('success', 'Venta registrada.');
        }

        $productos = $request->query('search')
            ? Producto::where('nombre', 'like', '%' . $request->query('search') . '%')->get()
            : Producto::all();

        return view('cajero.sale', compact('productos'));
    }

    public function payment($orden_id)
    {
        $orden = Orden::findOrFail($orden_id);
        $nequiNumber = '3152971513';
        return view('cajero.payment', compact('orden', 'nequiNumber'));
    }

    public function transactions()
    {
        $ordenes = Orden::where('user_id', Auth::id())->with('detalles.producto')->latest()->get();
        return view('cajero.transactions', compact('ordenes'));
    }

    public function close()
    {
        $ordenes = Orden::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->with('detalles.producto')
            ->get();
        $totalVentas = $ordenes->sum('total');

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Mail::raw("Reporte de cierre de caja del cajero " . Auth::user()->name . ":\n" .
                      "Total ventas del día: $" . number_format($totalVentas, 2) . "\n" .
                      "Número de transacciones: " . $ordenes->count(),
                function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject('Cierre de Caja - ' . now()->toDateString());
                });
        }

        return redirect()->route('cajero.dashboard')->with('success', 'Cierre de caja enviado al administrador.');
    }
}