<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\ApiTokenController;
// use App\Http\Controllers\;

// Auth
Route::get('/login', [AuthController::class, 'loginshow'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/my-workspace');
    });

    // Workspace
    Route::get('/my-workspace', [WorkspaceController::class, 'index'])->name('workspace.index');
    Route::get('/{workspace}/show-workspace', [WorkspaceController::class, 'show'])->name('workspace.show');
    Route::get('/create-workspace', [WorkspaceController::class, 'create'])->name('workspace.create');
    Route::post('/create-workspace', [WorkspaceController::class, 'store'])->name('workspace.store');
    Route::get('/{workspace}/edit-workspace', [WorkspaceController::class, 'edit'])->name('workspace.edit');
    Route::put('/{workspace}/edit-workspace', [WorkspaceController::class, 'update'])->name('workspace.update');
    Route::delete('/{workspace}/delete-workspace', [WorkspaceController::class, 'delete'])->name('workspace.delete');

    // API Token
    Route::get('/my-token', [WorkspaceController::class, 'index'])->name('apitoken.index');
    Route::get('/{token}/my-token', [WorkspaceController::class, 'show'])->name('apitoken.show');
    Route::get('/create-token', [WorkspaceController::class, 'create'])->name('apitoken.create');
    Route::post('/create-token', [WorkspaceController::class, 'store'])->name('apitoken.store');
    Route::patch('/{token}/revoke-token', [WorkspaceController::class, 'revoke'])->name('apitoken.revoke');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
