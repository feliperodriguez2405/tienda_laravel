<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Importar el facade Hash

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function usuarios()
    {
        $users = User::all();
        return view('admin.usuarios', compact('users'));
    }

    public function orders()
    {
        return view('admin.orders');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    // Mostrar la lista de usuarios
    public function usersIndex()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Mostrar formulario para crear un usuario
    public function create()
    {
        return view('admin.users.create');
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Ahora Hash está disponible
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario para editar un usuario
    public function edit(User $user)
    {
        $roles = ['usuario', 'cajero', 'admin'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Actualizar el rol de un usuario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Rol actualizado correctamente.');
    }

    // Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}