<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Order;
use App\Models\User; // Asegúrate de importar el modelo User

class UserController extends Controller
{
    public function dashboard()
    {
        return view('users.dashboard');
    }

    public function products()
    {
        $productos = Producto::all(); // Obtener productos de la base de datos
        return view('users.products', compact('productos'));
    }

    public function orders()
    {
        $pedidos = auth()->user()->orders; // Obtener pedidos del usuario autenticado
        return view('users.orders', compact('pedidos'));
    }

    public function settings()
    {
        return view('users.settings');
    }

    // Mostrar la lista de usuarios (solo para admin)
    public function index()
    {
        $this->authorize('viewAny', User::class); // Opcional: usar políticas de autorización
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Mostrar formulario para editar un usuario (solo para admin)
    public function edit(User $user)
    {
        $this->authorize('update', $user); // Opcional: usar políticas de autorización
        $roles = ['usuario', 'cajero', 'admin']; // Roles disponibles
        return view('users.edit', compact('user', 'roles'));
    }

    // Actualizar el rol de un usuario (solo para admin)
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user); // Opcional: usar políticas de autorización
        $request->validate([
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Rol actualizado correctamente.');
    }

    // Eliminar un usuario (solo para admin)
    public function destroy(User $user)
    {
        $this->authorize('delete', $user); // Opcional: usar políticas de autorización
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}