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

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $productos = $this->getFilteredProducts($request);
        return view('users.dashboard', compact('productos'));
    }

    public function products(Request $request)
    {
        $productos = $this->getFilteredProducts($request);
        return view('users.dashboard', compact('productos'));
    }

    public function orders(Request $request)
    {
        $ordenes = Orden::where('user_id', Auth::id())->latest()->paginate(10);
        return view('users.orders', compact('ordenes'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $productos = Producto::whereIn('id', array_keys($cart))->where('estado', 'activo')->get();
        // Remove inactive products from the cart
        $updatedCart = array_intersect_key($cart, $productos->pluck('id')->toArray());
        if ($cart !== $updatedCart) {
            session()->put('cart', $updatedCart);
            if (empty($updatedCart)) {
                return view('users.cart', compact('cart', 'productos'))->with('info', 'Algunos productos fueron eliminados del carrito porque ya no están disponibles.');
            }
        }
        return view('users.cart', compact('cart', 'productos'));
    }

    public function addToCart(Request $request, Producto $producto)
    {
        // Check if product is active
        if ($producto->estado !== 'activo') {
            return redirect()->route('user.dashboard')->with('error', 'El producto no está disponible.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $cart = session()->get('cart', []);
        $cart[$producto->id] = ($cart[$producto->id] ?? 0) + $request->input('cantidad');
        session()->put('cart', $cart);

        return redirect()->route('user.dashboard')->with('success', 'Producto añadido al carrito.');
    }

    public function updateCart(Request $request, Producto $producto)
    {
        // Check if product is active
        if ($producto->estado !== 'activo') {
            return redirect()->route('user.cart')->with('error', 'El producto no está disponible.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock,
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$producto->id])) {
            $cart[$producto->id] = $request->input('cantidad');
            session()->put('cart', $cart);
            return redirect()->route('user.cart')->with('success', 'Cantidad actualizada.');
        }

        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function removeFromCart(Producto $producto)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session()->put('cart', $cart);
            return redirect()->route('user.cart')->with('success', 'Producto eliminado del carrito.');
        }

        return redirect()->route('user.cart')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function cartCheckout(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,nequi',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'El carrito está vacío.');
        }

        $productos = Producto::whereIn('id', array_keys($cart))->where('estado', 'activo')->get();
        if ($productos->count() < count($cart)) {
            // Update cart to remove inactive products
            $updatedCart = array_intersect_key($cart, $productos->pluck('id')->toArray());
            session()->put('cart', $updatedCart);
            return redirect()->route('user.cart')->with('error', 'Algunos productos en el carrito no están disponibles. Por favor, revisa tu carrito.');
        }

        // Validate stock
        foreach ($productos as $producto) {
            if ($cart[$producto->id] > $producto->stock) {
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
                'estado' => $request->metodo_pago === 'efectivo' ? 'pendiente' : 'procesando',
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
            $metodoPago = MetodoPago::where('nombre', $request->metodo_pago)->firstOrFail();
            Pago::create([
                'orden_id' => $orden->id,
                'metodo_pago_id' => $metodoPago->id,
                'monto' => $total,
                'estado' => $request->metodo_pago === 'efectivo' ? 'pendiente' : 'pendiente',
            ]);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            // Notificación según método de pago
            $sessionKey = $request->metodo_pago === 'efectivo' ? 'efectivo_notification' : 'success';
            $message = $request->metodo_pago === 'efectivo' 
                ? 'Orden procesada. Por favor, diríjase al local para pagar en efectivo y recibir su pedido.'
                : 'Orden procesada con éxito.';

            return redirect()->route('user.orders')->with($sessionKey, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing checkout: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('user.cart')->with('error', 'Error al procesar la orden. Por favor, intenta de nuevo.');
        }
    }

    public function cancelOrder(Request $request, Orden $orden)
    {
        if ($orden->user_id !== Auth::id()) {
            return redirect()->route('user.orders')->with('error', 'No tienes permiso para cancelar esta orden.');
        }

        if ($orden->metodo_pago === 'nequi' && $orden->estado !== 'procesando') {
            return redirect()->route('user.orders')->with('error', 'No se puede cancelar esta orden.');
        }

        if ($orden->metodo_pago === 'efectivo' && $orden->estado !== 'pendiente') {
            return redirect()->route('user.orders')->with('error', 'No se puede cancelar esta orden.');
        }

        try {
            DB::beginTransaction();

            // Restaurar stock
            foreach ($orden->detalles as $detalle) {
                $producto = Producto::findOrFail($detalle->producto_id);
                $producto->increment('stock', $detalle->cantidad);
            }

            // Actualizar estado de la orden
            $orden->estado = 'cancelado';
            $orden->save();

            // Actualizar estado del pago
            $pago = Pago::where('orden_id', $orden->id)->firstOrFail();
            $pago->estado = 'pendiente'; // Usar 'pendiente' para ambos métodos de pago
            $pago->save();

            DB::commit();

            $sessionKey = $orden->metodo_pago === 'nequi' ? 'nequi_cancel' : 'efectivo_cancel';
            $message = $orden->metodo_pago === 'nequi' 
                ? 'Orden cancelada. El reembolso está en proceso.' 
                : 'Orden cancelada. No se realizará el pedido.';

            return redirect()->route('user.orders')->with($sessionKey, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error cancelling order: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return redirect()->route('user.orders')->with('error', 'Error al cancelar la orden: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        $user = Auth::user();
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
        return redirect()->route('user.settings')->with('success', 'Configuración actualizada correctamente.');
    }

    public function showOrder(Orden $orden)
    {
        if ($orden->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }

        return view('users.show', compact('orden'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('users.user', compact('user'));
    }

    private function getFilteredProducts(Request $request)
    {
        $query = Producto::where('stock', '>', 0)->where('estado', 'activo');

        if ($search = $request->query('search')) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        if ($category = $request->query('category')) {
            $query->where('categoria_id', $category);
        }

        return $query->with('categoria')->paginate(12);
    }
}