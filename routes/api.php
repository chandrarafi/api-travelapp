<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtentikasiController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\KursiController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenggunaController;
use App\Http\Middleware\HanyaAdmin;

Route::post('/registrasi', [OtentikasiController::class, 'registrasi']);
Route::post('/login', [OtentikasiController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profil', [OtentikasiController::class, 'profil']);
    Route::post('/keluar', [OtentikasiController::class, 'keluar']);

    Route::get('/rute', [RuteController::class, 'index']);
    Route::get('/rute/{id}', [RuteController::class, 'detail']);

    Route::get('/mobil', [MobilController::class, 'index']);
    Route::get('/mobil/{id}', [MobilController::class, 'detail']);
    Route::get('/mobil/{id}/kursi', [KursiController::class, 'index']);

    Route::get('/travel', [MobilController::class, 'index']);
    Route::get('/travel/{id}', [MobilController::class, 'detail']);
    Route::get('/travel/{id}/kursi', [KursiController::class, 'index']);

    Route::post('/pemesanan', [PemesananController::class, 'buatPemesanan']);
    Route::get('/pemesanan', [PemesananController::class, 'index']);
    Route::get('/pemesanan/{id}', [PemesananController::class, 'detail']);

    Route::post('/pemesanan/{id}/pembayaran', [PembayaranController::class, 'bayar']);

    Route::middleware([HanyaAdmin::class])->prefix('admin')->group(function () {
        Route::get('/rute', [RuteController::class, 'index']);
        Route::post('/rute', [RuteController::class, 'tambah']);
        Route::get('/rute/{id}', [RuteController::class, 'detail']);
        Route::put('/rute/{id}', [RuteController::class, 'ubah']);
        Route::delete('/rute/{id}', [RuteController::class, 'hapus']);

        Route::get('/mobil', [MobilController::class, 'index']);
        Route::post('/mobil', [MobilController::class, 'tambah']);
        Route::get('/mobil/{id}', [MobilController::class, 'detail']);
        Route::put('/mobil/{id}', [MobilController::class, 'ubah']);
        Route::delete('/mobil/{id}', [MobilController::class, 'hapus']);

        Route::get('/pengguna', [PenggunaController::class, 'index']);
        Route::post('/pengguna', [PenggunaController::class, 'tambah']);
        Route::get('/pengguna/{id}', [PenggunaController::class, 'detail']);
        Route::put('/pengguna/{id}', [PenggunaController::class, 'ubah']);
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'hapus']);

        Route::get('/pemesanan', [PemesananController::class, 'index']);
        Route::get('/pemesanan/{id}', [PemesananController::class, 'detail']);
        Route::put('/pemesanan/{id}/status', [PemesananController::class, 'ubahStatus']);
        Route::post('/pemesanan/{id}/konfirmasi', [PembayaranController::class, 'konfirmasiAdmin']);
        Route::delete('/pemesanan/{id}', [PemesananController::class, 'hapus']);
    });
});
