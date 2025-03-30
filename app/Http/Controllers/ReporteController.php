<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Producto;
use App\Models\DetalleOrden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $ventas = Orden::selectRaw('DATE(created_at) as fecha, SUM(total) as total_vendido')
            ->where('estado', 'entregado')
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->limit(7)
            ->get();

        $productosMasVendidos = DB::table('detalle_ordenes')
            ->join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->join('ordenes', 'detalle_ordenes.orden_id', '=', 'ordenes.id')
            ->where('ordenes.estado', 'entregado')
            ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.subtotal) as total')
            ->groupBy('productos.nombre')
            ->orderBy('cantidad_vendida', 'desc')
            ->limit(5)
            ->get();

        $bajoStock = Producto::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        $valorInventario = Producto::selectRaw('SUM(stock * precio) as valor_total')->first()->valor_total ?? 0;

        return view('admin.informes', compact('ventas', 'productosMasVendidos', 'bajoStock', 'valorInventario'));
    }
}