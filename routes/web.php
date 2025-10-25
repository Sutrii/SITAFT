<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalDosenController;
use App\Http\Controllers\SkripsiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return redirect('/login');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Jadwal Tugas Akhir
Route::get('/jadwal', [JadwalController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('jadwal');

// Data Jadwal Dosen
Route::get('/dosen', [JadwalDosenController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('jadwal-dosen');

// Data Skripsi Mahasiswa
Route::get('/skripsi', [App\Http\Controllers\SkripsiController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('skripsi');

// Data Dosen
Route::get('/data-dosen', [App\Http\Controllers\DosenController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('data-dosen');
    
// Data Mahasiswa
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('mahasiswa');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
