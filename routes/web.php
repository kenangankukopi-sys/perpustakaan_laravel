<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;
use App\Http\Controllers\DataController;

Route::get('/login', [MyController::class, 'showLogin'])->name('login');
Route::post('/login', [MyController::class, 'login']);
Route::get('/logout', [MyController::class, 'logout'])->name('logout');
Route::get('/register', [MyController::class, 'showRegister'])->name('register');
Route::post('/register', [MyController::class, 'registerPost'])->name('register.post');


Route::get('/home', [MyController::class, 'home']);
Route::get('/databuku', [MyController::class, 'books']);
Route::get('/api/databuku', [DataController::class, 'books']);

Route::post('/databuku/store', [MyController::class, 'simpant']);
Route::post('/databuku/update/{id}', [MyController::class, 'simpane']);
Route::get('/databuku/delete/{id}', [MyController::class, 'delete']);

Route::get('/datamasuk', [MyController::class, 'dataMasukBuku']);
Route::get('/api/datamasuk', [MyController::class, 'dataMasukBuku']);

Route::post('/datamasuk/store', [MyController::class, 'simpanDataMasuk']);
Route::put('/datamasuk/update/{id}', [MyController::class, 'updateDataMasuk']);
Route::get('/datamasuk/hapus/{id}', [MyController::class, 'hapusDataMasuk']);

Route::get('/peminjaman', [MyController::class, 'peminjaman']);
Route::post('/peminjaman/store', [MyController::class, 'simpanPeminjaman']);
Route::post('/peminjaman/update', [MyController::class, 'updatePeminjaman']);
Route::get('/peminjaman/hapus/{id}', [MyController::class, 'hapusPeminjaman']);
Route::get('/peminjaman/kembalikan/{id}', [MyController::class, 'kembalikanPeminjaman']);

Route::get('/datauser', [MyController::class, 'dataUser']);
Route::post('/datauser/store', [MyController::class, 'storeUsers']);
Route::post('/datauser/update/{id}', [MyController::class, 'updateUser']);
Route::get('/datauser/delete/{id}', [MyController::class, 'destroy']);
Route::get('/datauser/reset/{id}', [MyController::class, 'resetPassword']);
Route::get('/formfilter', [MyController::class, 'laporan']);


// ------------------- laporan masuk untuk buku peminjaman -----------------
Route::get('/laporanpeminjaman', [MyController::class, 'laporanPeminjaman']);
Route::get('/laporanpeminjaman/print', [MyController::class, 'printPeminjaman']);
Route::get('/laporanpeminjaman/pdf', [MyController::class, 'pdfPeminjaman']);
Route::get('/laporanpeminjaman/excel', [MyController::class, 'excelPeminjaman']);

// ------------------- laporan masuk untuk buku masuk ----------------------
Route::get('/laporanmasuk', [MyController::class, 'laporanMasuk']);
Route::get('/laporanmasuk/print', [MyController::class, 'printMasuk']);
Route::get('/laporanmasuk/pdf', [MyController::class, 'pdfMasuk']);
Route::get('/laporanmasuk/excel', [MyController::class, 'excelMasuk']);


// ------------------- KOLEKSI & RIWAYAT (untuk anggota) -------------------
Route::get('/koleksi', [MyController::class, 'koleksiBuku']);
Route::get('/riwayat', [MyController::class, 'riwayatPeminjaman']);
Route::post('/peminjaman/pinjam/{id}', [MyController::class, 'pinjamBuku']);
Route::post('/peminjaman/kembalikan/{id}', [MyController::class, 'kembalikanBukuAnggota']);