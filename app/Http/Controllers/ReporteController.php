<?php

namespace App\Http\Controllers;

use App\Models\{Orden, DetalleOrden, Producto};
use Illuminate\Http\Request;
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

        $productosMasVendidos = DetalleOrden::join('productos', 'detalle_orden.producto_id', '=', 'productos.id')
            ->selectRaw('productos.nombre, SUM(detalle_orden.cantidad) as cantidad_vendida, SUM(detalle_orden.cantidad * productos.precio) as total')
            ->groupBy('productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit(5)
            ->get();

        $bajoStock = Producto::where('stock', '<', 10)->get();
        $valorInventario = Producto::sum(DB::raw('stock * precio'));
        $gananciaTotal = Orden::where('estado', 'entregado')->sum('total') ?? 0;

        return view('admin.informes', compact(
            'ventas',
            'productosMasVendidos',
            'bajoStock',
            'valorInventario',
            'gananciaTotal'
        ));
    }
}