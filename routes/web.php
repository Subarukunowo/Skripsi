<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormasiController;

/*
|--------------------------------------------------------------------------
| ROUTES APLIKASI UTAMA
|--------------------------------------------------------------------------
*/

// Halaman Beranda
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

// --- Navigasi Halaman Statis ---
// Tambahkan route untuk halaman 'Tentang'
Route::get('/about', function () {
    return view('about'); 
})->name('about');

// Tambahkan route untuk halaman 'Pengaturan'
// Pastikan URI adalah '/settings' dan view adalah 'settings'
Route::get('/settings', function () {
    return view('settings'); // <--- PASTIKAN INI ADALAH 'settings' (dengan 's')
})->name('settings'); // <-- Direkomendasikan name juga menggunakan 'settings'


/*
|--------------------------------------------------------------------------
| ROUTES KLASIFIKASI & API
|--------------------------------------------------------------------------
*/

// API untuk upload dan klasifikasi (Digunakan dalam kode JavaScript)
Route::post('/analyze', [FormasiController::class, 'analyze'])->name('formation.analyze');

// Endpoint API yang mungkin tidak digunakan di frontend saat ini, tetapi didefinisikan
Route::post('/api/classify', [FormasiController::class, 'classify']); 


/*
|--------------------------------------------------------------------------
| ROUTES UNDUH LAPORAN
|--------------------------------------------------------------------------
*/

Route::post('/download-pdf', [FormasiController::class, 'downloadPDF'])->name('formation.download.pdf');
Route::post('/download-excel', [FormasiController::class, 'downloadExcel'])->name('formation.download.excel');

// Catatan: Route '/formation' tampaknya duplikat fungsi beranda atau sudah tidak relevan
// Route::get('/formation', fn() => view('formation'));