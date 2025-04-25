<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\User;
use App\Models\Reseña; // Added for reviews
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('users.dashboard');
    }

    public function products(Request $request)
    {
        $search = $request->query('search');
        $productos = Producto::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                         ->orWhere('descripcion', 'like', "%{$search}%");
        })->get();
        return view('users.products', compact('productos'));
    }

    public function orders()
    {
        $pedidos = auth()->user()->orders; // Esto asume una relación en el modelo User
        return view('users.orders', compact('pedidos'));
    }

    public function cart()
    {
        $cart = session('cart', []);
        $productos = Producto::whereIn('id', array_keys($cart))->get();
        return view('users.cart', compact('productos', 'cart'));
    }

    public function addToCart(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:'.$producto->stock,
        ]);

        $cart = session('cart', []);
        $cantidad = $request->input('cantidad', 1);

        if (isset($cart[$producto->id])) {
            $cart[$producto->id] += $cantidad;
        } else {
            $cart[$producto->id] = $cantidad;
        }

        session(['cart' => $cart]);

        return redirect()->route('user.products')->with('success', 'Producto añadido al carrito.');
    }

    public function removeFromCart(Producto $producto)
    {
        $cart = session('cart', []);
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session(['cart' => $cart]);
        }
        return redirect()->route('user.cart')->with('success', 'Producto eliminado del carrito.');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío. Agrega productos antes de proceder.');
        }

        $productos = Producto::whereIn('id', array_keys($cart))->get();
        $total = $productos->sum(function ($producto) use ($cart) {
            return $producto->precio * $cart[$producto->id];
        });

        return view('users.checkout', compact('productos', 'cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío.');
        }

        $request->validate([
            'metodo_pago' => 'required|in:efectivo,nequi',
        ]);

        $productos = Producto::whereIn('id', array_keys($cart))->get();
        $total = $productos->sum(function ($producto) use ($cart) {
            return $producto->precio * $cart[$producto->id];
        });

        foreach ($productos as $producto) {
            if ($producto->stock < $cart[$producto->id]) {
                return redirect()->route('user.cart')->with('error', "Stock insuficiente para {$producto->nombre}.");
            }
        }

        $orden = Orden::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'estado' => 'pendiente',
            'metodo_pago' => $request->metodo_pago,
        ]);

        foreach ($productos as $producto) {
            $subtotal = $producto->precio * $cart[$producto->id];
            DetalleOrden::create([
                'orden_id' => $orden->id,
                'producto_id' => $producto->id,
                'cantidad' => $cart[$producto->id],
                'subtotal' => $subtotal,
            ]);
            $producto->decrement('stock', $cart[$producto->id]);
        }

        session()->forget('cart');

        return redirect()->route('user.orders')->with('success', 'Orden creada con éxito. Por favor, dirígete al local para pagar con ' . $request->metodo_pago . '.');
    }

    public function settings()
    {
        $user = auth()->user(); // Obtener el usuario autenticado
        return view('users.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('user.settings')->with('success', 'Configuración actualizada correctamente.');
    }

    public function showOrder(Orden $orden)
    {
        if ($orden->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        return view('users.show', compact('orden'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('users.user', compact('user'));
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $roles = ['usuario', 'cajero', 'admin'];
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $request->validate([
            'role' => 'required|in:usuario,cajero,admin',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    // New method to display reviews
    public function reviews()
    {
        $reseñas = Reseña::with(['user', 'producto'])->get();
        $productos = Producto::all(); // For the review form
        return view('users.reviews', compact('reseñas', 'productos'));
    }

    // New method to save a review
    public function storeReview(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        // Check if the user has already reviewed this product
        $existingReview = Reseña::where('user_id', Auth::id())
                               ->where('producto_id', $request->producto_id)
                               ->exists();

        if ($existingReview) {
            return redirect()->route('user.reviews')->with('error', 'Ya has dejado una reseña para este producto.');
        }

        Reseña::create([
            'user_id' => Auth::id(),
            'producto_id' => $request->producto_id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
        ]);

        return redirect()->route('user.reviews')->with('success', 'Reseña guardada correctamente.');
    }
}