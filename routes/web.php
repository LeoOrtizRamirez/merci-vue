<?php

use App\Http\Controllers\ActaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Security;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group( function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //return Inertia::render('Dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::resource('users', UserController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/customers/datatable', [CustomerController::class, 'datatable'])->name('customers.datatable');
    Route::resource('customers', CustomerController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/empresas/datatable', [EmpresaController::class, 'datatable'])->name('empresas.datatable');
    Route::resource('empresas', EmpresaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/categorias/datatable', [CategoriaController::class, 'datatable'])->name('categorias.datatable');
    Route::resource('categorias', CategoriaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/actas/datatable', [ActaController::class, 'datatable'])->name('actas.datatable');
    Route::resource('actas', ActaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/roles/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');
    Route::get('/roles/permissions/datatable', [RoleController::class, 'rolePermissionsDatatable'])->name('roles.permissions.datatable');
    Route::post('/roles/permissions/toggle', [RoleController::class, 'rolePermissionsToggle'])->name('roles.permissions.toggle');
    Route::resource('roles', RoleController::class);
});