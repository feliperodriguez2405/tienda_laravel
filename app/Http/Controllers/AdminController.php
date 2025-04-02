<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Orden, DetalleOrden, User, Proveedor, OrdenCompra, Producto};
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\{Log, DB};

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('admin.dashboard');
    }

    // Gestión de Usuarios
    public function usuarios()
    {
        return view('admin.usuarios', ['users' => User::all()]);
    }

    // Gestión de Pedidos
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

    // CRUD Usuarios
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
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
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

    // Gestión de Proveedores
    public function proveedores()
    {
        return view('admin.proveedores.index', ['proveedores' => Proveedor::all()]);
    }

    public function proveedorCreate()
    {
        return view('admin.proveedores.create');
    }

    public function proveedorStore(Request $request)
    {
        $data = $this->validateProveedor($request);
        Proveedor::create($data);
        return redirect()->route('admin.proveedores')->with('success', 'Proveedor registrado correctamente.');
    }

    public function proveedorEdit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function proveedorUpdate(Request $request, Proveedor $proveedor)
    {
        $data = $this->validateProveedor($request, $proveedor->id);
        $proveedor->update($data);
        return redirect()->route('admin.proveedores')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function proveedorDestroy(Proveedor $proveedor)
    {
        if ($proveedor->ordenesCompra()->exists()) {
            return redirect()->route('admin.proveedores')->with('error', 'No se puede eliminar el proveedor porque tiene órdenes de compra asociadas.');
        }
        $proveedor->delete();
        return redirect()->route('admin.proveedores')->with('success', 'Proveedor eliminado correctamente.');
    }

    // Órdenes de Compra
    public function ordenCompraCreate(Proveedor $proveedor)
    {
        return view('admin.proveedores.orden_create', compact('proveedor'));
    }

    public function ordenCompraStore(Request $request, Proveedor $proveedor)
    {
        $data = $this->validateOrdenCompra($request);

        // No calculamos monto al crear, el proveedor lo definirá al entregar
        $ordenCompra = $proveedor->ordenesCompra()->create([
            'proveedor_id' => $proveedor->id,
            'fecha' => $data['fecha'],
            'monto' => 0, // Monto inicial en 0, se actualizará después
            'estado' => $data['estado'],
            'detalles' => $data['detalles'],
            'confirmado_por_vendedor' => false,
        ]);

        if ($data['estado'] === 'entregado') {
            $this->updateProductStock($data['detalles']);
        }

        if ($proveedor->email && $proveedor->recibir_notificaciones) {
            return $this->sendOrdenCompraNotification($ordenCompra, $proveedor);
        }

        return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
            ->with('success', 'Orden de compra registrada.');
    }

    public function ordenCompraShow(Proveedor $proveedor, OrdenCompra $orden)
    {
        // Verifica que la orden pertenezca al proveedor
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        return view('admin.proveedores.orden_show', compact('proveedor', 'orden'));
    }

    // Nuevo método para actualizar monto y precios al entregar
    public function ordenCompraUpdateMonto(Request $request, OrdenCompra $ordenCompra)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'detalles' => 'required|array',
            'detalles.*.producto' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
            'detalles.*.descripcion' => 'nullable|string|max:500',
        ]);

        // Verificar que los productos coincidan con los originales
        $originalDetalles = collect($ordenCompra->detalles)->pluck('producto')->toArray();
        $newDetalles = collect($request->detalles)->pluck('producto')->toArray();
        if ($originalDetalles != $newDetalles) {
            return redirect()->back()->with('error', 'Los productos no coinciden con la orden original.');
        }

        $ordenCompra->update([
            'monto' => $request->monto,
            'estado' => $request->estado,
            'detalles' => $request->detalles, // Actualizar detalles con precios
            'confirmado_por_vendedor' => true,
        ]);

        if ($request->estado === 'entregado') {
            $this->updateProductStockAndPrice($request->detalles);
        }

        return redirect()->route('admin.proveedores.ordenes.historial', $ordenCompra->proveedor)
            ->with('success', 'Orden actualizada y stock ajustado.');
    }

    public function historialCompras(Proveedor $proveedor)
    {
        $ordenes = $proveedor->ordenesCompra()->latest()->paginate(10); // Paginación de 10 elementos por página
        return view('admin.proveedores.historial', compact('proveedor', 'ordenes'));
    }   

    // Configuración de Correo
    public function configurarCorreo()
    {
        $correo_notificaciones = config('mail.notificaciones_correo', 'alphasoft.cmjff@gmail.com');
        return view('admin.proveedores.configurar_correo', compact('correo_notificaciones'));
    }

    public function guardarCorreo(Request $request)
    {
        $data = $request->validate([
            'correo_notificaciones' => 'required|email',
        ]);
        config(['mail.notificaciones_correo' => $data['correo_notificaciones']]);
        file_put_contents(config_path('mail.php'), '<?php return ' . var_export(config('mail'), true) . ';');
        return redirect()->route('admin.proveedores')->with('success', 'Correo de notificaciones actualizado.');
    }

    // Gestión de Productos
    public function productos()
    {
        $productos = Producto::with('categoria')->get();
        return view('admin.productos.index', compact('productos'));
    }

    public function productoUpdate(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'precio' => 'required|numeric|min:0', // Usamos 'precio' como precio_venta
            'stock' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string|max:500',
        ]);
        $producto->update($data);
        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente.');
    }

    public function productoDestroy(Producto $producto)
    {
        if ($producto->detallesOrdenes()->exists()) {
            return redirect()->route('admin.productos')->with('error', 'No se puede eliminar el producto porque está asociado a órdenes.');
        }
        $producto->delete();
        return redirect()->route('admin.productos')->with('success', 'Producto eliminado correctamente.');
    }

    // Reportes
    public function reportes()
    {
        $ventas = Orden::selectRaw('DATE(created_at) as fecha, SUM(total) as total_vendido')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $productosMasVendidos = DetalleOrden::join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->selectRaw('productos.nombre, SUM(detalle_ordenes.cantidad) as cantidad_vendida, SUM(detalle_ordenes.cantidad * productos.precio) as total')
            ->groupBy('productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit(5)
            ->get();

        $bajoStock = Producto::where('stock', '<', 10)->get();
        $valorInventario = Producto::sum(DB::raw('stock * precio'));
        $gananciaTotal = Orden::where('estado', 'entregado')->sum('total') ?? 0; // Versión simplificada

        return view('admin.informes', compact(
            'ventas',
            'productosMasVendidos',
            'bajoStock',
            'valorInventario',
            'gananciaTotal'
        ));
    }

    // Método para actualizar la orden de compra desde la vista
    public function ordenCompraUpdate(Request $request, OrdenCompra $orden)
    {
        // Validar los datos
        $request->validate([
            'detalles.*.precio' => 'nullable|numeric|min:0',
            'estado' => 'required|in:completado,cancelado',
        ]);

        // Solo permitir actualización si está pendiente
        if ($orden->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'No se puede modificar una orden que no está pendiente.');
        }

        // Actualizar los precios en los detalles
        $detalles = $orden->detalles;
        foreach ($request->detalles as $index => $detalleData) {
            if (isset($detalleData['precio'])) {
                $detalles[$index]['precio'] = floatval($detalleData['precio']);
            }
        }

        // Calcular el monto total
        $monto = 0;
        foreach ($detalles as $detalle) {
            if (isset($detalle['precio']) && isset($detalle['cantidad'])) {
                $monto += $detalle['precio'] * $detalle['cantidad'];
            }
        }

        // Actualizar la orden
        $orden->update([
            'detalles' => $detalles,
            'monto' => $monto,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.proveedores.ordenes.historial', $orden->proveedor)
            ->with('success', 'Orden actualizada correctamente.');
    }

    // Métodos privados de apoyo
    private function validateProveedor(Request $request, $id = null): array
    {
        return $request->validate([
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => "nullable|email|unique:proveedores,email,{$id}",
            'direccion' => 'nullable|string',
            'productos_suministrados' => 'nullable|array',
            'condiciones_pago' => 'nullable|string',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
        ]);
    }

    private function validateOrdenCompra(Request $request): array
    {
        return $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'detalles' => 'required|array',
            'detalles.*.producto' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.descripcion' => 'nullable|string|max:500',
        ]);
    }

    private function updateProductStock(array $detalles): void
    {
        foreach ($detalles as $detalle) {
            $producto = Producto::firstOrCreate(
                ['nombre' => $detalle['producto']],
                ['precio' => 0, 'precio_compra' => 0, 'stock' => 0, 'descripcion' => $detalle['descripcion'] ?? '']
            );
            $producto->update([
                'stock' => $producto->stock + $detalle['cantidad'],
            ]);
        }
    }

    private function updateProductStockAndPrice(array $detalles): void
    {
        foreach ($detalles as $detalle) {
            $producto = Producto::firstOrCreate(
                ['nombre' => $detalle['producto']],
                ['precio' => 0, 'precio_compra' => 0, 'stock' => 0, 'descripcion' => $detalle['descripcion'] ?? '']
            );
            $producto->update([
                'stock' => $producto->stock + $detalle['cantidad'],
                'precio_compra' => $detalle['precio'], // Precio del proveedor
            ]);
        }
    }

    private function sendOrdenCompraNotification(OrdenCompra $ordenCompra, Proveedor $proveedor)
    {
        try {
            $proveedor->notify(new OrdenCompraNotification($ordenCompra, $proveedor));
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra registrada y notificación enviada al proveedor.');
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage());
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('warning', 'Orden registrada, pero hubo un problema al enviar el correo: ' . $e->getMessage());
        }
    }

    private function calculateTotalProfit(): float
    {
        return Orden::join('detalle_ordenes', 'ordenes.id', '=', 'detalle_ordenes.orden_id')
            ->join('productos', 'detalle_ordenes.producto_id', '=', 'productos.id')
            ->where('ordenes.estado', 'entregado')
            ->sum(DB::raw('detalle_ordenes.cantidad * (productos.precio - productos.precio_compra)')) ?? 0;
    }
}