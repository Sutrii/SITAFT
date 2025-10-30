<?php

use App\Http\Controllers\DashboardController;
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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/month/{month}', [DashboardController::class, 'fetchMonth']);
    Route::get('/dashboard/data/{month}/{day?}', [DashboardController::class, 'fetchData']);

    Route::get('/dashboard/data-today', function () {
        $today = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
        $data = \App\Models\JadwalDosen::with('dosen')
            ->whereRaw('LOWER(hari) = ?', strtolower($today))
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');
        return response()->json($data);
    });
});

// Jadwal Tugas Akhir
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jadwal', [App\Http\Controllers\JadwalController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [App\Http\Controllers\JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [App\Http\Controllers\JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [App\Http\Controllers\JadwalController::class, 'destroy'])->name('jadwal.destroy');
});

// Data Jadwal Dosen
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jadwal-dosen', [App\Http\Controllers\JadwalDosenController::class, 'index'])->name('jadwal-dosen');
    Route::post('/jadwal-dosen', [App\Http\Controllers\JadwalDosenController::class, 'store'])->name('jadwal-dosen.store');
    Route::put('/jadwal-dosen/{jadwal}', [App\Http\Controllers\JadwalDosenController::class, 'update'])->name('jadwal-dosen.update');
    Route::delete('/jadwal-dosen/{jadwal}', [App\Http\Controllers\JadwalDosenController::class, 'destroy'])->name('jadwal-dosen.destroy');
});

// Data Skripsi Mahasiswa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/skripsi', [App\Http\Controllers\SkripsiController::class, 'index'])->name('skripsi');
    Route::post('/skripsi', [App\Http\Controllers\SkripsiController::class, 'store'])->name('skripsi.store');
    Route::put('/skripsi/{skripsi}', [App\Http\Controllers\SkripsiController::class, 'update'])->name('skripsi.update');
    Route::delete('/skripsi/{skripsi}', [App\Http\Controllers\SkripsiController::class, 'destroy'])->name('skripsi.destroy');
});

// Data Dosen
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/data-dosen', [App\Http\Controllers\DosenController::class, 'index'])->name('data-dosen');
    Route::post('/data-dosen', [App\Http\Controllers\DosenController::class, 'store'])->name('dosen.store');
    Route::put('/data-dosen/{dosen}', [App\Http\Controllers\DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/data-dosen/{dosen}', [App\Http\Controllers\DosenController::class, 'destroy'])->name('dosen.destroy');
});

// Data Mahasiswa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
