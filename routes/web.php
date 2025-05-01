<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    //API
Route::get('/workspaces', [WorkspaceController::class, 'index']);
Route::get('/workspaces/{id}', [WorkspaceController::class, 'list']);

// Google Login Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Workspace routes
    Route::get('/workspace/{workspace}', [WorkspaceController::class, 'show'])->name('workspace.detail');
    Route::get('/workspace/{workspace}/booking', [WorkspaceController::class, 'booking'])->name('workspace.booking');
    Route::post('/workspace/{workspace}/booking', [WorkspaceController::class, 'storeBooking'])->name('workspace.booking.store');
    Route::get('/workspace/{workspace}/edit', [WorkspaceController::class, 'edit'])->name('workspace.edit');
    Route::put('/workspace/{workspace}', [WorkspaceController::class, 'update'])->name('workspace.update');
    
    // Booking history and management
    Route::get('/booking/history', [WorkspaceController::class, 'myBookings'])->name('booking.history');
    Route::get('/booking/{pemesanan}', [WorkspaceController::class, 'bookingDetail'])->name('booking.detail');
    Route::post('/booking/{pemesanan}/cancel', [WorkspaceController::class, 'cancelBooking'])->name('booking.cancel');
    
    // Admin routes - Middleware diubah ke manual check di controller
    Route::prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
    });

    // Payment routes
    Route::get('/payment/history', [PaymentController::class, 'paymentHistory'])->name('payment.history');
    Route::get('/payment/form', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');
    Route::get('/payment/pending', [PaymentController::class, 'paymentPending'])->name('payment.pending');
});

Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
