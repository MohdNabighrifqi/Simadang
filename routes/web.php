<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\PermintaanDataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermintaanDataController as AdminPermintaanController;
use App\Http\Controllers\Admin\ExportController;

/*
|----------------------------------------------------------------------
| PUBLIK
|----------------------------------------------------------------------
*/
Route::get('/',          [BerandaController::class,   'index'])->name('beranda');
Route::get('/peta',      [PetaController::class,      'index'])->name('peta.index');
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');


/*
|----------------------------------------------------------------------
| GUEST
|----------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get ('/login',    [LoginController::class,    'create'])->name('login');
    Route::post('/login',    [LoginController::class,    'store']);
    Route::get ('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

/*
|----------------------------------------------------------------------
| AUTH
|----------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
// Permintaan data — publik bisa akses
    Route::get('/permintaan-data',       [PermintaanDataController::class, 'create'])->name('permintaan.create');
    Route::post('/permintaan-data',      [PermintaanDataController::class, 'store']) ->name('permintaan.store');
    Route::get('/permintaan-data/{permintaan}/sukses', [PermintaanDataController::class, 'sukses'])->name('permintaan.sukses');
    
    Route::get ('/laporan/riwayat', [LaporanController::class, 'riwayat'])->name('laporan.riwayat');
    Route::get ('/laporan/create',  [LaporanController::class, 'create']) ->name('laporan.create');
    Route::post('/laporan',         [LaporanController::class, 'store'])  ->name('laporan.store');
    Route::get ('/laporan',         [LaporanController::class, 'index'])  ->name('laporan.index');
    Route::get ('/laporan/{laporan}',[LaporanController::class, 'show'])  ->name('laporan.show');
});

/*
|----------------------------------------------------------------------
| ADMIN
|----------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifikasi/cek', [DashboardController::class, 'pollCount'])->name('notifikasi.cek');

    // CRUD Laporan
    Route::get   ('/laporan',                   [AdminLaporanController::class, 'index'])  ->name('laporan.index');
    Route::get   ('/laporan/{laporan}',          [AdminLaporanController::class, 'show'])   ->name('laporan.show');
    Route::get   ('/laporan/{laporan}/edit',     [AdminLaporanController::class, 'edit'])   ->name('laporan.edit');
    Route::put   ('/laporan/{laporan}',          [AdminLaporanController::class, 'update']) ->name('laporan.update');
    Route::delete('/laporan/{laporan}',          [AdminLaporanController::class, 'destroy'])->name('laporan.destroy');
    Route::patch ('/laporan/{laporan}/verify',   [AdminLaporanController::class, 'verify']) ->name('laporan.verify');

    // CRUD Pengguna
    Route::get   ('/users',              [UserController::class, 'index'])  ->name('users.index');
    Route::get   ('/users/create',       [UserController::class, 'create']) ->name('users.create');
    Route::post  ('/users',              [UserController::class, 'store'])  ->name('users.store');
    Route::get   ('/users/{user}/edit',  [UserController::class, 'edit'])   ->name('users.edit');
    Route::put   ('/users/{user}',       [UserController::class, 'update']) ->name('users.update');
    Route::delete('/users/{user}',       [UserController::class, 'destroy'])->name('users.destroy');

    // Permintaan Data
    Route::get   ('/permintaan-data',                    [AdminPermintaanController::class, 'index'])  ->name('permintaan.index');
    Route::get   ('/permintaan-data/{permintaan}',       [AdminPermintaanController::class, 'show'])   ->name('permintaan.show');
    Route::patch ('/permintaan-data/{permintaan}',       [AdminPermintaanController::class, 'update']) ->name('permintaan.update');
    Route::delete('/permintaan-data/{permintaan}',       [AdminPermintaanController::class, 'destroy'])->name('permintaan.destroy');

    // Ekspor Data
    Route::get('/export',         [ExportController::class, 'form'])   ->name('export.form');
    Route::get('/export/laporan', [ExportController::class, 'laporan'])->name('export.laporan');
});
