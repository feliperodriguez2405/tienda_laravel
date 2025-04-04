<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Orden, DetalleOrden, User};

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function usuarios()
    {
        return view('admin.usuarios', ['users' => User::all()]);
    }

    public function pedidos()
    {
        $ordenes = Orden::with(['detalles.producto', 'user'])->latest()->get();
        return view('admin.pedidos', compact('ordenes'));
    }

    public function updateStatus(Request $request, Orden $orden)
    {
        $request->validate(['estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado']);
        $orden->update(['estado' => $request->estado]);
        return redirect()->route('admin.pedidos')->with('success', 'Estado actualizado.');
    }

    public function refund(Request $request, Orden $orden)
    {
        if ($orden->estado !== 'entregado') {
            return redirect()->route('admin.pedidos')->with('error', 'Solo se pueden reembolsar órdenes entregadas.');
        }
        $orden->update(['estado' => 'cancelado']);
        return redirect()->route('admin.pedidos')->with('success', 'Reembolso procesado.');
    }

    public function generateInvoice(Orden $orden)
    {
        return response()->json(['message' => "Factura generada para orden {$orden->id}"]);
    }

    public function usersIndex()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        $roles = ['usuario', 'cajero', 'admin']; // Roles definidos en el enum de la migración
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:usuario,cajero,admin', // Valida contra los valores del enum
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        if ($user->ordenes()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar un usuario con órdenes asociadas.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}