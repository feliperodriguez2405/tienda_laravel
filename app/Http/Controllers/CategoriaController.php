<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::query();

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Paginación: 10 categorías por página
        $categorias = $query->paginate(10);

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function show(Categoria $categoria)
    {
        return view('categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $categoria->update($request->all());

        // Update associated products' estado to match category's estado
        if ($request->estado === 'inactivo') {
            $categoria->productos()->update(['estado' => 'inactivo']);
        } elseif ($request->estado === 'activo') {
            $categoria->productos()->update(['estado' => 'activo']);
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada');
    }

    public function destroy(Categoria $categoria)
    {
        // Check if the category has associated products
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')->with('error', 'No se puede eliminar, tiene productos asociados.');
        }

        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $categoria = Categoria::create($request->all());

        // Update associated products' estado to match category's estado
        if ($request->estado === 'inactivo') {
            $categoria->productos()->update(['estado' => 'inactivo']);
        } elseif ($request->estado === 'activo') {
            $categoria->productos()->update(['estado' => 'activo']);
        }

        return redirect()->route('categorias.index')->with('success', '¡Categoría creada!');
    }

    public function checkName(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');
        $query = Categoria::where('nombre', $nombre);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Error: El nombre ya está en uso' : ''
        ]);
    }
}