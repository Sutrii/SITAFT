<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\MahasiswaJadwalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalDosenController;
use App\Http\Controllers\SkripsiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorangController;
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

// Dashboard Router
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->roleId == 1 && $user->positionId == 3) {
            return redirect()->route('dashboard.mahasiswa');
        }
        return redirect()->route('koordinator.dashboard');
    })->name('dashboard');
});

// Dashboard Mahasiswa
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard/mahasiswa', [MahasiswaDashboardController::class, 'index'])->name('dashboard.mahasiswa');
    Route::get('/dashboard/mahasiswa/jadwal', [MahasiswaJadwalController::class, 'index'])->name('mahasiswa.jadwal');
    Route::get('/dashboard/mahasiswa/daftar-seminar', [MahasiswaDashboardController::class, 'daftarSeminar'])->name('mahasiswa.daftar-seminar');
    Route::post('/dashboard/mahasiswa/daftar-seminar/proposal', [MahasiswaDashboardController::class, 'storeSeminarProposal'])->name('mahasiswa.daftar-seminar.proposal');
    Route::post('/dashboard/mahasiswa/daftar-seminar/hasil', [MahasiswaDashboardController::class, 'storeSeminarHasil'])->name('mahasiswa.daftar-seminar.hasil');
    Route::post('/dashboard/mahasiswa/daftar-seminar/sidang-akhir', [MahasiswaDashboardController::class, 'storeSidangAkhir'])->name('mahasiswa.daftar-seminar.sidang');
    Route::get('/dashboard/mahasiswa/download-berita-acara', [MahasiswaDashboardController::class, 'downloadBeritaAcara'])->name('mahasiswa.download-berita-acara');
    Route::get('/dashboard/mahasiswa/panduan', [MahasiswaDashboardController::class, 'panduan'])->name('mahasiswa.panduan');
});

// Dashboard Koordinator & Data Master
Route::middleware(['auth', 'verified', 'role:koordinator'])->prefix('dashboard/koordinator')->name('koordinator.')->group(function () {
    
    // Dashboard Stats & API
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/month/{month}/{year}', [DashboardController::class, 'fetchMonth']);
    Route::get('/data/{month}/{day}/{year}', [DashboardController::class, 'fetchData']);
    Route::get('/data-today', [DashboardController::class, 'dataToday']);
    Route::get('/stats/penguji', [DashboardController::class, 'pengujiStats']);

    // Request Seminar
    Route::get('/request-seminar', [\App\Http\Controllers\KoordinatorSeminarController::class, 'index'])->name('request-seminar');
    Route::post('/request-seminar/{id}/status', [\App\Http\Controllers\KoordinatorSeminarController::class, 'updateStatus'])->name('request-seminar.update-status');

    // Jadwal Tugas Akhir
    Route::get('/jadwal', [App\Http\Controllers\JadwalController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [App\Http\Controllers\JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [App\Http\Controllers\JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [App\Http\Controllers\JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::post('/jadwal/import', [App\Http\Controllers\JadwalController::class, 'import'])->name('jadwal.import');
    Route::get('/skripsi/{id}/penguji-proposal', [JadwalController::class, 'getPengujiFromProposal']);

    // Data Jadwal Dosen
    Route::get('/jadwal-dosen', [App\Http\Controllers\JadwalDosenController::class, 'index'])->name('jadwal-dosen');
    Route::post('/jadwal-dosen', [App\Http\Controllers\JadwalDosenController::class, 'store'])->name('jadwal-dosen.store');
    Route::put('/jadwal-dosen/{jadwal}', [App\Http\Controllers\JadwalDosenController::class, 'update'])->name('jadwal-dosen.update');
    Route::delete('/jadwal-dosen/{jadwal}', [App\Http\Controllers\JadwalDosenController::class, 'destroy'])->name('jadwal-dosen.destroy');

    // Data Skripsi Mahasiswa
    Route::get('/skripsi', [App\Http\Controllers\SkripsiController::class, 'index'])->name('skripsi');
    Route::post('/skripsi', [App\Http\Controllers\SkripsiController::class, 'store'])->name('skripsi.store');
    Route::put('/skripsi/{skripsi}', [App\Http\Controllers\SkripsiController::class, 'update'])->name('skripsi.update');
    Route::delete('/skripsi/{skripsi}', [App\Http\Controllers\SkripsiController::class, 'destroy'])->name('skripsi.destroy');

    // Data Dosen
    Route::get('/data-dosen', [App\Http\Controllers\DosenController::class, 'index'])->name('data-dosen');
    Route::post('/data-dosen', [App\Http\Controllers\DosenController::class, 'store'])->name('dosen.store');
    Route::put('/data-dosen/{dosen}', [App\Http\Controllers\DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/data-dosen/{dosen}', [App\Http\Controllers\DosenController::class, 'destroy'])->name('dosen.destroy');

    // Data Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Data Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/download-template', [UserController::class, 'downloadTemplate'])->name('users.download-template');
    Route::post('/users/import', [UserController::class, 'importExcel'])->name('users.import');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/borang', [BorangController::class, 'index'])->name('borang');

Route::get('/jadwal/share/{title}/{from}/{to}', [JadwalController::class, 'sharedView'])
    ->where([
        'title' => '.*',
        'from' => '\d{4}-\d{2}-\d{2}',
        'to' => '\d{4}-\d{2}-\d{2}'
    ])
    ->name('jadwal.shared');

Route::get('/skripsi/{id}/detail', [SkripsiController::class, 'getById'])->name('skripsi.detail');

Route::get('/mahasiswa/{id}/skripsi', [SkripsiController::class, 'getByMahasiswa']);

Route::get('/dosen/by-bidang/{bidang}', [\App\Http\Controllers\DosenController::class, 'getByBidang']);

Route::get('/skripsi/{id}/auto-penguji', [JadwalController::class, 'autoPengujiBySkripsi']);
Route::get('/skripsi/{id}/available-slots', [JadwalController::class, 'getAvailableSlots']);

Route::get('/dosen/all', [DosenController::class, 'all']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get(
        '/jadwal/by-mahasiswa/{mahasiswaId}',
        [JadwalController::class, 'getJadwalByMahasiswa']
    );
});

require __DIR__.'/auth.php';
