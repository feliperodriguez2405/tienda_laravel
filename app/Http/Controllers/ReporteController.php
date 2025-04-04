<?php

namespace App\Http\Controllers;

use App\Models\{Orden, DetalleOrden, Producto};
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
            ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.cantidad * productos.precio) as total')
            ->groupBy('productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit(5)
            ->get();

        // Productos con bajo stock
        $bajoStock = Producto::where('stock', '<', 10)->get();

        // Valor total del inventario
        $valorInventario = Producto::sum(DB::raw('stock * COALESCE(precio, 0)'));

        // Calcular ganancia total
        $gananciaTotal = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->join('ordenes', 'detalle_ordenes.orden_id', '=', 'ordenes.id')
            ->where('ordenes.estado', 'entregado')
            ->selectRaw('SUM(detalle_ordenes.cantidad * (COALESCE(productos.precio, 0) - COALESCE(productos.precio_compra, 0))) as ganancia')
            ->value('ganancia') ?? 0;

        return view('admin.informes', compact(
            'ventas',
            'productosMasVendidos',
            'bajoStock',
            'valorInventario',
            'gananciaTotal'
        ));
    }
}