<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\User;
use App\Models\Proveedor;
use App\Models\OrdenCompra;
use App\Notifications\OrdenCompraNotification;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index() { return view('admin.dashboard'); }
    public function usuarios() { $users = User::all(); return view('admin.usuarios', compact('users')); }
    public function pedidos() { $ordenes = Orden::with(['detalles.producto', 'user'])->orderBy('created_at', 'desc')->get(); return view('admin.pedidos', compact('ordenes')); }
    public function updateStatus(Request $request, Orden $orden) { /* ... */ }
    public function refund(Request $request, Orden $orden) { /* ... */ }
    public function generateInvoice(Orden $orden) { /* ... */ }
    public function usersIndex() { /* ... */ }
    public function create() { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function edit($user) { /* ... */ }
    public function update(Request $request, $user) { /* ... */ }
    public function destroy($user) { /* ... */ }

    public function proveedores()
    {
        $proveedores = Proveedor::all();
        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function proveedorCreate() { return view('admin.proveedores.create'); }

    public function proveedorStore(Request $request)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:proveedores,email',
            'direccion' => 'nullable|string',
            'productos_suministrados' => 'nullable|array',
            'condiciones_pago' => 'nullable|string',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
        ]);

        Proveedor::create($request->all());
        return redirect()->route('admin.proveedores')->with('success', 'Proveedor registrado correctamente.');
    }

    public function proveedorEdit(Proveedor $proveedor) { return view('admin.proveedores.edit', compact('proveedor')); }

    public function proveedorUpdate(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:proveedores,email,' . $proveedor->id,
            'direccion' => 'nullable|string',
            'productos_suministrados' => 'nullable|array',
            'condiciones_pago' => 'nullable|string',
            'fecha_vencimiento_contrato' => 'nullable|date',
            'recibir_notificaciones' => 'nullable|boolean',
        ]);

        $proveedor->update($request->all());
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

    public function ordenCompraCreate(Proveedor $proveedor)
    {
        return view('admin.proveedores.orden_create', compact('proveedor'));
    }

    public function ordenCompraStore(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'detalles' => 'nullable|array',
            'detalles.*.producto' => 'required_with:detalles|string|max:255',
            'detalles.*.cantidad' => 'required_with:detalles|integer|min:1',
            'detalles.*.descripcion' => 'nullable|string|max:500',
        ]);

        $ordenCompra = $proveedor->ordenesCompra()->create([
            'proveedor_id' => $proveedor->id,
            'fecha' => $request->fecha,
            'monto' => $request->monto,
            'estado' => $request->estado,
            'detalles' => $request->detalles,
        ]);

        if ($proveedor->email && $proveedor->recibir_notificaciones) {
            try {
                $proveedor->notify(new OrdenCompraNotification($ordenCompra, $proveedor));
                return redirect()->route('admin.proveedores.historial', $proveedor)
                    ->with('success', 'Orden de compra registrada y notificación enviada al proveedor.');
            } catch (\Exception $e) {
                Log::error('Error enviando notificación de orden de compra: ' . $e->getMessage());
                return redirect()->route('admin.proveedores.historial', $proveedor)
                    ->with('warning', 'Orden registrada, pero hubo un problema al enviar el correo: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.proveedores.historial', $proveedor)
            ->with('success', 'Orden de compra registrada.');
    }

    public function historialCompras(Proveedor $proveedor)
    {
        $ordenes = $proveedor->ordenesCompra()->orderBy('created_at', 'desc')->get();
        return view('admin.proveedores.historial', compact('proveedor', 'ordenes'));
    }

    public function configurarCorreo()
    {
        $correo_notificaciones = config('mail.notificaciones_correo', 'alphasoft.cmjff@gmail.com');
        return view('admin.proveedores.configurar_correo', compact('correo_notificaciones'));
    }

    public function guardarCorreo(Request $request)
    {
        $request->validate([
            'correo_notificaciones' => 'required|email',
        ]);

        config(['mail.notificaciones_correo' => $request->correo_notificaciones]);
        file_put_contents(config_path('mail.php'), '<?php return ' . var_export(config('mail'), true) . ';');

        return redirect()->route('admin.proveedores')->with('success', 'Correo de notificaciones actualizado.');
    }
}