<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{OrdenCompra, Proveedor, Producto, Categoria};
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\{Log, DB, Storage};

class ProveedorController extends Controller
{
    /**
     * Display a listing of the providers.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('admin.proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new provider.
     */
    public function create()
    {
        $categorias = Categoria::all(); // Cargar todas las categorías
        return view('admin.proveedores.create', compact('categorias'));
    }

    /**
     * Store a newly created provider in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateProveedor($request);

        // Manejar nueva categoría
        if ($request->categoria_id === 'new' && $request->new_category_name) {
            $categoria = Categoria::create(['nombre' => $request->new_category_name]);
            $data['categoria_id'] = $categoria->id;
        }

        Proveedor::create($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado correctamente.');
    }

    /**
     * Show the form for editing the specified provider.
     */
    public function edit(Proveedor $proveedor)
    {
        $categorias = Categoria::all(); // Cargar categorías para el formulario de edición
        return view('admin.proveedores.edit', compact('proveedor', 'categorias'));
    }

    /**
     * Update the specified provider in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $this->validateProveedor($request, $proveedor->id);

        // Manejar nueva categoría
        if ($request->categoria_id === 'new' && $request->new_category_name) {
            $categoria = Categoria::create(['nombre' => $request->new_category_name]);
            $data['categoria_id'] = $categoria->id;
        }

        $proveedor->update($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified provider from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->ordenesCompra()->exists()) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene órdenes de compra asociadas.');
        }
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }

    /**
     * Show the form for creating a new purchase order for the specified provider.
     */
    public function ordenCompraCreate(Proveedor $proveedor)
    {
        return view('admin.proveedores.orden_create', compact('proveedor'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function ordenCompraStore(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,entregado',
            'detalles' => 'required|array',
            'detalles.*.producto' => 'required|string',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0', // Added precio_compra
            'detalles.*.precio_venta' => 'required|numeric|min:0',  // Added precio_venta
            'detalles.*.descripcion' => 'nullable|string',
        ]);

        $orden = OrdenCompra::create([
            'proveedor_id' => $proveedor->id,
            'fecha' => $request->fecha,
            'monto' => 0,
            'estado' => $request->estado,
            'detalles' => $request->detalles,
        ]);

        if ($request->estado === 'entregado') {
            foreach ($request->detalles as $detalle) {
                $producto = Producto::firstOrCreate(
                    ['nombre' => $detalle['producto']],
                    ['stock' => 0, 'precio' => $detalle['precio_venta'], 'precio_compra' => $detalle['precio_compra'], 'categoria_id' => 1] // Default categoria_id
                );

                $producto->increment('stock', $detalle['cantidad']);
                $producto->save();

                session()->flash('alerta_productos.' . $producto->id, [
                    'mensaje' => "El producto '{$producto->nombre}' necesita actualización",
                    'url' => route('productos.edit', $producto)
                ]);
            }
        }

        if ($proveedor->email && $proveedor->recibir_notificaciones) {
            try {
                $proveedor->notify(new OrdenCompraNotification($orden, $proveedor));
                return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                    ->with('success', 'Orden de compra registrada y notificación enviada al proveedor.');
            } catch (\Exception $e) {
                Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage());
                return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                    ->with('warning', 'Orden registrada, pero hubo un problema al enviar el correo.');
            }
        }

        return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
            ->with('success', 'Orden de compra guardada correctamente');
    }

    /**
     * Display the specified purchase order.
     */
    public function ordenCompraShow(Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $productos = Producto::all();
        $categorias = Categoria::all(); // Cargar categorías para el modal
        return view('admin.proveedores.orden_show', compact('proveedor', 'orden', 'productos', 'categorias'));
    }

