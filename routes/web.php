<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/* ===================== ADMIN + USER ===================== */
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang Masuk
    Route::get('/barang-masuk', [InventoryController::class, 'barangMasuk'])->name('barang-masuk');
    Route::post('/barang-masuk', [InventoryController::class, 'storeBarangMasuk'])->name('barang-masuk.store');
    Route::put('/barang-masuk/{id}', [InventoryController::class, 'updateBarangMasuk'])->name('barang-masuk.update');
    Route::delete('/barang-masuk/{id}', [InventoryController::class, 'destroyBarangMasuk'])->name('barang-masuk.destroy');
    Route::post('/barang-masuk/{id}/restore', [InventoryController::class, 'restoreBarangMasuk'])->name('barang-masuk.restore');
    Route::delete('/barang-masuk/{id}/force-delete', [InventoryController::class, 'forceDeleteBarangMasuk'])->name('barang-masuk.force-delete');

    // Nota Pembeli
    Route::get('/nota-pembeli', [InventoryController::class, 'notaPembeli'])->name('nota-pembeli.index');
    Route::post('/nota-pembeli', [InventoryController::class, 'storeNotaPembeli'])->name('nota-pembeli.store');
    Route::delete('/nota-pembeli/{id}', [InventoryController::class, 'destroyNotaPembeli'])->name('nota-pembeli.destroy');
    Route::get('/nota-pembeli/{id}/cetak', [InventoryController::class, 'cetak'])->name('nota-pembeli.cetak');
    Route::post('/nota-pembeli/{id}/restore', [InventoryController::class, 'restoreNotaPembeli'])->name('nota-pembeli.restore');
    Route::delete('/nota-pembeli/{id}/force-delete', [InventoryController::class, 'forceDeleteNotaPembeli'])->name('nota-pembeli.force-delete');

    // Sampah (isinya difilter di controller sesuai role)
    Route::get('/sampah', [InventoryController::class, 'sampah'])->name('buffer-stok.trash');
});

/* ===================== ADMIN SAJA ===================== */
Route::middleware(['auth', 'admin'])->group(function () {

    // Stok Barang
    Route::get('/stok', [InventoryController::class, 'stok'])->name('stok');
    Route::get('/stok/{id}', [InventoryController::class, 'show'])->name('barang.show');
    Route::put('/stok/{id}', [InventoryController::class, 'barangupdate'])->name('barang.update');
    Route::post('/barang/store', [InventoryController::class, 'barangstore'])->name('barang.store');
    Route::post('/stok-barang/{id}/restore', [InventoryController::class, 'restore'])->name('buffer-stok.restore');
    Route::delete('/stok-barang/{id}/force', [InventoryController::class, 'forceDelete'])->name('buffer-stok.force-delete');

    // Stok Opname
    Route::post('/stok/opname', [InventoryController::class, 'storeOpname'])->name('opname.store');
    Route::delete('/stok/opname/{id}', [InventoryController::class, 'destroyOpname'])->name('opname.destroy');
    Route::delete('/stok/opname/{id}/force-delete', [InventoryController::class, 'forceDeleteOpname'])->name('opname.force-delete');
    Route::post('/stok/opname/{id}/restore', [InventoryController::class, 'restoreOpname'])->name('opname.restore');

    // Buffer Stock
    Route::get('/buffer', [InventoryController::class, 'index'])->name('buffer.index');
    Route::get('/buffer/create', [InventoryController::class, 'create'])->name('buffer.create');
    Route::get('/buffer/{id}/edit', [InventoryController::class, 'edit'])->name('buffer.edit');
    Route::delete('/buffer/{id}', [InventoryController::class, 'destroy'])->name('buffer.destroy');
    Route::post('/buffer/update-minimum', [InventoryController::class, 'updateStokMinimum'])->name('buffer.update.minimum');
    Route::post('/buffer/apply-all', [InventoryController::class, 'applyBufferMethodAll'])->name('buffer.apply.all');

    // Barang Keluar (menunya sudah di-comment di sidebar)
    Route::get('/barang-keluar', [InventoryController::class, 'barangKeluar'])->name('barang-keluar');
    Route::get('/barang-keluar/create', [InventoryController::class, 'barangkeluarcreate'])->name('barang-keluar.create');
    Route::post('/barang-keluar', [InventoryController::class, 'barangkeluarstore'])->name('barang-keluar.store');
    Route::delete('/barang-keluar/{id}', [InventoryController::class, 'barangkeluardestroy'])->name('barang-keluar.destroy');

    // Manajemen Akun
    Route::get('/manajemen', [InventoryController::class, 'manajemen'])->name('manajemen');
    Route::put('/admin/users/{id}', [InventoryController::class, 'manajemenUpdate'])->name('manajemenupdate');
});

require __DIR__.'/auth.php';