<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{OrdenCompra, Proveedor, Producto};
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\{Log, DB};

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
        return view('admin.proveedores.create');
    }

    /**
     * Store a newly created provider in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateProveedor($request);
        Proveedor::create($data);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado correctamente.');
    }

    /**
     * Show the form for editing the specified provider.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified provider in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $this->validateProveedor($request, $proveedor->id);
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
                    ['stock' => 0]
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
        return view('admin.proveedores.orden_show', compact('proveedor', 'orden', 'productos'));
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
            'detalles.*.precio' => 'required|numeric|min:0',
        ]);

        $orden->estado = $data['estado'];
        $detalles = $orden->detalles;
        foreach ($data['detalles'] as $index => $detalleInput) {
            if (isset($detalles[$index])) {
                $detalles[$index]['producto'] = $detalleInput['producto'];
                if (isset($detalleInput['precio'])) {
                    $detalles[$index]['precio'] = $detalleInput['precio'];
                }
            }
        }
        $orden->detalles = $detalles;

        $monto = 0;
        foreach ($detalles as $detalle) {
            if (isset($detalle['precio']) && isset($detalle['cantidad'])) {
                $monto += $detalle['precio'] * $detalle['cantidad'];
            }
        }
        $orden->monto = $monto;

        if ($data['estado'] === 'entregado') {
            foreach ($detalles as $detalle) {
                $producto = Producto::firstOrCreate(
                    ['nombre' => $detalle['producto']],
                    ['stock' => 0]
                );

                $producto->increment('stock', $detalle['cantidad']);
                $producto->precio_compra = $detalle['precio'];
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
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => "nullable|email|unique:proveedores,email,{$id}",
            'direccion' => 'nullable|string',
            'productos_suministrados' => 'nullable|string',
            'condiciones_pago' => 'nullable|string',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

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
                ['stock' => 0]
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
                ->with('warning', 'Orden registrada, pero hubo un problema al enviar el correo: ' . $e->getMessage());
        }
    }
}