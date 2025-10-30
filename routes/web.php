<?php

use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Dokter\JadwalPriksaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register'); 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function ()
{
    Route::get('/dashboard', function() {
        return view('admin.dashboard'); 
    })->name('admin.dashboard'); 
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function (){
    Route::get('/dashboard', function() { 
        return view('dokter.dashboard'); 
    })->name('dokter.dashboard'); 
    Route::resource('jadwal-periksa', JadwalPriksaController::class);
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function (){
    Route::get('/dashboard', function(){
        return view('pasien.dashboard'); 
    })->name('pasien.dashboard');
});