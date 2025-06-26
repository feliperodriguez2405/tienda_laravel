
<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch top 5 recently added active products
        $productosDestacados = Producto::leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->selectRaw('
                productos.id,
                productos.nombre,
                productos.descripcion,
                productos.imagen,
                productos.precio,
                productos.stock,
                productos.categoria_id,
                COALESCE(categorias.nombre, "Sin categoría") as categoria_nombre,
                0 as cantidad_vendida,
                0 as total
            ')
            ->where('productos.estado', 'activo')
            ->orderByDesc('productos.created_at') // Or orderByDesc('productos.stock') for high stock
            ->limit(5)
            ->get();

        // Log data for debugging
        Log::info('ProductosDestacados', ['data' => $productosDestacados->toArray()]);

        return view('home', compact('productosDestacados'));
    }
}
