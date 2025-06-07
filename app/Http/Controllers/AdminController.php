<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Orden, DetalleOrden, User};
use Illuminate\Support\Facades\{Log, Auth};

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
        // Initialize query builder for users
        $query = User::query();

        // Apply search filters if provided
        if ($request->filled('name')) {
            // Filter by name (case-insensitive, partial match)
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            // Filter by email (case-insensitive, partial match)
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('role')) {
            // Filter by role (exact match)
            $query->where('role', $request->input('role'));
        }

        // Paginate results, 10 per page
        $users = $query->paginate(10);

        // Pass the filtered and paginated users to the view
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
            return redirect()->route('admin.users.create')->with('error', 'Error al crear el usuario. Intenta de nuevo.');
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
            return redirect()->route('admin.users.edit', $user)->with('error', 'Error al actualizar el usuario. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        if ($user->ordenes()->exists()) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar un usuario con órdenes asociadas.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando usuario ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error al eliminar el usuario. Intenta de nuevo.');
        }
    }

    /**
     * Display a listing of orders.
     */
    public function pedidos(Request $request)
    {
        // Initialize query builder for orders with related user and details
        $query = Orden::with(['detalles.producto', 'user']);

        // Apply search filters if provided
        if ($request->filled('client_name')) {
            // Filter by client name (case-insensitive, partial match)
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('client_name') . '%');
            });
        }

        if ($request->filled('order_id')) {
            // Filter by order ID (exact match)
            $query->where('id', $request->input('order_id'));
        }

        if ($request->filled('status')) {
            // Filter by status (exact match)
            $query->where('estado', $request->input('status'));
        }

        // Paginate results, 10 per page, and order by latest
        $ordenes = $query->latest()->paginate(10);

        // Pass the filtered and paginated orders to the view
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
            return redirect()->route('admin.pedidos')->with('error', 'Error al actualizar el estado. Intenta de nuevo.');
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
            return redirect()->route('admin.pedidos')->with('error', 'Error al procesar el reembolso. Intenta de nuevo.');
        }
    }

    /**
     * Generate an invoice for the specified order.
     */
    public function generateInvoice(Orden $orden)
    {
        try {
            // Placeholder for invoice generation logic
            return response()->json(['message' => "Factura generada para orden {$orden->id}"]);
        } catch (\Exception $e) {
            Log::error('Error generando factura para orden ID ' . $orden->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar la factura.'], 500);
        }
    }
}