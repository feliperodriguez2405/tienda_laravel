<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Orden, DetalleOrden, User};
use Illuminate\Support\Facades\{Log, Auth};
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Display a listing of users.
     */
    public function usersIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        try {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error creando usuario: ' . $e->getMessage());
            return redirect()->route('admin.users.create')->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['usuario', 'cajero', 'admin'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        try {
            if ($request->filled('password')) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando usuario ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('admin.users.edit', $user)->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'No puedes eliminar tu propio usuario.'], 403);
        }

        try {
            $user->delete();
            return response()->json(['success' => 'Usuario eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error eliminando usuario ID ' . $user->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'No se puede eliminar el usuario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of orders.
     */
    public function pedidos(Request $request)
    {
        $query = Orden::with(['detalles.producto', 'user']);

        if ($request->filled('client_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('client_name') . '%');
            });
        }

        if ($request->filled('order_id')) {
            $query->where('id', $request->input('order_id'));
        }

        if ($request->filled('status')) {
            $query->where('estado', $request->input('status'));
        }

        $ordenes = $query->latest()->paginate(10);
        return view('admin.pedidos', compact('ordenes'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Orden $orden)
    {
        $request->validate(['estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado']);

        try {
            $orden->update(['estado' => $request->estado]);
            return redirect()->route('admin.pedidos')->with('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando estado de orden ID ' . $orden->id . ': ' . $e->getMessage());
            return redirect()->route('admin.pedidos')->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Process a refund for the specified order.
     */
    public function refund(Request $request, Orden $orden)
    {
        if ($orden->estado !== 'entregado') {
            return redirect()->route('admin.pedidos')->with('error', 'Solo se pueden reembolsar órdenes entregadas.');
        }

        try {
            $orden->update(['estado' => 'cancelado']);
            return redirect()->route('admin.pedidos')->with('success', 'Reembolso procesado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error procesando reembolso para orden ID ' . $orden->id . ': ' . $e->getMessage());
            return redirect()->route('admin.pedidos')->with('error', 'Error al procesar el reembolso: ' . $e->getMessage());
        }
    }

    /**
     * Generate an invoice for the specified order.
     */
    public function generateInvoice(Orden $orden)
    {
        try {
            $pdf = Pdf::loadView('admin.facture_pdf', compact('orden'));
            return $pdf->download('factura_pedido_' . $orden->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generando factura para orden ID ' . $orden->id . ': ' . $e->getMessage());
            return redirect()->route('admin.pedidos')->with('error', 'Error al generar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Generate an HTML invoice for the specified order.
     */
    public function invoice(Orden $orden)
    {
        return view('admin.invoice', compact('orden'));
    }
}