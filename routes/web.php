<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación (registro habilitado)
Auth::routes();

// Redirección después del login
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Grupo de rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    // CRUD de productos
    Route::resource('productos', ProductoController::class);

    // CRUD de categorías
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::patch('categorias/{categoria}', [CategoriaController::class, 'update']);
    Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
});

// Rutas para usuarios con rol "usuario"
Route::middleware(['auth', 'role:usuario'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

// Rutas para administradores con rol "admin"
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');

    // Rutas para gestión de usuarios
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Rutas para gestión de pedidos
    Route::post('/pedidos/{orden}/update-status', [AdminController::class, 'updateStatus'])->name('admin.pedidos.updateStatus');
    Route::post('/pedidos/{orden}/refund', [AdminController::class, 'refund'])->name('admin.pedidos.refund');
    Route::get('/pedidos/{orden}/invoice', [AdminController::class, 'generateInvoice'])->name('admin.pedidos.invoice');

    // Rutas para gestión de proveedores
    Route::get('/proveedores', [AdminController::class, 'proveedores'])->name('admin.proveedores');
    Route::get('/proveedores/create', [AdminController::class, 'proveedorCreate'])->name('admin.proveedores.create');
    Route::post('/proveedores', [AdminController::class, 'proveedorStore'])->name('admin.proveedores.store');
    Route::get('/proveedores/{proveedor}/edit', [AdminController::class, 'proveedorEdit'])->name('admin.proveedores.edit');
    Route::put('/proveedores/{proveedor}', [AdminController::class, 'proveedorUpdate'])->name('admin.proveedores.update');
    Route::delete('/proveedores/{proveedor}', [AdminController::class, 'proveedorDestroy'])->name('admin.proveedores.destroy');

    // Rutas para órdenes de compra (ajustadas para consistencia)
    Route::get('/proveedores/{proveedor}/ordenes', [AdminController::class, 'historialCompras'])->name('admin.proveedores.ordenes.historial'); // Cambio aquí
    Route::get('/proveedores/{proveedor}/ordenes/create', [AdminController::class, 'ordenCompraCreate'])->name('admin.proveedores.ordenes.create');
    Route::post('/proveedores/{proveedor}/ordenes', [AdminController::class, 'ordenCompraStore'])->name('admin.proveedores.ordenes.store');
    Route::get('/proveedores/{proveedor}/ordenes/{orden}', [AdminController::class, 'ordenCompraShow'])->name('admin.proveedores.ordenes.show'); // Agregada para el botón "Ver"
    Route::put('/ordenes/{orden}', [AdminController::class, 'ordenCompraUpdate'])->name('admin.ordenes.update');

    // Rutas para configurar el correo de notificaciones
    Route::get('/proveedores/configurar-correo', [AdminController::class, 'configurarCorreo'])->name('admin.proveedores.configurar-correo');
    Route::post('/proveedores/configurar-correo', [AdminController::class, 'guardarCorreo'])->name('admin.proveedores.guardar-correo');
});

// Rutas para administradores con rol "admin" y permiso "administrar categorías"
Route::middleware(['auth', 'role:admin', 'permission:administrar categorías'])->group(function () {
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
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');
});