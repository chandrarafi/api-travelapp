<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Middleware\AdminWebMiddleware;

// Root redirect to Admin Login or Dashboard
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Auth Routes
Route::get('/admin/login', [AdminDashboardController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'login']);
Route::post('/admin/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');

// Protected Admin Dashboard Routes
Route::middleware([AdminWebMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Rute Management
    Route::get('/rute', [AdminDashboardController::class, 'ruteIndex'])->name('admin.rute.index');
    Route::post('/rute', [AdminDashboardController::class, 'ruteStore'])->name('admin.rute.store');
    Route::put('/rute/{id}', [AdminDashboardController::class, 'ruteUpdate'])->name('admin.rute.update');
    Route::delete('/rute/{id}', [AdminDashboardController::class, 'ruteDestroy'])->name('admin.rute.destroy');

    // Mobil / Fleet Management
    Route::get('/mobil', [AdminDashboardController::class, 'mobilIndex'])->name('admin.mobil.index');
    Route::post('/mobil', [AdminDashboardController::class, 'mobilStore'])->name('admin.mobil.store');
    Route::put('/mobil/{id}', [AdminDashboardController::class, 'mobilUpdate'])->name('admin.mobil.update');
    Route::delete('/mobil/{id}', [AdminDashboardController::class, 'mobilDestroy'])->name('admin.mobil.destroy');

    // Pemesanan / Bookings Management
    Route::get('/pemesanan', [AdminDashboardController::class, 'pemesananIndex'])->name('admin.pemesanan.index');
    Route::get('/pemesanan/{id}', [AdminDashboardController::class, 'pemesananShow'])->name('admin.pemesanan.show');
    Route::put('/pemesanan/{id}/status', [AdminDashboardController::class, 'pemesananUpdateStatus'])->name('admin.pemesanan.update-status');
    Route::delete('/pemesanan/{id}', [AdminDashboardController::class, 'pemesananDestroy'])->name('admin.pemesanan.destroy');

    // Pembayaran Management
    Route::get('/pembayaran', [AdminDashboardController::class, 'pembayaranIndex'])->name('admin.pembayaran.index');
    Route::post('/pembayaran/{id}/konfirmasi', [AdminDashboardController::class, 'pembayaranKonfirmasi'])->name('admin.pembayaran.konfirmasi');
    Route::post('/pembayaran/{id}/tolak', [AdminDashboardController::class, 'pembayaranTolak'])->name('admin.pembayaran.tolak');

    // Pengguna / User Management
    Route::get('/pengguna', [AdminDashboardController::class, 'penggunaIndex'])->name('admin.pengguna.index');
    Route::post('/pengguna', [AdminDashboardController::class, 'penggunaStore'])->name('admin.pengguna.store');
    Route::put('/pengguna/{id}', [AdminDashboardController::class, 'penggunaUpdate'])->name('admin.pengguna.update');
    Route::delete('/pengguna/{id}', [AdminDashboardController::class, 'penggunaDestroy'])->name('admin.pengguna.destroy');
});
