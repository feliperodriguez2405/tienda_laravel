<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Order;

class UserController extends Controller
{
    public function dashboard() {
        return view('users.dashboard');
    }

    public function products() {
        $productos = Producto::all(); // Obtener productos de la base de datos
        return view('users.products', compact('productos'));
    }

    public function orders() {
        $pedidos = auth()->user()->orders; // Obtener pedidos del usuario autenticado
        return view('users.orders', compact('pedidos'));
    }

    public function settings() {
        return view('users.settings');
    }
}
