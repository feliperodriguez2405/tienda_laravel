<?php

namespace App\Http\Controllers;

use App\Models\{Orden, DetalleOrden, Producto, Pago, MetodoPago, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Ventas por día (últimos 7 días)
        $ventas = Orden::selectRaw('DATE(created_at) as fecha, SUM(total) as total_vendido')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Productos más vendidos (Top 5)
        $productosMasVendidos = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.subtotal) as total')
            ->groupBy('productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit(5)
            ->get();

        // Productos con bajo stock
        $bajoStock = Producto::where('stock', '<', 10)->get();

        // Valor total del inventario
        $valorInventario = Producto::sum(DB::raw('stock * COALESCE(precio, 0)'));

        // Ganancia total (precio de venta - precio de compra por cantidad para órdenes entregadas)
        $gananciaTotal = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->join('ordenes', 'detalle_ordenes.orden_id', '=', 'ordenes.id')
            ->where('ordenes.estado', 'entregado')
            ->selectRaw('SUM(detalle_ordenes.cantidad * (productos.precio - COALESCE(productos.precio_compra, 0))) as ganancia')
            ->value('ganancia') ?? 0;

        // Cierre de caja (hoy)
        $pagos = Pago::where('estado', 'completado')
            ->whereDate('created_at', today())
            ->with(['orden.detalles.producto', 'orden.user', 'metodoPago'])
            ->get();

        $cierreCaja = [
            'total_ventas' => $pagos->sum('monto'),
            'transacciones' => $pagos->count(),
            'metodos_pago' => $pagos->groupBy('metodo_pago_id')->map(function ($group) {
                return [
                    'nombre' => $group->first()->metodoPago ? $group->first()->metodoPago->nombre : 'Desconocido',
                    'count' => $group->count(),
                    'total' => $group->sum('monto'),
                ];
            })->values(),
            'por_cajero' => $pagos->groupBy('orden.user_id')->map(function ($group) {
                return [
                    'cajero' => $group->first()->orden->user ? $group->first()->orden->user->name : 'Desconocido',
                    'count' => $group->count(),
                ];
            })->values(),
            'productos_top' => DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
                ->join('ordenes', 'detalle_ordenes.orden_id', '=', 'ordenes.id')
                ->where('ordenes.created_at', '>=', today())
                ->where('ordenes.created_at', '<', today()->addDay())
                ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.subtotal) as total')
                ->groupBy('productos.nombre')
                ->orderByDesc('cantidad_vendida')
                ->limit(5)
                ->get(),
        ];

        return view('admin.informes', compact(
            'ventas',
            'productosMasVendidos',
            'bajoStock',
            'valorInventario',
            'gananciaTotal',
            'cierreCaja'
        ));
    }
}