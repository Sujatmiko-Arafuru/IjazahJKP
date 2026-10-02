<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\IjazahController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('public.welcome');
})->name('public.home');

Route::get('/pendataan-alumni', [AlumniController::class, 'showRegister'])->name('public.register');
Route::post('/pendataan-alumni', [AlumniController::class, 'register'])->name('public.register.store');
Route::get('/pendataan-alumni/sukses/{id}', [AlumniController::class, 'showSuccess'])->name('public.register.sukses');

Route::get('/cek-ijazah', function () {
    return redirect()->route('public.pengembalian');
})->name('public.cek_ijazah');

// Menu Pengembalian Toga & Verifikasi Kelulusan (User Mahasiswa)
Route::get('/pengembalian', [PengembalianController::class, 'showMahasiswaForm'])->name('public.pengembalian');
Route::post('/pengembalian/upload', [PengembalianController::class, 'uploadMahasiswaItem'])->name('public.pengembalian.upload');
Route::get('/pengembalian/file/{alumniId}/{itemKey}', [PengembalianController::class, 'viewFile'])->name('pengembalian.view');

// Admin Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('login.store');
});

// Admin Authenticated Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Alumni management (Verifikasi Berkas Persyaratan Mahasiswa)
    Route::get('/alumni', [AlumniController::class, 'adminIndex'])->name('alumni.index');
    Route::get('/alumni/export', [AlumniController::class, 'exportExcel'])->name('alumni.export');
    Route::get('/alumni/{alumni}', [AlumniController::class, 'adminShow'])->name('alumni.show');
    Route::post('/alumni/{alumni}/verify', [AlumniController::class, 'adminVerify'])->name('alumni.verify');
    
    // Ijazah management
    Route::get('/ijazah', [IjazahController::class, 'adminIndex'])->name('ijazah.index');
    Route::get('/ijazah/{ijazah}/edit', [IjazahController::class, 'adminEdit'])->name('ijazah.edit');
    Route::put('/ijazah/{ijazah}', [IjazahController::class, 'adminUpdate'])->name('ijazah.update');

    // Pengembalian management (Pengambilan Dokumen Kelulusan)
    Route::get('/pengembalian/wajib', function() { return redirect()->route('admin.alumni.index'); })->name('pengembalian.wajib');
    Route::get('/pengembalian/dokumen', [PengembalianController::class, 'adminDokumen'])->name('pengembalian.dokumen');
    Route::get('/pengembalian/dokumen/{alumni}', [PengembalianController::class, 'adminShowDokumen'])->name('pengembalian.show');
    Route::post('/pengembalian/{alumniId}/verify', [PengembalianController::class, 'adminVerifyItem'])->name('pengembalian.verify');
    
    // Document secure viewer (streams files inline)
    Route::get('/alumni/{id}/dokumen/{type}', [DokumenController::class, 'viewFile'])->name('dokumen.view');
});
