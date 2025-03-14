<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtro por búsqueda de nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('categoria_id', $request->category);
        }

        // Filtro por nivel de stock
        if ($request->filled('stock')) {
            if ($request->stock == 'low') {
                $query->where('stock', '<=', 10);
            } elseif ($request->stock == 'medium') {
                $query->whereBetween('stock', [11, 50]);
            } elseif ($request->stock == 'high') {
                $query->where('stock', '>', 50);
            }
        }

        // Obtener productos paginados y categorías para el filtro
        $productos = $query->paginate(9); // 9 productos por página (3 por fila)
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar imagen si existe
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public'); // Guarda en storage/app/public/productos
        }

        // Crear producto con la imagen
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'imagen' => $rutaImagen, // Guarda la ruta de la imagen en la base de datos
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto agregado correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Si hay una nueva imagen, eliminar la anterior y guardar la nueva
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::delete('public/' . $producto->imagen);
            }
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        // Actualizar los demás datos del producto
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
            'imagen' => $producto->imagen, // Mantiene la imagen existente si no se cambia
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::delete('public/' . $producto->imagen);
        }

        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}