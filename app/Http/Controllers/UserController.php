<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\User;
use App\Models\Pago;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        // Removed middleware exceptions for index, edit, update, destroy as they are now handled by AdminController
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $query = Producto::where('stock', '>', 0);

        if ($search = $request->query('search')) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        if ($category = $request->query('category')) {
            $query->where('categoria_id', $category);
        }

        $productos = $query->with('categoria')->paginate(12);

        return view('users.dashboard', compact('productos'));
    }

    public function products(Request $request)
    {
        $query = Producto::where('stock', '>', 0);

        if ($search = $request->query('search')) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        if ($category = $request->query('category')) {
            $query->where('categoria_id', $category);
        }

        $productos = $query->with('categoria')->paginate(12);

        return view('users.products', compact('productos'));
    }

    public function orders()
    {
        $ordenes = Orden::where('user_id', auth()->id())->latest()->get();
        return view('users.orders', compact('ordenes'));
    }

    public function cart()
    {
        $cart = session('cart', []);
        $productoIds = array_keys($cart);
        $productos = Producto::whereIn('id', $productoIds)->get();
        return view('users.cart', compact('cart', 'productos'));
    }

    public function addToCart(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $cart = session('cart', []);
        $quantity = $request->input('cantidad');
        $cart[$producto->id] = isset($cart[$producto->id]) ? $cart[$producto->id] + $quantity : $quantity;
        session(['cart' => $cart]);

        return redirect()->route('user.dashboard')->with('success', 'Producto añadido al carrito.');
    }

    public function updateCart(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $cart = session('cart', []);
        if (isset($cart[$producto->id])) {
            $cart[$producto->id] = $request->input('cantidad');
            session(['cart' => $cart]);
            return redirect()->route('user.cart')->with('success', 'Cantidad actualizada.');
        }

        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function removeFromCart(Producto $producto)
    {
        $cart = session('cart', []);
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session(['cart' => $cart]);
            return redirect()->route('user.cart')->with('success', 'Producto eliminado del carrito.');
        }

        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío.');
        }

        $productoIds = array_keys($cart);
        $productos = Producto::whereIn('id', $productoIds)->get();

        return view('users.checkout', compact('cart', 'productos'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,nequi',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío.');
        }

        $productoIds = array_keys($cart);
        $productos = Producto::whereIn('id', $productoIds)->get();

        // Validate stock
        foreach ($productos as $producto) {
            if ($cart[$producto->id] > $producto->stock) {
                return redirect()->route('user.cart')->with('error', "No hay suficiente stock para {$producto->nombre}.");
            }
        }

        try {
            DB::beginTransaction();

            // Calculate total with quantities and 19% IVA
            $subtotal = $productos->sum(fn($p) => $p->precio * $cart[$p->id]);
            $total = $subtotal * 1.19;

            // Create order
            $orden = Orden::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'estado' => $request->metodo_pago === 'efectivo' ? 'entregado' : 'procesando',
                'metodo_pago' => $request->metodo_pago,
            ]);

            // Create order details
            foreach ($productos as $producto) {
                DetalleOrden::create([
                    'orden_id' => $orden->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cart[$producto->id],
                    'subtotal' => $producto->precio * $cart[$producto->id],
                ]);
                $producto->decrement('stock', $cart[$producto->id]);
            }

            // Create payment
            $metodoPago = MetodoPago::where('nombre', $request->metodo_pago)->first();
            if (!$metodoPago) {
                throw new \Exception("Método de pago {$request->metodo_pago} no encontrado.");
            }
            Pago::create([
                'orden_id' => $orden->id,
                'metodo_pago_id' => $metodoPago->id,
                'monto' => $total,
                'estado' => $request->metodo_pago === 'efectivo' ? 'completado' : 'pendiente',
            ]);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return redirect()->route('user.orders')->with('success', 'Orden procesada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error processing checkout: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('user.cart')->with('error', 'Error al procesar la orden: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        $user = auth()->user();
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
}