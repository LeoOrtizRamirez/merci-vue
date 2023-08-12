<?php

use App\Http\Controllers\ActaController;
use App\Http\Controllers\ActividadeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EntregableController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Security;
use App\Http\Controllers\TareaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $empresa = $request->input('empresa_id');
        $user = Auth::user();
        $user->role_name = $user->getRoleNames()[0];
        if ($user->role_name == "ADMIN" || isset($empresa)) {
            return app()->call('App\Http\Controllers\DashboardController@index');
        } else {
            return app()->call('App\Http\Controllers\EmpresaController@index');
        }
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::post('/users/indicadores', [UserController::class, 'saveIndicador'])->name('users.saveIndicador');
    Route::get('/users/empresas', [UserController::class, 'getEmpresas'])->name('users.empresas');
    Route::resource('users', UserController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/empresas/datatable', [EmpresaController::class, 'datatable'])->name('empresas.datatable');
    Route::post('/api/upload-image', [EmpresaController::class, 'uploadImage'])->name('empresas.image');
    Route::resource('empresas', EmpresaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/entregables/datatable', [EntregableController::class, 'datatable'])->name('entregables.datatable');
    Route::post('/api/upload-entregable', [EntregableController::class, 'uploadImage'])->name('entregables.image');
    Route::resource('entregables', EntregableController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/categorias/datatable', [CategoriaController::class, 'datatable'])->name('categorias.datatable');
    Route::resource('categorias', CategoriaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/actividades/datatable', [ActividadeController::class, 'datatable'])->name('actividades.datatable');
    Route::resource('actividades', ActividadeController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/actas/datatable', [ActaController::class, 'datatable'])->name('actas.datatable');
    Route::get('/actas/cronograma/{id}', [ActaController::class, 'cronograma'])->name('actas.cronograma');
    Route::post('/importar-archivo', [ActaController::class, 'import'])->name('actas.import');
    Route::resource('actas', ActaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/tareas/datatable', [TareaController::class, 'datatable'])->name('tareas.datatable');
    Route::resource('tareas', TareaController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/roles/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');
    Route::get('/roles/permissions/datatable', [RoleController::class, 'rolePermissionsDatatable'])->name('roles.permissions.datatable');
    Route::post('/roles/permissions/toggle', [RoleController::class, 'rolePermissionsToggle'])->name('roles.permissions.toggle');
    Route::resource('roles', RoleController::class);
});
