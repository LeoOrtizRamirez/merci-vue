<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvestmentController;
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
    Route::get('/loans/datatable', [LoanController::class, 'datatable'])->name('loans.datatable');
    Route::resource('loans', LoanController::class);
});
Route::post('loan/payment/to-pay/{id}/{view}', [PaymentController::class, 'setToPay'])->name('payment.store');
Route::get('loan/{id}', [LoanController::class, 'getLoan'])->name('loan.get');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/investments/datatable', [InvestmentController::class, 'datatable'])->name('investments.datatable');
    Route::resource('investments', InvestmentController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/payments/datatable', [PaymentController::class, 'datatable'])->name('payments.datatable');
    Route::resource('payments', PaymentController::class);
});
/*

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->resource('/user', UserController::class)->parameters(['user' => 'person']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->resource('/investment', InvestmentController::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->resource('/customer', CustomerController::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->resource('/payment', PaymentController::class);
Route::post('loan/payment/to-pay/{id}/{view}', [PaymentController::class, 'setToPay'])->name('payment.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->resource('/loan', LoanController::class);
Route::get('loan/{id}', [LoanController::class, 'getLoan'])->name('loan.get');
*/