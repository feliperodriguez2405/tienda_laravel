<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\MetodoPago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $productos = $this->getFilteredProducts($request);
        Log::info('Dashboard accessed', ['user_id' => Auth::id(), 'session_id' => session()->getId(), 'cart' => session('cart')]);
        return view('users.dashboard', compact('productos'));
    }

    public function products(Request $request)
    {
        $productos = $this->getFilteredProducts($request);
        Log::info('Products page accessed', ['user_id' => Auth::id(), 'session_id' => session()->getId(), 'cart' => session('cart')]);
        return view('users.dashboard', compact('productos'));
    }

    public function orders(Request $request)
    {
        $ordenes = Orden::where('user_id', Auth::id())->latest()->paginate(10);
        Log::info('Orders page accessed', ['user_id' => Auth::id(), 'session_id' => session()->getId(), 'cart' => session('cart')]);
        return view('users.orders', compact('ordenes'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        Log::info('Cart accessed', ['cart' => $cart, 'session_id' => session()->getId(), 'user_id' => Auth::id()]);

        if (empty($cart)) {
            Log::warning('Cart is empty on access', ['session_id' => session()->getId(), 'user_id' => Auth::id()]);
            return view('users.cart', ['cart' => [], 'productos' => collect([])])
                ->with('error', 'Tu carrito está vacío.');
        }

        $productoIds = array_keys($cart);
        $productos = Producto::whereIn('id', $productoIds)->where('estado', 'activo')->get();
        Log::info('Products retrieved for cart', [
            'producto_ids' => $productoIds,
            'productos_count' => $productos->count(),
            'productos' => $productos->pluck('id')->toArray(),
            'user_id' => Auth::id()
        ]);

        // Only remove products from cart if they are not found or inactive
        $validProductoIds = $productos->pluck('id')->toArray();
        $updatedCart = array_intersect_key($cart, array_flip($validProductoIds));
        if ($cart !== $updatedCart) {
            Log::warning('Cart updated due to invalid products', [
                'original_cart' => $cart,
                'updated_cart' => $updatedCart,
                'removed_ids' => array_diff(array_keys($cart), $validProductoIds)
            ]);
            session()->put('cart', $updatedCart);
            if (empty($updatedCart)) {
                return view('users.cart', ['cart' => [], 'productos' => collect([])])
                    ->with('info', 'Algunos productos fueron eliminados del carrito porque ya no están disponibles.');
            }
        }

        return view('users.cart', compact('cart', 'productos'));
    }

    public function addToCart(Request $request, Producto $producto)
    {
        Log::info('Attempting to add product to cart', [
            'producto_id' => $producto->id,
            'producto_estado' => $producto->estado,
            'stock' => $producto->stock,
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        if ($producto->estado !== 'activo') {
            Log::warning('Attempt to add inactive product to cart', ['producto_id' => $producto->id]);
            return redirect()->route('user.dashboard')->with('error', 'El producto no está disponible.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',
            'cantidad.max' => 'La cantidad no puede superar el stock disponible.',
        ]);

        $cart = session()->get('cart', []);
        $cart[$producto->id] = ($cart[$producto->id] ?? 0) + $request->input('cantidad');
        session()->put('cart', $cart);
        session()->save(); // Explicitly save the session
        Log::info('Product added to cart', [
            'producto_id' => $producto->id,
            'cantidad' => $request->input('cantidad'),
            'cart' => $cart,
            'session_id' => session()->getId()
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Producto añadido al carrito.');
    }

    public function updateCart(Request $request, Producto $producto)
    {
        Log::info('Attempting to update cart', [
            'producto_id' => $producto->id,
            'producto_estado' => $producto->estado,
            'stock' => $producto->stock,
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        if ($producto->estado !== 'activo') {
            Log::warning('Attempt to update inactive product in cart', ['producto_id' => $producto->id]);
            return redirect()->route('user.cart')->with('error', 'El producto no está disponible.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',
            'cantidad.max' => 'La cantidad no puede superar el stock disponible.',
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$producto->id])) {
            $cart[$producto->id] = $request->input('cantidad');
            session()->put('cart', $cart);
            session()->save(); // Explicitly save the session
            Log::info('Cart updated', [
                'producto_id' => $producto->id,
                'cantidad' => $request->input('cantidad'),
                'cart' => $cart,
                'session_id' => session()->getId()
            ]);
            return redirect()->route('user.cart')->with('success', 'Cantidad actualizada.');
        }

        Log::warning('Product not found in cart for update', ['producto_id' => $producto->id]);
        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function removeFromCart(Producto $producto)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session()->put('cart', $cart);
            session()->save(); // Explicitly save the session
            Log::info('Product removed from cart', [
                'producto_id' => $producto->id,
                'cart' => $cart,
                'session_id' => session()->getId()
            ]);
            return redirect()->route('user.cart')->with('success', 'Producto eliminado del carrito.');
        }

        Log::warning('Product not found in cart for removal', ['producto_id' => $producto->id]);
        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function cartCheckout(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,nequi',
        ], [
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago seleccionado no es válido.',
        ]);

        $cart = session()->get('cart', []);
        Log::info('Checkout initiated', [
            'cart' => $cart,
            'session_id' => session()->getId(),
            'metodo_pago' => $request->metodo_pago,
            'user_id' => Auth::id()
        ]);

        if (empty($cart)) {
            Log::warning('Checkout attempted with empty cart', ['session_id' => session()->getId(), 'user_id' => Auth::id()]);
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío.');
        }

        $productos = Producto::whereIn('id', array_keys($cart))->where('estado', 'activo')->get();
        Log::info('Products retrieved for checkout', [
            'producto_ids' => array_keys($cart),
            'productos_count' => $productos->count(),
            'productos' => $productos->pluck('id')->toArray(),
            'user_id' => Auth::id()
        ]);

        if ($productos->isEmpty()) {
            Log::warning('No active products found in cart during checkout', ['cart' => $cart, 'user_id' => Auth::id()]);
            return redirect()->route('user.cart')->with('error', 'No hay productos activos en el carrito. Por favor, revisa tu carrito.');
        }

        // Validate stock
        foreach ($productos as $producto) {
            if (!isset($cart[$producto->id]) || $cart[$producto->id] > $producto->stock) {
                Log::warning('Insufficient stock for product during checkout', [
                    'producto_id' => $producto->id,
                    'cart_qty' => $cart[$producto->id] ?? 0,
                    'stock' => $producto->stock,
                    'user_id' => Auth::id()
                ]);
                return redirect()->route('user.cart')->with('error', "No hay suficiente stock para {$producto->nombre}.");
            }
        }

        try {
            DB::beginTransaction();

            // Calculate total with 19% IVA
            $subtotal = $productos->sum(fn($p) => $p->precio * $cart[$p->id]);
            $total = $subtotal * 1.19;

            // Create order
            $orden = Orden::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'estado' => 'procesando',
                'metodo_pago' => $request->metodo_pago,
            ]);
            Log::info('Order created', [
                'orden_id' => $orden->id,
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'user_id' => Auth::id()
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
                Log::info('Order detail created and stock updated', [
                    'producto_id' => $producto->id,
                    'cantidad' => $cart[$producto->id],
                    'user_id' => Auth::id()
                ]);
            }

            // Create payment
            $metodoPago = MetodoPago::where('nombre', $request->metodo_pago)->firstOrFail();
            Pago::create([
                'orden_id' => $orden->id,
                'metodo_pago_id' => $metodoPago->id,
                'monto' => $total,
                'estado' => 'pendiente',
            ]);
            Log::info('Payment created', [
                'orden_id' => $orden->id,
                'metodo_pago_id' => $metodoPago->id,
                'monto' => $total,
                'user_id' => Auth::id()
            ]);

            // Clear cart
            session()->forget('cart');
            session()->save(); // Explicitly save the session
            Log::info('Cart cleared after successful checkout', ['session_id' => session()->getId(), 'user_id' => Auth::id()]);

            DB::commit();

            $message = $request->metodo_pago === 'efectivo'
                ? 'Orden procesada. Por favor, diríjase al local para pagar en efectivo mientras los trabajadores preparan su pedido.'
                : 'Orden procesada. Por favor, realice el pago con Nequi al número 3152971513 mientras los trabajadores preparan su pedido.';

            return redirect()->route('user.cart')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing checkout: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
                'cart' => $cart,
                'metodo_pago' => $request->metodo_pago,
                'session_id' => session()->getId(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('user.cart')->with('error', 'Error al procesar la orden: ' . $e->getMessage());
        }
    }

    public function cancelOrder(Request $request, Orden $orden)
    {
        if ($orden->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to cancel order', ['orden_id' => $orden->id, 'user_id' => Auth::id()]);
            return redirect()->route('user.orders')->with('error', 'No tienes permiso para cancelar esta orden.');
        }

        if ($orden->estado !== 'procesando') {
            Log::warning('Cannot cancel order due to state', ['orden_id' => $orden->id, 'estado' => $orden->estado]);
            return redirect()->route('user.orders')->with('error', 'No se puede cancelar esta orden.');
        }

        try {
            DB::beginTransaction();

            foreach ($orden->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle->producto_id);
                $producto->increment('stock', $detalle->cantidad);
                Log::info('Stock restored for cancelled order', ['producto_id' => $producto->id, 'cantidad' => $detalle->cantidad]);
            }

            $orden->estado = 'cancelado';
            $orden->save();

            $pago = Pago::where('orden_id', $orden->id)->firstOrFail();
            $pago->estado = 'cancelado';
            $pago->save();
            Log::info('Order and payment cancelled', ['orden_id' => $orden->id, 'pago_id' => $pago->id]);

            DB::commit();

            $message = $orden->metodo_pago === 'nequi'
                ? 'Orden cancelada. El reembolso está en proceso.'
                : 'Orden cancelada. No se realizará el pedido.';

            return redirect()->route('user.orders')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error cancelling order: {$e->getMessage()}", ['trace' => $e->getTraceAsString(), 'orden_id' => $orden->id]);
            return redirect()->route('user.orders')->with('error', 'Error al cancelar la orden: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        $user = Auth::user();
        Log::info('Settings page accessed', ['user_id' => Auth::id(), 'session_id' => session()->getId(), 'cart' => session('cart')]);
        return view('users.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

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
        Log::info('User settings updated', ['user_id' => $user->id, 'data' => $data]);
        return redirect()->route('user.settings')->with('success', 'Configuración actualizada correctamente.');
    }

    public function showOrder(Orden $orden)
    {
        if ($orden->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to view order', ['orden_id' => $orden->id, 'user_id' => Auth::id()]);
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        Log::info('Order details viewed', ['orden_id' => $orden->id, 'user_id' => Auth::id()]);
        return view('users.show', compact('orden'));
    }

    public function profile()
    {
        $user = Auth::user();
        Log::info('Profile page accessed', ['user_id' => Auth::id(), 'session_id' => session()->getId(), 'cart' => session('cart')]);
        return view('users.user', compact('user'));
    }

    private function getFilteredProducts(Request $request)
    {
        $query = Producto::where('stock', '>', 0)
                        ->where('estado', 'activo')
                        ->orderBy('created_at', 'desc');

        if ($search = $request->query('search')) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        if ($category = $request->query('category')) {
            $query->where('categoria_id', $category);
        }

        return $query->with('categoria')->paginate(9);
    }
}