<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PoliController;

Route::get('/', function () {
    return view('welcome');
});

// Route Autentikasi Umum (Sudah Benar)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/logout', [AuthController::class, 'logout']); 

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function ()
{
    Route::get('/dashboard', function() {
        return view('admin.dashboard'); 
    })->name('admin.dashboard'); 
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function (){
    Route::get('/dashboard', function() { 
        return view('dokter.dashboard'); 
    })->name('dokter.dashboard'); 
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function (){
    Route::get('/dashboard', function(){
        return view('pasien.dashboard'); 
    })->name('pasien.dashboard');
});