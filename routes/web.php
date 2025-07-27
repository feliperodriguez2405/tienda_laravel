<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReseñaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CajeroController;

/*
|---------------------------------------------------------------------------
| Rutas Web
|---------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas de autenticación (registro habilitado)
Auth::routes();
Auth::routes(['verify' => false]);

// Redirección después del login
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/productos', [HomeController::class, 'productos'])->name('productos.index');

// Grupo de rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    // CRUD de productos
    Route::resource('productos', ProductoController::class);
    // Rutas para búsqueda y generación de códigos de barras
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');
    Route::get('/productos/generar-barcode', [ProductoController::class, 'generarBarcode'])->name('productos.generar-barcode');

    // CRUD de categorías
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::patch('categorias/{categoria}', [CategoriaController::class, 'update']);
    Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::post('categorias/check-name', [CategoriaController::class, 'checkName'])->name('categorias.checkName');
});

// Rutas para usuarios con rol "usuario"
Route::middleware(['auth', 'role:usuario'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/cart', [UserController::class, 'cart'])->name('user.cart');
    Route::post('/user/cart/add/{producto}', [UserController::class, 'addToCart'])->name('user.cart.add');
    Route::post('/user/cart/remove/{producto}', [UserController::class, 'removeFromCart'])->name('user.cart.remove');
    Route::post('/user/cart/update/{producto}', [UserController::class, 'updateCart'])->name('user.cart.update');
    Route::post('/user/cart/checkout', [UserController::class, 'cartCheckout'])->name('user.cart.checkout');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/orders/{orden}', [UserController::class, 'showOrder'])->name('user.orders.show');
    Route::post('/user/orders/cancel/{orden}', [UserController::class, 'cancelOrder'])->name('order.cancel');
    Route::patch('/user/orders/cancel/{orden}', [UserController::class, 'cancelOrder'])->name('order.cancel');
    Route::delete('/user/orders/delete', [UserController::class, 'deleteSelectedOrders'])->name('orders.delete.selected');
});

// Rutas para el cajero
Route::get('/cajero/dashboard', [CajeroController::class, 'dashboard'])
    ->name('cajero.dashboard')
    ->middleware(['auth', 'role:cajero']);

// Rutas para administradores con rol "admin"
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');

    // Rutas de reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('admin.reportes');

    // Rutas para gestión de usuarios
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
    Route::post('/pedidos/{orden}/update-status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::post('/pedidos/{orden}/refund', [AdminController::class, 'refund'])->name('admin.refund');
    Route::get('/pedidos/{orden}/invoice', [AdminController::class, 'invoice'])->name('admin.invoice');
    Route::get('/pedidos/{orden}/generate-invoice', [AdminController::class, 'generateInvoice'])->name('admin.generateInvoice');

    // Rutas para gestión de proveedores
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('admin.proveedores.edit');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('admin.proveedores.destroy');
    Route::delete('/proveedores/{proveedor}/ordenes/{orden}', [ProveedorController::class, 'ordenCompraDestroy'])->name('admin.proveedores.ordenes.destroy');
    Route::post('/admin/productos/store', [ProveedorController::class, 'storeProduct'])->name('admin.productos.store');
    Route::get('admin/proveedores/{proveedor}/ordenes/{orden}/edit', [ProveedorController::class, 'edit'])->name('admin.proveedores.ordenes.edit'); 
    
    // Rutas para configurar correo de notificaciones
    Route::get('/proveedores/configurar-correo', [ProveedorController::class, 'configurarCorreo'])->name('admin.proveedores.configurar-correo');
    Route::post('/proveedores/configurar-correo', [ProveedorController::class, 'guardarCorreoNotificaciones'])->name('admin.proveedores.guardar-correo');

    // Rutas para órdenes de compra
    Route::get('/proveedores/{proveedor}/ordenes', [ProveedorController::class, 'historialCompras'])->name('admin.proveedores.ordenes.historial');
    Route::get('/proveedores/{proveedor}/ordenes/create', [ProveedorController::class, 'ordenCompraCreate'])->name('admin.proveedores.ordenes.create');
    Route::post('/proveedores/{proveedor}/ordenes', [ProveedorController::class, 'ordenCompraStore'])->name('admin.proveedores.ordenes.store');
    Route::get('/proveedores/{proveedor}/ordenes/{orden}', [ProveedorController::class, 'ordenCompraShow'])->name('admin.proveedores.ordenes.show');
    Route::put('/proveedores/{proveedor}/ordenes/{orden}', [ProveedorController::class, 'ordenCompraUpdate'])->name('admin.proveedores.ordenes.update');
Route::delete('/proveedores/{proveedor}/ordenes/{orden}', [ProveedorController::class, 'ordenCompraDestroy'])->name('admin.proveedores.ordenes.destroy');
    Route::post('/proveedores/{proveedor}/ordenes/{orden}/update-producto', [ProveedorController::class, 'updateProducto'])->name('admin.proveedores.ordenes.updateProducto');
});

// Rutas para administradores con rol "admin" y permiso "administrar categorías"
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
});

// Rutas de autenticación personalizadas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Redirecciones según el rol
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:usuario'])->get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');

// Rutas adicionales para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/products', [UserController::class, 'products'])->name('user.products');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');
    Route::put('/user/settings', [UserController::class, 'updateSettings'])->name('user.settings.update');
    Route::get('/user/orders/{orden}', [UserController::class, 'showOrder'])->name('user.orders.show');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/reseñas', [ReseñaController::class, 'index'])->name('user.reviews');
    Route::post('/reseñas', [ReseñaController::class, 'store'])->middleware('auth')->name('user.reviews.store');
});

// Rutas adicionales para cajero
Route::prefix('cajero')->middleware(['auth', 'role:cajero'])->group(function () {
    Route::get('/dashboard', [CajeroController::class, 'dashboard'])->name('cajero.dashboard');
    Route::get('/sale', [CajeroController::class, 'sale'])->name('cajero.sale');
    Route::post('/sale', [CajeroController::class, 'sale']);
    Route::get('/payment/{orden_id}', [CajeroController::class, 'payment'])->name('cajero.payment');
    Route::post('/payment/{orden_id}', [CajeroController::class, 'confirmPayment'])->name('cajero.payment.confirm');
    Route::get('/transactions', [CajeroController::class, 'transactions'])->name('cajero.transactions');
    Route::get('/transactions/export', [CajeroController::class, 'exportTransactions'])->name('cajero.transactions.export');
    Route::patch('/order/{id}', [CajeroController::class, 'updateOrder'])->name('cajero.order.update');
    Route::delete('/order/{id}', [CajeroController::class, 'deleteOrder'])->name('cajero.order.delete');
    Route::post('/order/{id}/pay', [CajeroController::class, 'payOrder'])->name('cajero.order.pay');
    Route::get('/close', [CajeroController::class, 'close'])->name('cajero.close');
    Route::post('/close', [CajeroController::class, 'close']);
    Route::post('/order/{order}/refund', [CajeroController::class, 'refundOrder'])->name('cajero.order.refunded');

    // Rutas para facturación
    Route::get('/pedidos/{orden}/factura', [AdminController::class, 'factura'])->name('admin.pedidos.factura');
});

// Rutas para más inormación y manuales
Route::get('/manual', function () {
    return view('manual');
})->name('manual');
Route::get('/manual/cajero', function () {
    return view('cajero.manualc');
})->name('manualc');
Route::get('/manual/usuario', function () {
    return view('users.manualu');
})->name('manualu');
Route::get('/manual/administrador', function () {
    return view('admin.manuala');
})->name('manuala');
Route::get('/manual/tecnico', function () {
    return view('manualTec');
})->name('manualTec');
Route::get('/developers', function () {
    return view('developers');
})->name('developers');