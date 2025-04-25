<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::get('/workspaces/{id}', [WorkspaceController::class, 'getWorkspace']);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Workspace routes
    Route::get('/workspace/{workspace}', [WorkspaceController::class, 'show'])->name('workspace.detail');
    Route::get('/workspace/{workspace}/booking', [WorkspaceController::class, 'booking'])->name('workspace.booking');
    Route::get('/workspace/{workspace}/edit', [WorkspaceController::class, 'edit'])->name('workspace.edit');
    Route::put('/workspace/{workspace}', [WorkspaceController::class, 'update'])->name('workspace.update');
});

require __DIR__.'/auth.php';
