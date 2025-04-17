<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStepController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProcedureController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Home Route - Redirects based on role
Route::get('/home', function () {
    $user = auth()->user();

    if ($user->role === 'doctor') {
        return redirect()->route('doctor.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'employee') {
        return redirect()->route('employee.dashboard');
    }

    return redirect()->route('login');
})->name('home');

// Doctor Routes
Route::middleware(['auth'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', function () {
        return view('doctor.dashboard');
    })->name('doctor.dashboard');

    Route::get('/my-work', [OrderController::class, 'index'])->name('doctor.my-work');
    Route::get('/new-order', [OrderController::class, 'create'])->name('doctor.new-order');
    Route::post('/orders', [OrderController::class, 'store'])->name('doctor.orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('doctor.order.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('doctor.orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('doctor.orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('doctor.orders.destroy');

    Route::get('/finance', function () {
        return view('doctor.finance');
    })->name('doctor.finance');

    Route::get('/prices', function () {
        return view('doctor.prices');
    })->name('doctor.prices');

    Route::get('/contact', function () {
        return view('doctor.contact');
    })->name('doctor.contact');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User Management
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Procedure Management
    Route::resource('procedures', ProcedureController::class)->names([
        'index' => 'admin.procedures.index',
        'create' => 'admin.procedures.create',
        'store' => 'admin.procedures.store',
        'show' => 'admin.procedures.show',
        'edit' => 'admin.procedures.edit',
        'update' => 'admin.procedures.update',
        'destroy' => 'admin.procedures.destroy',
    ]);

    // Color Management
    Route::resource('colors', ColorController::class)->names([
        'index' => 'admin.colors.index',
        'create' => 'admin.colors.create',
        'store' => 'admin.colors.store',
        'show' => 'admin.colors.show',
        'edit' => 'admin.colors.edit',
        'update' => 'admin.colors.update',
        'destroy' => 'admin.colors.destroy',
    ]);

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::post('/orders/{order}/assign', [OrderController::class, 'assignEmployees'])->name('admin.orders.assign');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');

    // Order Step Management
    Route::get('/steps', [OrderStepController::class, 'index'])->name('admin.steps.index');
    Route::get('/steps/{orderStep}/assign', [OrderStepController::class, 'assignForm'])->name('admin.steps.assign.form');
    Route::post('/steps/{orderStep}/assign', [OrderStepController::class, 'assign'])->name('admin.steps.assign');
    Route::put('/steps/{orderStep}/status', [OrderStepController::class, 'updateStatus'])->name('admin.steps.status');
    Route::put('/steps/{orderStep}/due-date', [OrderStepController::class, 'updateDueDate'])->name('admin.steps.due-date');
});

// Employee Routes
Route::middleware(['auth'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    Route::get('/orders', [EmployeeDashboardController::class, 'orders'])->name('employee.orders');
    Route::get('/orders/{order}', [EmployeeDashboardController::class, 'showOrder'])->name('employee.order.show');
    Route::put('/steps/{orderStep}', [EmployeeDashboardController::class, 'updateStepStatus'])->name('employee.step.update');
});