    /**
     * Display the purchase history for the specified provider.
     */
    public function historialCompras(Proveedor $proveedor, Request $request)
    {
        $estado = $request->query('estado');

        $ordenes = $proveedor->ordenesCompra()
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('admin.proveedores.historial', compact('proveedor', 'ordenes'));
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function ordenCompraDestroy(Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $orden->delete();

        return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
            ->with('success', 'Orden de compra eliminada correctamente.');
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function ordenCompraUpdate(Request $request, Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $data = $request->validate([
            'estado' => 'required|in:entregado,completado,cancelado',
            'detalles' => 'required|array',
            'detalles.*.producto' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0', // Added precio_compra
            'detalles.*.precio_venta' => 'required|numeric|min:0',  // Added precio_venta
            'detalles.*.descripcion' => 'nullable|string|max:500',
        ]);

        $orden->estado = $data['estado'];
        $detalles = $orden->detalles;
        foreach ($data['detalles'] as $index => $detalleInput) {
            if (isset($detalles[$index])) {
                $detalles[$index]['producto'] = $detalleInput['producto'];
                $detalles[$index]['cantidad'] = $detalleInput['cantidad'];
                $detalles[$index]['precio_compra'] = $detalleInput['precio_compra'];
                $detalles[$index]['precio_venta'] = $detalleInput['precio_venta'];
                $detalles[$index]['descripcion'] = $detalleInput['descripcion'] ?? '';
            }
        }
        $orden->detalles = $detalles;

        $monto = 0;
        foreach ($detalles as $detalle) {
            if (isset($detalle['precio_compra']) && isset($detalle['cantidad'])) {
                $monto += $detalle['precio_compra'] * $detalle['cantidad'];
            }
        }
        $orden->monto = $monto;

        if ($data['estado'] === 'entregado') {
            foreach ($detalles as $detalle) {
                $producto = Producto::firstOrCreate(
                    ['nombre' => $detalle['producto']],
                    ['stock' => 0, 'precio' => $detalle['precio_venta'], 'precio_compra' => $detalle['precio_compra'], 'categoria_id' => 1] // Default categoria_id
                );

                $producto->increment('stock', $detalle['cantidad']);
                $producto->save();

                session()->flash('alerta_productos.' . $producto->id, [
                    'mensaje' => "El producto '{$producto->nombre}' necesita actualización",
                    'url' => route('productos.edit', $producto)
                ]);
            }
        }

        $orden->save();

        return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
            ->with('success', 'Orden de compra actualizada correctamente.');
    }

    /**
     * Show the form for configuring notification email.
     */
    public function configurarCorreo()
    {
        $correo_notificaciones = env('MAIL_FROM_ADDRESS', 'default@example.com');
        return view('admin.proveedores.configurar-correo', compact('correo_notificaciones'));
    }

    /**
     * Store the notification email configuration.
     */
    public function guardarCorreoNotificaciones(Request $request)
    {
        $request->validate([
            'correo_notificaciones' => 'required|email',
        ]);

        $nuevoCorreo = $request->input('correo_notificaciones');
        return redirect()->route('admin.proveedores.configurar-correo')
            ->with('success', 'Correo de notificaciones actualizado a: ' . $nuevoCorreo);
    }

    /**
     * Validate provider data.
     */
    private function validateProveedor(Request $request, $id = null): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => "nullable|email|unique:proveedores,email,{$id}",
            'direccion' => 'nullable|string',
            'productos_suministrados' => 'nullable|string',
            'condiciones_pago' => 'nullable|string',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
            'categoria_id' => 'nullable|exists:categorias,id',
            'new_category_name' => 'nullable|string|max:255|required_if:categoria_id,new',
        ];

        $data = $request->validate($rules);

        // Convert productos_suministrados string to array
        if ($data['productos_suministrados']) {
            $data['productos_suministrados'] = array_map('trim', explode(',', $data['productos_suministrados']));
        } else {
            $data['productos_suministrados'] = [];
        }

        // Ensure recibir_notificaciones is boolean
        $data['recibir_notificaciones'] = $request->has('recibir_notificaciones');

        // Set default estado if not provided
        $data['estado'] = $data['estado'] ?? 'activo';

        return $data;
    }

    /**
     * Validate purchase order data.
     */
    private function validateOrdenCompra(Request $request): array
    {
        return $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0', // Added precio_compra
            'detalles.*.precio_venta' => 'required|numeric|min:0',  // Added precio_venta
            'detalles.*.descripcion' => 'nullable|string|max:500',
        ]);
    }

    /**
     * Update product stock based on order details.
     */
    private function updateProductStock(array $detalles): void
    {
        foreach ($detalles as $detalle) {
            $producto = Producto::firstOrCreate(
                ['nombre' => $detalle['producto']],
                ['stock' => 0, 'precio' => $detalle['precio_venta'], 'precio_compra' => $detalle['precio_compra'], 'categoria_id' => 1] // Default categoria_id
            );
            $producto->increment('stock', $detalle['cantidad']);
            $producto->save();
        }
    }

    /**
     * Send purchase order notification to the provider.
     */
    private function sendOrdenCompraNotification(OrdenCompra $ordenCompra, Proveedor $proveedor)
    {
        try {
            $proveedor->notify(new OrdenCompraNotification($ordenCompra, $proveedor));
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra registrada y notificación enviada al proveedor.');
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage());
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('warning', 'Orden registrada, pero un problema al enviar el correo: ' . $e->getMessage());
        }
    }
}


























