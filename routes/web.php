<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;

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
   

  // CRUD de categorías
Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store'); // IMPORTANTE
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
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Rutas para gestión de usuarios
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Rutas para administradores con rol "admin" y permiso "administrar categorías"
Route::middleware(['auth', 'role:admin', 'permission:administrar categorías'])->group(function () {
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');

});

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Redirecciones según el rol
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:usuario'])->get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');



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

