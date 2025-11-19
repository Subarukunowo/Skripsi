<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormasiController;

Route::get('/', function () {
    return view('beranda');
});

// route untuk upload dan klasifikasi
Route::post('/api/classify', [FormasiController::class, 'classify']);
Route::post('/analyze', [FormasiController::class, 'analyze'])->name('formation.analyze');
Route::get('/formation', fn() => view('formation')); // halaman utama
Route::post('/download-pdf', [FormasiController::class, 'downloadPDF'])->name('formation.download.pdf');
Route::post('/download-excel', [FormasiController::class, 'downloadExcel'])->name('formation.download.excel');