<!-- <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{OrdenCompra, Proveedor, Producto, Categoria};
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\{Log, DB, Mail, Artisan, Config, Schema};

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $productos = Producto::all();
        return view('admin.proveedores.create', compact('categorias', 'productos'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\-\(\) ]*$/',
            'email' => 'required|email|max:255|unique:proveedores,email',
            'direccion' => 'nullable|string|max:500',
            'productos_suministrados' => 'nullable|array',
            'condiciones_pago' => 'nullable|string|max:1000',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
            'estado' => 'required|in:activo,inactivo',
            'new_category_name' => 'nullable|string|max:255|required_if:categoria_id,new',
        ];

        $messages = [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'nombre.string' => 'El nombre del proveedor debe ser un texto.',
            'nombre.max' => 'El nombre del proveedor no puede exceder 255 caracteres.',
            'telefono.string' => 'El teléfono debe ser un texto.',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'telefono.regex' => 'El teléfono debe contener solo números, signos (+, -, (), y espacios).',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede exceder 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'direccion.string' => 'La dirección debe ser un texto.',
            'direccion.max' => 'La dirección no puede exceder 500 caracteres.',
            'productos_suministrados.array' => 'Los productos suministrados deben ser un arreglo.',
            'condiciones_pago.string' => 'Las condiciones de pago deben ser un texto.',
            'condiciones_pago.max' => 'Las condiciones de pago no pueden exceder 1000 caracteres.',
            'fecha_vencimiento_contrato.date' => 'La fecha de vencimiento del contrato debe ser una fecha válida.',
            'recibir_notificaciones.boolean' => 'La opción de recibir notificaciones debe ser un valor booleano.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser activo o inactivo.',
            'new_category_name.string' => 'El nombre de la nueva categoría debe ser un texto.',
            'new_category_name.max' => 'El nombre de la nueva categoría no puede exceder 255 caracteres.',
            'new_category_name.required_if' => 'El nombre de la nueva categoría es obligatorio cuando se selecciona "Crear nueva categoría".',
        ];

        try {
            $data = $request->validateWithBag('default', $rules, $messages);
            $data['nombre'] = ucfirst(trim($data['nombre']));

            $categoria_id = $request->input('categoria_id');
            if ($categoria_id === 'new' && $request->filled('new_category_name')) {
                $categoria = Categoria::create([
                    'nombre' => ucfirst(trim($request->new_category_name)),
                    'estado' => 'activo'
                ]);
                $categoria_id = $categoria->id;
            } else {
                $categoria_id = $request->filled('categoria_id') && $request->categoria_id !== 'new' ? $request->categoria_id : null;
            }

            $productos_suministrados = $request->input('productos_suministrados', []);
            $data['categoria_id'] = $categoria_id;
            $data['recibir_notificaciones'] = $request->has('recibir_notificaciones');
            $data['estado'] = $data['estado'] ?? 'activo';

            DB::beginTransaction();
            $proveedor = Proveedor::create($data);

            if (!empty($productos_suministrados) && Schema::hasTable('proveedor_producto')) {
                try {
                    $proveedor->productos()->sync($productos_suministrados);
                } catch (\Exception $e) {
                    Log::warning('Failed to sync products for provider: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
                }
            } elseif (!empty($productos_suministrados)) {
                Log::warning('Pivot table proveedor_producto does not exist. Skipping product sync for provider ID: ' . $proveedor->id);
            }

            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error while registering provider: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar proveedor: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al registrar el proveedor: ' . $e->getMessage());
        }
    }

    public function edit(Proveedor $proveedor)
    {
        $categorias = Categoria::all();
        $productos = Producto::all();
        $proveedor_productos = Schema::hasTable('proveedor_producto') ? $proveedor->productos()->pluck('productos.id')->toArray() : [];
        return view('admin.proveedores.edit', compact('proveedor', 'categorias', 'productos', 'proveedor_productos'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\-\(\) ]*$/',
            'email' => "required|email|max:255|unique:proveedores,email,{$proveedor->id}",
            'direccion' => 'nullable|string|max:500',
            'productos_suministrados' => 'nullable|array',
            'condiciones_pago' => 'nullable|string|max:1000',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
            'estado' => 'required|in:activo,inactivo',
            'new_category_name' => 'nullable|string|max:255|required_if:categoria_id,new',
        ];

        $messages = [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'new_category_name.required_if' => 'El nombre de la nueva categoría es obligatorio cuando se selecciona "Crear nueva categoría".',
        ];

        try {
            $data = $request->validateWithBag('default', $rules, $messages);
            $data['nombre'] = ucfirst(trim($data['nombre']));

            $categoria_id = $request->input('categoria_id');
            if ($categoria_id === 'new' && $request->filled('new_category_name')) {
                $categoria = Categoria::create([
                    'nombre' => ucfirst(trim($request->new_category_name)),
                    'estado' => 'activo'
                ]);
                $categoria_id = $categoria->id;
            } else {
                $categoria_id = $request->filled('categoria_id') && $request->categoria_id !== 'new' ? $request->categoria_id : null;
            }

            $productos_suministrados = $request->input('productos_suministrados', []);
            $data['categoria_id'] = $categoria_id;
            $data['recibir_notificaciones'] = $request->has('recibir_notificaciones');
            $data['estado'] = $data['estado'] ?? 'activo';

            DB::beginTransaction();
            $proveedor->update($data);

            if (!empty($productos_suministrados) && Schema::hasTable('proveedor_producto')) {
                try {
                    $proveedor->productos()->sync($productos_suministrados);
                } catch (\Exception $e) {
                    Log::warning('Failed to sync products for provider: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
                }
            } elseif (!empty($productos_suministrados)) {
                Log::warning('Pivot table proveedor_producto does not exist. Skipping product sync for provider ID: ' . $proveedor->id);
            } else {
                if (Schema::hasTable('proveedor_producto')) {
                    $proveedor->productos()->detach();
                }
            }

            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error while updating provider: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar proveedor: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->ordenesCompra()->exists()) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene órdenes de compra asociadas.');
        }
        try {
            DB::beginTransaction();
            if (Schema::hasTable('proveedor_producto')) {
                $proveedor->productos()->detach();
            }
            $proveedor->delete();
            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar proveedor: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->route('proveedores.index')->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }

    public function ordenCompraCreate(Proveedor $proveedor)
    {
        $categorias = Categoria::all();
        $productos = Producto::all();
        return view('admin.proveedores.orden_create', compact('proveedor', 'categorias', 'productos'));
    }

    public function ordenCompraStore(Request $request, Proveedor $proveedor)
    {
        $data = $this->validateOrdenCompra($request);
        foreach ($data['detalles'] as &$detalle) {
            if (isset($detalle['categoria_id']) && $detalle['categoria_id'] === 'new' && !empty($detalle['new_category_name'])) {
                $categoria = Categoria::create([
                    'nombre' => ucfirst(trim($detalle['new_category_name'])),
                    'estado' => 'activo'
                ]);
                $detalle['categoria_id'] = $categoria->id;
            }
            $detalle['producto'] = $this->getProductNames($detalle['producto_ids'] ?? []);
        }

        try {
            DB::beginTransaction();
            $orden = OrdenCompra::create([
                'proveedor_id' => $proveedor->id,
                'fecha' => $data['fecha'],
                'monto' => 0,
                'estado' => $data['estado'],
                'detalles' => $data['detalles'],
            ]);

            $monto = 0;
            foreach ($data['detalles'] as $detalle) {
                if (isset($detalle['precio_compra']) && isset($detalle['cantidad'])) {
                    $monto += $detalle['precio_compra'] * $detalle['cantidad'];
                }
            }
            $orden->monto = $monto;
            $orden->save();

            if ($data['estado'] === 'entregado') {
                $this->updateProductStock($data['detalles']);
            }

            DB::commit();

            $emailStatus = '';
            if ($proveedor->email) {
                try {
                    $proveedor->notify(new OrdenCompraNotification($orden, $proveedor));
                    $emailStatus = ' y notificación enviada al proveedor.';
                } catch (\Exception $e) {
                    Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
                    $emailStatus = ', pero hubo un problema al enviar la notificación: ' . $e->getMessage();
                }
            } else {
                $emailStatus = ', pero el proveedor no tiene correo registrado.';
            }

            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra guardada correctamente' . $emailStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar orden de compra: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al registrar la orden de compra: ' . $e->getMessage());
        }
    }

    public function storeProduct(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $producto = Producto::firstOrCreate(
                ['nombre' => ucfirst(trim($request->nombre))],
                ['stock' => 0, 'precio' => 0, 'precio_compra' => 0, 'estado' => 'activo']
            );

            return response()->json(['success' => true, 'producto' => $producto]);
        } catch (\Exception $e) {
            Log::error('Error al guardar producto: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function ordenCompraShow(Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $productos = Producto::all();
        $categorias = Categoria::all();
        return view('admin.proveedores.orden_show', compact('proveedor', 'orden', 'productos', 'categorias'));
    }

    public function historialCompras(Proveedor $proveedor, Request $request)
    {
        $estado = $request->query('estado');

        $ordenes = $proveedor->ordenesCompra()
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return view('admin.proveedores.historial', compact('proveedor', 'ordenes'));
    }

    public function ordenCompraDestroy(Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        try {
            DB::beginTransaction();
            $orden->delete();
            DB::commit();
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar orden de compra: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al eliminar la orden de compra: ' . $e->getMessage());
        }
    }

    public function ordenCompraUpdate(Request $request, Proveedor $proveedor, OrdenCompra $orden)
    {
        if ($orden->proveedor_id !== $proveedor->id) {
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $data = $this->validateOrdenCompra($request);
        foreach ($data['detalles'] as &$detalle) {
            if (isset($detalle['categoria_id']) && $detalle['categoria_id'] === 'new' && !empty($detalle['new_category_name'])) {
                $categoria = Categoria::create([
                    'nombre' => ucfirst(trim($detalle['new_category_name'])),
                    'estado' => 'activo'
                ]);
                $detalle['categoria_id'] = $categoria->id;
            }
            $detalle['producto'] = $this->getProductNames($detalle['producto_ids'] ?? []);
        }

        try {
            DB::beginTransaction();
            $orden->estado = $data['estado'];
            $orden->detalles = $data['detalles'];

            $monto = 0;
            foreach ($data['detalles'] as $detalle) {
                if (isset($detalle['precio_compra']) && isset($detalle['cantidad'])) {
                    $monto += $detalle['precio_compra'] * $detalle['cantidad'];
                }
            }
            $orden->monto = $monto;

            if ($data['estado'] === 'entregado') {
                $this->updateProductStock($data['detalles']);
            }

            $orden->save();
            DB::commit();

            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar orden de compra: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al actualizar la orden de compra: ' . $e->getMessage());
        }
    }

    public function configurarCorreo()
    {
        Artisan::call('config:clear');
        $correo_notificaciones = config('mail.from.address', 'default@example.com');
        $password_configurada = config('mail.mailers.smtp.password') ? true : false;
        $smtpTestResult = $this->testSmtpConnection();
        return view('admin.proveedores.configurar-correo', compact('correo_notificaciones', 'password_configurada', 'smtpTestResult'));
    }

    public function guardarCorreoNotificaciones(Request $request)
    {
        $request->validate([
            'correo_notificaciones' => 'required|email',
            'password_notificaciones' => 'required|string|min:6',
        ], [
            'correo_notificaciones.required' => 'El correo de notificaciones es obligatorio.',
            'correo_notificaciones.email' => 'El correo de notificaciones debe ser una dirección válida.',
            'password_notificaciones.required' => 'La contraseña es obligatoria.',
            'password_notificaciones.string' => 'La contraseña debe ser un texto.',
            'password_notificaciones.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $nuevoCorreo = $request->input('correo_notificaciones');
        $nuevaPassword = $request->input('password_notificaciones');

        try {
            $this->updateEnvFile([
                'MAIL_FROM_ADDRESS' => $nuevoCorreo,
                'MAIL_USERNAME' => $nuevoCorreo,
                'MAIL_PASSWORD' => $nuevaPassword,
            ]);

            Artisan::call('config:clear');
            $smtpTestResult = $this->testSmtpConnection();

            if ($smtpTestResult['success']) {
                return redirect()->route('admin.proveedores.configurar-correo')
                    ->with('success', 'Correo de notificaciones actualizado a: ' . $nuevoCorreo . '. La conexión SMTP es exitosa.');
            } else {
                return redirect()->route('admin.proveedores.configurar-correo')
                    ->with('error', 'Correo actualizado, pero la conexión SMTP falló: ' . $smtpTestResult['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error al guardar la configuración de correo: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.proveedores.configurar-correo')
                ->with('error', 'Error al actualizar el correo: ' . $e->getMessage());
        }
    }

    private function testSmtpConnection()
    {
        try {
            Mail::raw('Este es un correo de prueba para verificar la conexión SMTP.', function ($message) {
                $message->to(config('mail.from.address', 'default@example.com'))
                        ->subject('Prueba de Conexión SMTP')
                        ->from(config('mail.from.address', 'default@example.com'), config('mail.from.name', 'Tienda D\'jenny'));
            });
            return ['success' => true, 'message' => 'Conexión SMTP exitosa.'];
        } catch (\Exception $e) {
            Log::error('Error en la conexión SMTP: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            throw new \Exception('El archivo .env no existe en la ruta: ' . $envPath);
        }
        if (!is_writable($envPath)) {
            throw new \Exception('El archivo .env no es escribible. Verifica los permisos del archivo en: ' . $envPath);
        }
        $envContent = file_get_contents($envPath);
        $lines = explode("\n", $envContent);
        foreach ($data as $key => $value) {
            $found = false;
            foreach ($lines as &$line) {
                if (strpos($line, $key . '=') === 0) {
                    $line = $key . '="' . addslashes($value) . '"';
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $lines[] = $key . '="' . addslashes($value) . '"';
            }
        }
        if (!file_put_contents($envPath, implode("\n", $lines))) {
            throw new \Exception('No se pudo escribir en el archivo .env en: ' . $envPath);
        }
    }

    private function validateOrdenCompra(Request $request): array
    {
        return $request->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_ids' => 'required|array|min:1',
            'detalles.*.producto_ids.*' => 'required|exists:productos,id',
            'detalles.*.new_product_name' => 'nullable|string|max:255',
            'detalles.*.categoria_id' => 'nullable|exists:categorias,id',
            'detalles.*.new_category_name' => 'nullable|string|max:255|required_if:detalles.*.categoria_id,new',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0',
            'detalles.*.precio_venta' => 'required|numeric|min:0',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser pendiente, procesando, enviado, entregado o cancelado.',
            'detalles.required' => 'Los detalles de la orden son obligatorios.',
            'detalles.array' => 'Los detalles deben ser un arreglo.',
            'detalles.min' => 'Debe haber al menos un detalle en la orden.',
            'detalles.*.producto_ids.required' => 'Debe seleccionar al menos un producto.',
            'detalles.*.producto_ids.array' => 'Los productos deben ser un arreglo.',
            'detalles.*.producto_ids.*.exists' => 'El producto seleccionado no es válido.',
            'detalles.*.new_product_name.string' => 'El nombre del nuevo producto debe ser un texto.',
            'detalles.*.new_product_name.max' => 'El nombre del nuevo producto no puede exceder 255 caracteres.',
            'detalles.*.categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'detalles.*.new_category_name.required_if' => 'El nombre de la nueva categoría es obligatorio cuando se selecciona "Crear nueva categoría".',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'detalles.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'detalles.*.precio_compra.required' => 'El precio de compra es obligatorio.',
            'detalles.*.precio_compra.numeric' => 'El precio de compra debe ser un número.',
            'detalles.*.precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'detalles.*.precio_venta.required' => 'El precio de venta es obligatorio.',
            'detalles.*.precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'detalles.*.precio_venta.min' => 'El precio de venta no puede ser negativo.',
        ]);
    }

    private function getProductNames(array $producto_ids): string
    {
        if (empty($producto_ids)) {
            return '';
        }
        $producto_ids = array_filter($producto_ids, fn($id) => is_numeric($id));
        if (empty($producto_ids)) {
            return '';
        }
        $productos = Producto::whereIn('id', $producto_ids)->pluck('nombre')->toArray();
        return implode(', ', $productos);
    }

    private function updateProductStock(array $detalles): void
    {
        foreach ($detalles as $detalle) {
            foreach ($detalle['producto_ids'] as $producto_id) {
                if (is_numeric($producto_id)) {
                    $producto = Producto::find($producto_id);
                    if ($producto) {
                        $producto->increment('stock', $detalle['cantidad']);
                        $producto->precio = $detalle['precio_venta'];
                        $producto->precio_compra = $detalle['precio_compra'];
                        $producto->categoria_id = $detalle['categoria_id'] ?? null;
                        $producto->save();
                    }
                }
            }
        }
    }

    private function sendOrdenCompraNotification(OrdenCompra $ordenCompra, Proveedor $proveedor)
    {
        try {
            $proveedor->notify(new OrdenCompraNotification($ordenCompra, $proveedor));
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('success', 'Orden de compra registrada y notificación enviada al proveedor.');
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.proveedores.ordenes.historial', $proveedor)
                ->with('warning', 'Orden registrada, pero hubo un problema al enviar el correo: ' . $e->getMessage());
        }
    }
} -->