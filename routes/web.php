<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\KeranjangController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\MidtransController;
use App\Http\Controllers\Customer\PesananController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Kasir\KategoriController;
use App\Http\Controllers\Kasir\MejaController;
use App\Http\Controllers\Kasir\MenuController as KasirMenuController;
use App\Http\Controllers\Kasir\PengaturanController;
use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN PELANGGAN (PUBLIK — TIDAK PERLU LOGIN/REGISTER)
| Diakses dengan cara SCAN QR CODE yang ada di meja.
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

Route::get('/m/{kode_qr}', [MenuController::class, 'index'])->name('menu.index');

Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::patch('/keranjang/{key}', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::delete('/keranjang/{key}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');

Route::get('/pesanan/{kode_pesanan}', [PesananController::class, 'status'])->name('pesanan.status');
Route::get('/pesanan/{kode_pesanan}/cek-status', [PesananController::class, 'cekStatus'])->name('pesanan.cekStatus');
Route::post('/pesanan/{kode_pesanan}/konfirmasi-bayar', [PesananController::class, 'konfirmasiBayar'])->name('pesanan.konfirmasiBayar');

// --- Midtrans (Sandbox) ---
Route::get('/pesanan/{kode_pesanan}/bayar-midtrans', [MidtransController::class, 'bayar'])->name('pesanan.bayarMidtrans');
Route::get('/pesanan/{kode_pesanan}/cek-status-midtrans', [MidtransController::class, 'cekStatus'])->name('pesanan.cekStatusMidtrans');
Route::post('/midtrans/notifikasi', [MidtransController::class, 'notifikasi'])->name('midtrans.notifikasi');

/*
|--------------------------------------------------------------------------
| LOGIN KASIR
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| AREA KASIR (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/pesanan/cari', [KasirPesananController::class, 'cari'])->name('pesanan.cari');
    Route::get('/pesanan/{pesanan}', [KasirPesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{pesanan}/bayar', [KasirPesananController::class, 'bayar'])->name('pesanan.bayar');
    Route::patch('/pesanan/{pesanan}/status', [KasirPesananController::class, 'updateStatus'])->name('pesanan.status');

    Route::resource('meja', MejaController::class)->except(['show']);
    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('menu', KasirMenuController::class)->except(['show']);

    Route::get('/pengaturan', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});
