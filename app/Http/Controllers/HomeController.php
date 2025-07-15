<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Reseña;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener 8 productos activos de forma aleatoria
        $productos = Producto::leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->selectRaw('
                productos.id,
                productos.nombre,
                productos.descripcion,
                productos.imagen,
                productos.precio,
                productos.stock,
                productos.categoria_id,
                COALESCE(categorias.nombre, "Sin categoría") as categoria_nombre
            ')
            ->where('productos.estado', 'activo')
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Obtener 8 reseñas de forma aleatoria
        $reseñas = Reseña::with('user')
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Registrar datos para depuración
        Log::info('8 productos activos aleatorios', ['data' => $productos->toArray()]);
        Log::info('8 reseñas aleatorias', ['data' => $reseñas->toArray()]);

        return view('home', compact('productos', 'reseñas'));
    }

    public function productos()
    {
        // Obtener todos los productos activos con paginación
        $productos = Producto::leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
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
            ->orderByDesc('productos.created_at')
            ->paginate(12); // Paginación de 12 productos por página

        // Registrar datos para depuración
        Log::info('Todos los Productos', ['data' => $productos->toArray()]);

        return view('productos', compact('productos'));
    }
}