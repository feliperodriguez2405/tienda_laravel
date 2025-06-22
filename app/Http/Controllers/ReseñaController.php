<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reseña;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class ReseñaController extends Controller
{
    // Mostrar formulario y reseñas
    public function index()
    {
        $reseñas = Reseña::with(['user', 'producto'])->latest()->get();
        $productos = Producto::all();

        return view('users.reviews', compact('reseñas', 'productos'));
    }

    // Almacenar una nueva reseña
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        Reseña::create([
            'user_id' => Auth::id(),
            'producto_id' => $request->producto_id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
        ]);

        return redirect()->route('user.reviews')->with('success', '¡Reseña enviada correctamente!');
    }
}
