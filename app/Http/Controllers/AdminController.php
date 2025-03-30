<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\DetalleOrden;

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

    public function pedidos()
    {
        // Obtener todos los pedidos con sus detalles y usuarios
        $ordenes = Orden::with(['detalles.producto', 'user'])->orderBy('created_at', 'desc')->get();
        return view('admin.pedidos', compact('ordenes'));
    }

    public function updateStatus(Request $request, Orden $orden)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);

        $orden->update(['estado' => $request->estado]);

        return redirect()->route('admin.pedidos')->with('success', 'Estado del pedido actualizado correctamente.');
    }

    public function refund(Request $request, Orden $orden)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
        ]);

        $orden->update([
            'estado' => 'cancelado',
            'motivo_reembolso' => $request->motivo,
        ]);

        return redirect()->route('admin.pedidos')->with('success', 'Reembolso procesado correctamente.');
    }

    public function generateInvoice(Orden $orden)
    {
        $orden->load(['detalles.producto', 'user']); // Cargar relaciones para la factura
        return view('admin.invoice', compact('orden'));
    }

    // Métodos para gestión de usuarios (sin cambios)
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
        // Lógica para crear usuario (ajusta según tus necesidades)
    }

    public function edit($user)
    {
        $user = User::findOrFail($user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        // Lógica para actualizar usuario (ajusta según tus necesidades)
    }

    public function destroy($user)
    {
        User::findOrFail($user)->delete();
        return redirect()->route('admin.users.index');
    }
}