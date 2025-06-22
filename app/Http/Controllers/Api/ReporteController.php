<?php

   namespace App\Http\Controllers\Api;

   use App\Http\Controllers\Controller;
   use App\Models\Orden;
   use App\Models\DetalleOrden;
   use App\Models\Producto;
   use App\Models\Pago;
   use App\Models\MetodoPago;
   use Illuminate\Support\Facades\DB;

   class ReporteController extends Controller
   {
       public function index()
       {
           $ventas = Orden::selectRaw('DATE(created_at) as fecha, SUM(total) as total_vendido')
               ->where('created_at', '>=', now()->subDays(7))
               ->groupBy('fecha')
               ->orderBy('fecha')
               ->get();

           $productosMasVendidos = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
               ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.subtotal) as total')
               ->groupBy('productos.nombre')
               ->orderByDesc('cantidad_vendida')
               ->limit(5)
               ->get();

           $bajoStock = Producto::where('stock', '<', 10)->get();

           $valorInventario = Producto::sum(DB::raw('stock * COALESCE(precio, 0)'));

           $gananciaTotal = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
               ->join('ordenes', 'detalle_ordenes.orden_id', '=', 'ordenes.id')
               ->where('ordenes.estado', 'entregado')
               ->selectRaw('SUM(detalle_ordenes.subtotal) as ganancia')
               ->value('ganancia') ?? 0;

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

           return response()->json([
               'ventas' => $ventas,
               'productos_mas_vendidos' => $productosMasVendidos,
               'bajo_stock' => $bajoStock,
               'valor_inventario' => $valorInventario,
               'ganancia_total' => $gananciaTotal,
               'cierre_caja' => $cierreCaja,
           ]);
       }
   }