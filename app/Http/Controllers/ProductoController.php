<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class ProductoController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('ProductoController::index called');

            $query = Producto::query()->with('categoria');

            if ($request->filled('search')) {
                $query->where('nombre', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('category')) {
                $query->where('categoria_id', $request->category);
            }

            if ($request->filled('stock')) {
                if ($request->stock == 'low') {
                    $query->where('stock', '<=', 10);
                } elseif ($request->stock == 'medium') {
                    $query->whereBetween('stock', [11, 50]);
                } elseif ($request->stock == 'high') {
                    $query->where('stock', '>', 50);
                }
            }

            $productos = $query->paginate(9); // Changed to 9 products per page
            Log::info('Productos paginated', ['count' => $productos->count(), 'total' => $productos->total(), 'per_page' => $productos->perPage()]);

            $categorias = Categoria::all();

            return view('productos.index', compact('productos', 'categorias'));
        } catch (Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return view('productos.index', ['productos' => collect(), 'categorias' => Categoria::all()])
                ->with('error', 'Error al cargar los productos. Por favor, intenta de nuevo.');
        }
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

   public function store(Request $request)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('productos', 'nombre'),
        ],
        'descripcion' => 'required|string',
        'precio' => 'required|numeric|min:0',
        'precio_compra' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categoria_id' => 'required|exists:categorias,id',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'nombre.unique' => 'El nombre ya está en uso.',
        'nombre.required' => 'El nombre es obligatorio.',
        'precio.required' => 'El precio es obligatorio.',
        'stock.required' => 'El stock es obligatorio.',
        'categoria_id.required' => 'La categoría es obligatoria.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
        'imagen.max' => 'La imagen no debe superar los 2MB.',
    ]);
    $rutaImagen = null;
    if ($request->hasFile('imagen')) {
        $rutaImagen = $request->file('imagen')->store('productos', 'public');
    }

    Producto::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'precio' => $request->precio,
        'precio_compra' => $request->precio_compra,
        'stock' => $request->stock,
        'categoria_id' => $request->categoria_id,
        'imagen' => $rutaImagen,
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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'precio', 'precio_compra', 'stock', 'categoria_id']);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::delete('public/' . $producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::delete('public/' . $producto->imagen);
        }

        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}