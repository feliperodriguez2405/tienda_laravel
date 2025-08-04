<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{OrdenCompra, Proveedor, Producto, Categoria};
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\{DB, Schema, Log, Mail, Artisan};
use Illuminate\Validation\ValidationException;

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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:proveedores,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'condiciones_pago' => 'nullable|string|max:500',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
            'estado' => 'required|in:activo,inactivo',
            'categoria_id' => 'required|in:new,' . implode(',', Categoria::pluck('id')->toArray()),
            'new_category_name' => 'required_if:categoria_id,new|string|max:255|unique:categorias,nombre',
            'productos_suministrados' => 'nullable|array',
            'productos_suministrados.*' => 'exists:productos,id',
        ], [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'categoria_id.required' => 'Debe seleccionar una categoría o crear una nueva.',
            'new_category_name.required_if' => 'El nombre de la nueva categoría es obligatorio.',
            'new_category_name.unique' => 'El nombre de la categoría ya existe.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        $data = $request->only([
            'nombre', 'telefono', 'email', 'direccion', 'condiciones_pago',
            'fecha_vencimiento_contrato', 'recibir_notificaciones', 'estado', 'categoria_id'
        ]);
        $data['nombre'] = ucfirst(trim($data['nombre'] ?? 'Proveedor Anónimo'));
        $data['recibir_notificaciones'] = $request->has('recibir_notificaciones');
        $data['estado'] = $data['estado'] ?? 'activo';
        $productos_suministrados = $request->input('productos_suministrados', []);

        DB::beginTransaction();
        try {
            if ($request->input('categoria_id') === 'new') {
                $categoria = Categoria::create([
                    'nombre' => ucfirst(trim($request->input('new_category_name') ?? 'Categoría Genérica')),
                    'estado' => 'activo'
                ]);
                $data['categoria_id'] = $categoria->id;
            } else {
                $data['categoria_id'] = $request->filled('categoria_id') ? $request->input('categoria_id') : null;
            }

            $proveedor = Proveedor::create($data);

            if (!empty($productos_suministrados) && Schema::hasTable('proveedor_producto')) {
                $proveedor->productos()->sync($productos_suministrados);
            }

            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado correctamente.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in store: ' . $e->getMessage(), ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store: ' . $e->getMessage(), ['request_data' => $request->all(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error al registrar el proveedor: ' . $e->getMessage())->withInput();
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
        $data = $request->only([
            'nombre', 'telefono', 'email', 'direccion', 'condiciones_pago',
            'fecha_vencimiento_contrato', 'recibir_notificaciones', 'estado', 'categoria_id'
        ]);
        $data['nombre'] = ucfirst(trim($data['nombre'] ?? 'Proveedor Anónimo'));
        $data['recibir_notificaciones'] = $request->has('recibir_notificaciones');
        $data['estado'] = $data['estado'] ?? 'activo';
        $productos_suministrados = $request->input('productos_suministrados', []);

        if ($request->input('categoria_id') === 'new' && $request->filled('new_category_name')) {
            $categoria = Categoria::create([
                'nombre' => ucfirst(trim($request->input('new_category_name') ?? 'Categoría Genérica')),
                'estado' => 'activo'
            ]);
            $data['categoria_id'] = $categoria->id;
        } else {
            $data['categoria_id'] = $request->filled('categoria_id') && $request->input('categoria_id') !== 'new' ? $request->input('categoria_id') : null;
        }

        DB::beginTransaction();
        try {
            $proveedor->update($data);

            if (Schema::hasTable('proveedor_producto')) {
                $proveedor->productos()->sync($productos_suministrados);
            }

            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in update: ' . $e->getMessage(), ['proveedor_id' => $proveedor->id, 'request_data' => $request->all()]);
            return redirect()->route('proveedores.index')->with('error', 'Error al actualizar el proveedor.');
        }
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->ordenesCompra()->exists()) {
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene órdenes de compra asociadas.');
        }

        DB::beginTransaction();
        try {
            if (Schema::hasTable('proveedor_producto')) {
                $proveedor->productos()->detach();
            }
            $proveedor->delete();
            DB::commit();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy: ' . $e->getMessage(), ['proveedor_id' => $proveedor->id]);
            return redirect()->route('proveedores.index')->with('error', 'Error al eliminar el proveedor.');
        }
    }

    public function ordenCompraCreate(Proveedor $proveedor)
    {
        $categorias = Categoria::all();
        $productos = Producto::with('categoria')->get();
        return view('admin.proveedores.orden_create', compact('proveedor', 'categorias', 'productos'));
    }

    public function ordenCompraStore(Request $request, Proveedor $proveedor)
    {
        if (!$proveedor->exists || !$proveedor->id) {
            Log::error('Proveedor inválido', ['proveedor_id' => $proveedor->id ?? null]);
            return redirect()->route('proveedores.index')->with('error', 'Proveedor no encontrado.');
        }

        $request->validate([
            'fecha' => 'nullable|date',
            'detalles' => 'required|array',
            'detalles.*.producto_ids' => 'required|array',
            'detalles.*.producto_ids.*' => 'nullable|numeric|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0',
            'detalles.*.precio_venta' => 'required|numeric|min:0',
            'detalles.*.descripcion' => 'nullable|string|max:500',
            'detalles.*.categoria_id' => 'nullable|numeric|exists:categorias,id',
            'detalles.*.new_product_name' => 'nullable|string|max:255',
            'detalles.*.new_category_name' => 'nullable|string|max:255',
            'estado' => 'required|in:procesando,entregado,cancelado',
        ]);

        $data = $request->only(['fecha', 'detalles', 'estado']);
        $data['estado'] = $data['estado'] ?? 'procesando';

        if (empty($data['detalles']) || !is_array($data['detalles'])) {
            $data['detalles'] = [[
                'producto_ids' => [],
                'producto' => 'Producto genérico',
                'cantidad' => 1,
                'categoria_id' => $proveedor->categoria_id ?? null,
                'precio_compra' => 0,
                'precio_venta' => 0,
            ]];
            Log::warning('No detalles provided, using default', ['proveedor_id' => $proveedor->id]);
        }

        DB::beginTransaction();
        try {
            $newProducts = [];
            $monto = 0;

            foreach ($data['detalles'] as &$detalle) {
                $detalle['producto_ids'] = array_filter($detalle['producto_ids'] ?? [], fn($id) => is_numeric($id));
                $detalle['cantidad'] = $detalle['cantidad'] ?? 1;
                $detalle['categoria_id'] = $detalle['categoria_id'] ?? $proveedor->categoria_id ?? null;
                $detalle['descripcion'] = isset($detalle['descripcion']) ? ucfirst($this->correctSpelling(trim($detalle['descripcion']))) : '';
                $detalle['porcentaje_ganancia'] = $detalle['porcentaje_ganancia'] ?? null;
                $detalle['precio_compra'] = $detalle['precio_compra'] ?? 0;
                $detalle['precio_venta'] = $detalle['precio_venta'] ?? 0;

                if (isset($detalle['new_product_name']) && !empty($detalle['new_product_name'])) {
                    $correctedName = $this->correctSpelling(ucfirst(trim($detalle['new_product_name'] ?? 'Producto Genérico')));
                    $categoriaId = $detalle['categoria_id'] ?? $proveedor->categoria_id ?? null;
                    if ($detalle['categoria_id'] === 'new' && !empty($detalle['new_category_name'])) {
                        $categoria = Categoria::create([
                            'nombre' => ucfirst(trim($detalle['new_category_name'] ?? 'Categoría Genérica')),
                            'estado' => 'activo'
                        ]);
                        $categoriaId = $categoria->id;
                    }

                    $producto = Producto::firstOrCreate(
                        ['nombre' => $correctedName],
                        [
                            'stock' => 0,
                            'precio' => $detalle['precio_venta'],
                            'precio_compra' => $detalle['precio_compra'],
                            'estado' => 'inactivo',
                            'categoria_id' => $categoriaId
                        ]
                    );
                    $detalle['producto_ids'] = [$producto->id];
                    $detalle['producto'] = $producto->nombre;
                    $newProducts[] = $producto;
                } else {
                    $detalle['producto'] = !empty($detalle['producto_ids']) ? $this->getProductNames($detalle['producto_ids']) : ($detalle['producto'] ?? 'Producto genérico');
                    if (!empty($detalle['producto_ids']) && is_numeric($detalle['producto_ids'][0])) {
                        $producto = Producto::find($detalle['producto_ids'][0]);
                        if ($producto) {
                            $detalle['categoria_id'] = $producto->categoria_id;
                            $producto->precio_compra = $detalle['precio_compra'];
                            $producto->precio = $detalle['precio_venta'];
                            $producto->save();
                            Log::info('Producto actualizado en ordenCompraStore', [
                                'producto_id' => $producto->id,
                                'precio_compra' => $detalle['precio_compra'],
                                'precio_venta' => $detalle['precio_venta']
                            ]);
                        }
                    }
                }
                $monto += $detalle['precio_compra'] * $detalle['cantidad'];
                unset($detalle['new_product_name'], $detalle['new_category_name']);
            }

            $orderData = [
                'proveedor_id' => $proveedor->id,
                'fecha' => $data['fecha'] ? \Carbon\Carbon::parse($data['fecha'])->toDateTimeString() : now(),
                'monto' => $monto,
                'estado' => $data['estado'],
                'detalles' => $data['detalles'],
            ];

            Log::info('Attempting to create OrdenCompra', ['order_data' => $orderData]);

            $orden = OrdenCompra::create($orderData);

            if (!$orden->exists || !$orden->id) {
                throw new \Exception('Failed to create OrdenCompra: No ID assigned');
            }

            if ($data['estado'] === 'entregado') {
                $this->updateProductStock($data['detalles']);
            }

            try {
                $proveedor->notify(new OrdenCompraNotification($orden, $proveedor));
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage(), [
                    'proveedor_id' => $proveedor->id,
                    'orden_id' => $orden->id
                ]);
            }

            DB::commit();

            $alerts = [];
            foreach ($newProducts as $producto) {
                $alerts['alerta_productos.' . $producto->id] = [
                    'mensaje' => "El producto '{$producto->nombre}' se creó como inactivo. Debe activarlo y editarlo en la sección de productos.",
                    'url' => route('productos.edit', $producto)
                ];
            }

            Log::info('Redirecting to historialCompras', [
                'proveedor_id' => $proveedor->id,
                'orden_id' => $orden->id
            ]);

            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('success', 'Orden de compra registrada correctamente.')
                ->with($alerts);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in ordenCompraStore: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id ?? null,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('proveedores.index')->with('error', 'Error al crear la orden de compra: ' . $e->getMessage());
        }
    }

    public function ordenCompraHistorial(Proveedor $proveedor)
    {
        try {
            $query = OrdenCompra::where('proveedor_id', $proveedor->id);

            if ($search = request('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhereHas('proveedor', function ($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%");
                      });
                });
            }

            if ($estado = request('estado')) {
                $query->where('estado', $estado);
            }

            $ordenes = $query->paginate(9);
            Log::info('Órdenes paginadas', [
                'proveedor_id' => $proveedor->id,
                'count' => count($ordenes->items()),
                'total' => $ordenes->total(),
                'per_page' => $ordenes->perPage()
            ]);

            return view('admin.proveedores.historial', compact('proveedor', 'ordenes'));
        } catch (\Exception $e) {
            Log::error('Error al obtener historial de órdenes', [
                'proveedor_id' => $proveedor->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al cargar el historial de órdenes.');
        }
    }

    public function ordenCompraShow(Proveedor $proveedor, OrdenCompra $orden)
    {
        if (!$proveedor->exists || !$orden->exists || $orden->proveedor_id !== $proveedor->id) {
            Log::error('Invalid proveedor or orden in ordenCompraShow', [
                'proveedor_id' => $proveedor->id ?? null,
                'orden_id' => $orden->id ?? null
            ]);
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $productos = Producto::all();
        $categorias = Categoria::all();
        return view('admin.proveedores.orden_show', compact('proveedor', 'orden', 'productos', 'categorias'));
    }

    public function historialCompras(Proveedor $proveedor, Request $request)
    {
        if (!$proveedor->exists) {
            Log::error('Proveedor inválido en historialCompras', ['proveedor_id' => $proveedor->id ?? null]);
            return redirect()->route('proveedores.index')->with('error', 'Proveedor no encontrado.');
        }

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
        if (!$proveedor->exists || !$orden->exists || $orden->proveedor_id !== $proveedor->id) {
            Log::error('Invalid proveedor or orden in ordenCompraDestroy', [
                'proveedor_id' => $proveedor->id ?? null,
                'orden_id' => $orden->id ?? null
            ]);
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        DB::beginTransaction();
        try {
            foreach ($orden->detalles ?? [] as $detalle) {
                if (!empty($detalle['producto_ids'])) {
                    $producto = Producto::find($detalle['producto_ids'][0]);
                    if ($producto && $producto->estado === 'inactivo' && $producto->stock === 0) {
                        $producto->delete();
                    }
                }
            }
            $orden->delete();
            DB::commit();
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('success', 'Orden de compra eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in ordenCompraDestroy: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id,
                'orden_id' => $orden->id
            ]);
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('error', 'Error al eliminar la orden de compra.');
        }
    }

    public function ordenCompraUpdate(Request $request, Proveedor $proveedor, OrdenCompra $orden)
    {
        if (!$proveedor->exists || !$orden->exists || $orden->proveedor_id !== $proveedor->id) {
            Log::error('Invalid proveedor or orden in ordenCompraUpdate', [
                'proveedor_id' => $proveedor->id ?? null,
                'orden_id' => $orden->id ?? null
            ]);
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('error', 'La orden no pertenece a este proveedor.');
        }

        $request->validate([
            'estado' => 'required|in:procesando,entregado,cancelado',
            'detalles' => 'required|array',
            'detalles.*.producto_ids' => 'required|array',
            'detalles.*.producto_ids.*' => 'nullable|numeric|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0',
            'detalles.*.precio_venta' => 'required|numeric|min:0',
            'detalles.*.descripcion' => 'nullable|string|max:500',
            'detalles.*.categoria_id' => 'nullable|numeric|exists:categorias,id',
            'detalles.*.new_product_name' => 'nullable|string|max:255',
            'detalles.*.new_category_name' => 'nullable|string|max:255',
            'detalles.*.porcentaje_ganancia' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['estado', 'detalles']);
        $monto = 0;

        if (empty($data['detalles']) || !is_array($data['detalles'])) {
            $data['detalles'] = [[
                'producto_ids' => [],
                'producto' => 'Producto genérico',
                'cantidad' => 1,
                'categoria_id' => $proveedor->categoria_id ?? null,
                'precio_compra' => 0,
                'precio_venta' => 0,
            ]];
            Log::warning('No detalles provided in ordenCompraUpdate, using default', ['proveedor_id' => $proveedor->id]);
        }

        try {
            DB::beginTransaction();
            $alerts = [];
            $newProducts = [];

            foreach ($data['detalles'] as &$detalle) {
                $detalle['producto_ids'] = array_filter($detalle['producto_ids'] ?? [], fn($id) => is_numeric($id));
                $detalle['producto'] = !empty($detalle['producto_ids']) ? $this->getProductNames($detalle['producto_ids']) : ($detalle['producto'] ?? 'Producto genérico');
                $detalle['descripcion'] = isset($detalle['descripcion']) ? ucfirst($this->correctSpelling(trim($detalle['descripcion']))) : '';
                $detalle['porcentaje_ganancia'] = $detalle['porcentaje_ganancia'] ?? null;
                $detalle['precio_compra'] = $detalle['precio_compra'] ?? 0;
                $detalle['precio_venta'] = $detalle['precio_venta'] ?? 0;

                if (isset($detalle['new_product_name']) && !empty($detalle['new_product_name'])) {
                    $correctedName = $this->correctSpelling(ucfirst(trim($detalle['new_product_name'])));
                    $categoriaId = $detalle['categoria_id'] ?? $proveedor->categoria_id ?? null;
                    if ($detalle['categoria_id'] === 'new' && !empty($detalle['new_category_name'])) {
                        $categoria = Categoria::create([
                            'nombre' => ucfirst(trim($detalle['new_category_name'] ?? 'Categoría Genérica')),
                            'estado' => 'activo'
                        ]);
                        $categoriaId = $categoria->id;
                    }

                    $producto = Producto::firstOrCreate(
                        ['nombre' => $correctedName],
                        [
                            'stock' => 0,
                            'precio' => $detalle['precio_venta'],
                            'precio_compra' => $detalle['precio_compra'],
                            'estado' => 'inactivo',
                            'categoria_id' => $categoriaId
                        ]
                    );
                    $detalle['producto_ids'] = [$producto->id];
                    $detalle['producto'] = $producto->nombre;
                    $newProducts[] = $producto;
                    Log::info('Nuevo producto creado en ordenCompraUpdate', [
                        'producto_id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'precio_compra' => $detalle['precio_compra'],
                        'precio_venta' => $detalle['precio_venta']
                    ]);
                } elseif (!empty($detalle['producto_ids'])) {
                    $producto = Producto::find($detalle['producto_ids'][0]);
                    if ($producto) {
                        $detalle['categoria_id'] = $producto->categoria_id;
                        $producto->precio_compra = $detalle['precio_compra'];
                        $producto->precio = $detalle['precio_venta'];
                        $producto->save();
                        Log::info('Producto actualizado en ordenCompraUpdate', [
                            'producto_id' => $producto->id,
                            'precio_compra' => $detalle['precio_compra'],
                            'precio_venta' => $detalle['precio_venta']
                        ]);
                    }
                }
                $monto += $detalle['precio_compra'] * $detalle['cantidad'];
                unset($detalle['new_product_name'], $detalle['new_category_name']);
            }

            $orden->update([
                'estado' => $data['estado'],
                'detalles' => $data['detalles'],
                'monto' => $monto,
            ]);

            if ($data['estado'] === 'entregado') {
                $this->updateProductStock($data['detalles']);
            }

            DB::commit();

            foreach ($newProducts as $producto) {
                $alerts['alerta_productos.' . $producto->id] = [
                    'mensaje' => "El producto '{$producto->nombre}' se creó como inactivo. Debe activarlo y editarlo en la sección de productos.",
                    'url' => route('productos.edit', $producto)
                ];
            }

            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('success', 'Orden de compra actualizada correctamente.')
                ->with($alerts);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in ordenCompraUpdate: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id,
                'orden_id' => $orden->id,
                'request_data' => $request->all()
            ]);
            return redirect()->route('admin.proveedores.ordenes.historial', ['proveedor' => $proveedor->id])
                ->with('error', 'Error al actualizar la orden de compra: ' . $e->getMessage());
        }
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'nullable|numeric|exists:categorias,id',
        ]);

        $nombre = ucfirst(trim($request->input('nombre', 'Producto Genérico')));
        $categoria_id = $request->input('categoria_id');

        try {
            $correctedName = $this->correctSpelling($nombre);
            $producto = Producto::firstOrCreate(
                ['nombre' => $correctedName],
                [
                    'stock' => 0,
                    'precio' => 0,
                    'precio_compra' => 0,
                    'estado' => 'inactivo',
                    'categoria_id' => $categoria_id
                ]
            );

            return response()->json([
                'success' => true,
                'producto' => [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'categoria_id' => $producto->categoria_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in storeProduct: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(['success' => false, 'error' => 'Error al crear el producto'], 500);
        }
    }

    public function configurarCorreo()
    {
        $correo_notificaciones = config('mail.from.address', 'default@example.com');
        return view('admin.proveedores.configurar-correo', compact('correo_notificaciones'));
    }

    public function guardarCorreoNotificaciones(Request $request)
    {
        $correo_notificaciones = $request->input('correo_notificaciones', 'default@example.com');
        $password_notificaciones = $request->input('password_notificaciones');

        if (!filter_var($correo_notificaciones, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('admin.proveedores.configurar-correo')
                ->with('error', 'El correo de notificaciones debe ser una dirección válida.');
        }

        try {
            $envFile = base_path('.env');
            $envContent = file_get_contents($envFile);
            $newEnv = [
                'MAIL_FROM_ADDRESS' => '"'.addslashes($correo_notificaciones).'"',
                'MAIL_USERNAME' => '"'.addslashes($correo_notificaciones).'"',
                'MAIL_PASSWORD' => '"'.addslashes($password_notificaciones).'"',
                'MAIL_FROM_NAME' => '"Tienda D\'jenny"',
            ];

            foreach ($newEnv as $key => $value) {
                $pattern = "/^{$key}=.*$/m";
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
                } else {
                    $envContent .= "\n{$key}={$value}";
                }
            }

            file_put_contents($envFile, $envContent);
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            try {
                Mail::raw('Este es un correo de prueba para verificar que la configuración SMTP de Tienda D\'jenny se ha actualizado correctamente.', function ($message) use ($correo_notificaciones) {
                    $message->to($correo_notificaciones)
                            ->subject('Verificación de Configuración de Correo - Tienda D\'jenny')
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });

                return redirect()->route('admin.proveedores.configurar-correo')
                    ->with('success', 'Correo de notificaciones actualizado a: ' . $correo_notificaciones)
                    ->with('smtp_success', 'Se envió un correo de verificación a ' . $correo_notificaciones . '. Por favor, revisa tu bandeja de entrada (o spam).');
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de prueba: ' . $e->getMessage());
                return redirect()->route('admin.proveedores.configurar-correo')
                    ->with('success', 'Correo de notificaciones actualizado a: ' . $correo_notificaciones)
                    ->with('smtp_error', 'La configuración se guardó, pero no se pudo enviar el correo de verificación: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar .env: ' . $e->getMessage());
            return redirect()->route('admin.proveedores.configurar-correo')
                ->with('error', 'Error al guardar la configuración del correo: ' . $e->getMessage());
        }
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
            foreach ($detalle['producto_ids'] ?? [] as $producto_id) {
                if (is_numeric($producto_id)) {
                    $producto = Producto::find($producto_id);
                    if ($producto) {
                        $cantidad = $detalle['cantidad'] ?? 0;
                        $producto->stock += $cantidad;
                        $producto->precio = $detalle['precio_venta'] ?? $producto->precio;
                        $producto->precio_compra = $detalle['precio_compra'] ?? $producto->precio_compra;
                        $producto->save();
                        Log::info('Stock y precios actualizados', [
                            'producto_id' => $producto->id,
                            'cantidad' => $cantidad,
                            'nuevo_stock' => $producto->stock,
                            'precio_compra' => $producto->precio_compra,
                            'precio_venta' => $producto->precio
                        ]);
                    }
                }
            }
        }
    }

    private function correctSpelling(string $text): string
    {
        $corrections = [
            'nesesario' => 'necesario',
            'nesecario' => 'necesario',
            'nesesidad' => 'necesidad',
            'desarollo' => 'desarrollo',
            'prodcto' => 'producto',
            'proveedor' => 'proveedor',
            'cantida' => 'cantidad',
            'recivido' => 'recibido',
            'entrega' => 'entregado',
            'procesando' => 'procesando',
            'cancelado' => 'cancelado',
            'columbia' => 'Colombia',
            'bogota' => 'Bogotá',
            'medellin' => 'Medellín',
            'cali' => 'Cali',
            'barranquilla' => 'Barranquilla',
            'cartagena' => 'Cartagena',
            'cucuta' => 'Cúcuta',
            'ibague' => 'Ibagué',
            'pereira' => 'Pereira',
            'manizales' => 'Manizales',
            'villavicencio' => 'Villavicencio',
        ];

        $text = strtolower($text);
        foreach ($corrections as $wrong => $correct) {
            $text = str_replace($wrong, $correct, $text);
        }
        return ucfirst($text);
    }
}