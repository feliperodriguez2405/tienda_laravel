<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('ProductoController::index called');

            $query = Producto::query()->with('categoria');

            if ($request->filled('search')) {
                $query->where('nombre', 'like', '%' . $request->search . '%')
                      ->orWhere('codigo_barra', 'like', '%' . $request->search . '%');
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

            $productos = $query->paginate(9);
            Log::info('Productos paginated', ['count' => $productos->count(), 'total' => $productos->total(), 'per_page' => $productos->perPage()]);

            $categorias = Categoria::all();

            return view('productos.index', compact('productos', 'categorias'));
        } catch (\Exception $e) {
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
            'codigo_barra' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('productos', 'codigo_barra'),
            ],
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
            'porcentaje_ganancia' => 'nullable|numeric|in:20,25,30,40,50',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'nombre.unique' => 'El nombre ya está en uso.',
            'codigo_barra.unique' => 'El código de barras ya está en uso.',
            'nombre.required' => 'El nombre es obligatorio.',
            'precio.required' => 'El precio es obligatorio.',
            'precio_compra.numeric' => 'El precio de compra debe ser un número.',
            'porcentaje_ganancia.in' => 'El porcentaje de ganancia debe ser 20%, 25%, 30%, 40% o 50%.',
            'stock.required' => 'El stock es obligatorio.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'imagen.image' => 'La imagen debe ser un archivo de imagen válido.',
            'imagen.max' => 'La imagen no debe superar los 2MB.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $data = $request->only(['nombre', 'codigo_barra', 'descripcion', 'precio', 'precio_compra', 'stock', 'categoria_id', 'estado']);

        // Capitalizar la primera letra de cada palabra en nombre y descripción
        $data['nombre'] = $this->capitalizeWords($data['nombre']);
        $data['descripcion'] = $this->capitalizeWords($data['descripcion']);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $data['codigo_barra'] = $request->codigo_barra ?? $this->generarCodigoBarra();

        // Calcular precio si se proporciona porcentaje y precio de compra
        if ($request->filled('porcentaje_ganancia') && $request->filled('precio_compra')) {
            $data['precio'] = $request->precio_compra * (1 + $request->porcentaje_ganancia / 100);
        }

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', 'Producto agregado correctamente.');
    }

    public function show(Producto $producto)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($producto->codigo_barra, $generator::TYPE_CODE_128));
        return view('productos.show', compact('producto', 'barcode'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'nombre')->ignore($producto->id),
            ],
            'codigo_barra' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('productos', 'codigo_barra')->ignore($producto->id),
            ],
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
            'porcentaje_ganancia' => 'nullable|numeric|in:20,25,30,40,50',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $data = $request->only(['nombre', 'codigo_barra', 'descripcion', 'precio', 'precio_compra', 'stock', 'categoria_id', 'estado']);

        // Capitalizar la primera letra de cada palabra en nombre y descripción
        $data['nombre'] = $this->capitalizeWords($data['nombre']);
        $data['descripcion'] = $this->capitalizeWords($data['descripcion']);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::delete('public/' . $producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Calcular precio si se proporciona porcentaje y precio de compra
        if ($request->filled('porcentaje_ganancia') && $request->filled('precio_compra')) {
            $data['precio'] = $request->precio_compra * (1 + $request->porcentaje_ganancia / 100);
        }

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->detallesOrdenes()->exists()) {
            return redirect()->route('productos.index')->with('error', 'No se puede eliminar, tiene pedidos asociados.');
        }

        if ($producto->imagen) {
            Storage::delete('public/' . $producto->imagen);
        }

        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function buscar(Request $request)
    {
        $codigoBarra = $request->query('codigo_barra');
        $producto = Producto::where('codigo_barra', $codigoBarra)->first();

        if ($producto) {
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($producto->codigo_barra, $generator::TYPE_CODE_128));
            return response()->json([
                'success' => true,
                'producto' => $producto,
                'barcode' => $barcode,
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function generarBarcode(Request $request)
    {
        $codigoBarra = $request->query('codigo_barra') ?? $this->generarCodigoBarra();
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($codigoBarra, $generator::TYPE_CODE_128));
        return response()->json(['barcode' => $barcode]);
    }

    private function generarCodigoBarra()
    {
        return 'PROD' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function capitalizeWords($string)
    {
        return implode(' ', array_map(function($word) {
            return mb_strtoupper(mb_substr($word, 0, 1)) . mb_strtolower(mb_substr($word, 1));
        }, explode(' ', trim($string))));
    }
}