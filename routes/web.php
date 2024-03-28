<?php

use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IkatanDinasController;
use App\Http\Controllers\JobsiteController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MutasiKaryawanController;
use App\Http\Controllers\MutasiSertifikasiController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\SertifikasiExpiredController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {

  // Dashboard
  Route::get('/', [DashboardController::class, 'index']);
  Route::get('sertifikasi3bulanexpired/export', [DashboardController::class, 'export']);

  // Karyawan
  Route::get('/karyawan/{nama_jobsite}/export', [KaryawanController::class, 'export']);
  Route::post('/karyawan/{nama_jobsite}/import', [KaryawanController::class, 'import']);
  Route::get('/karyawan/{nama_jobsite}', [KaryawanController::class, 'index']);
  Route::get('/karyawan/{nama_jobsite}/create', [KaryawanController::class, 'create']);
  Route::post('/karyawan/{nama_jobsite}/create', [KaryawanController::class, 'store']);
  Route::get('/karyawan/{nama_jobsite}/{karyawan}/edit', [KaryawanController::class, 'edit']);
  Route::put('/karyawan/{nama_jobsite}/{karyawan}', [KaryawanController::class, 'update']);
  Route::delete('/karyawan/{nama_jobsite}/{karyawan}', [KaryawanController::class, 'destroy']);
  Route::put('/karyawan/{nama_jobsite}/{karyawan}/ubah/status', [KaryawanController::class, 'ubahStatus']);

  // Sertifikasi
  Route::get('/sertifikasi/{nama_jobsite}/export', [SertifikasiController::class, 'export']);
  Route::post('/sertifikasi/{nama_jobsite}/import', [SertifikasiController::class, 'import']);
  Route::get('/sertifikasi/{nama_jobsite}', [SertifikasiController::class, 'index']);
  Route::get('/sertifikasi/{nama_jobsite}/create', [SertifikasiController::class, 'create']);
  Route::post('/sertifikasi/{nama_jobsite}', [SertifikasiController::class, 'store']);
  Route::get('/sertifikasi/{nama_jobsite}/{id}/edit', [SertifikasiController::class, 'edit']);
  Route::put('/sertifikasi/{nama_jobsite}/{sertifikasi}', [SertifikasiController::class, 'update']);
  Route::delete('/sertifikasi/{nama_jobsite}/{sertifikasi}', [SertifikasiController::class, 'destroy']);

  // Mutasi Karyawan
  Route::get('approve/mutasi_karyawan', [MutasiKaryawanController::class, 'permintaan']);
  Route::put('approve/mutasi_karyawan/{id}', [MutasiKaryawanController::class, 'approve']);
  Route::put('approve/reject/mutasi_karyawan/{id}', [MutasiKaryawanController::class, 'reject']);
  Route::resource('mutasi_karyawan', MutasiKaryawanController::class);
  Route::get('mutasi_karyawan/export/excel', [MutasiKaryawanController::class, 'export']);

  // Ikatan Dinas
  Route::get('/ikatan_dinas/export', [IkatanDinasController::class, 'export']);
  Route::get('/ikatan_dinas', [IkatanDinasController::class, 'index']);

  // Mutasi Sertifikasi
  Route::get('mutasi_sertifikasi', [MutasiSertifikasiController::class, 'index']);
  Route::delete('mutasi_sertifikasi/{id}', [MutasiSertifikasiController::class, 'destroy']);
  Route::get('mutasi_sertifikasi/export/excel', [MutasiSertifikasiController::class, 'export']);

  // Sertifikasi Expired
  Route::get('expired/sertifikasi', [SertifikasiExpiredController::class, 'index']);
  Route::get('expired/sertifikasi/export/excel', [SertifikasiExpiredController::class, 'export']);

  // Kompetensi
  Route::post('/komp/import/excel', [KompetensiController::class, 'import']);
  Route::get('/komp', [KompetensiController::class, 'index']);
  Route::get('/komp/create', [KompetensiController::class, 'create']);
  Route::post('/komp', [KompetensiController::class, 'store'])->name("storeKomp");
  Route::get('/komp/{kompetensi}/edit', [KompetensiController::class, 'edit']);
  Route::put('/komp/{kompetensi}', [KompetensiController::class, 'update']);
  Route::delete('/komp/{kompetensi}', [KompetensiController::class, 'destroy']);

  // Jobsite
  Route::resource('jobsite', JobsiteController::class);

  // User
  Route::resource('user', UserController::class);
  Route::put('user/{user}/ubah/level', [UserController::class, 'ubahLevel']);

  // Profile User
  Route::get('profile', [UserController::class, 'profile']);

  // Logout
  Route::get('/logout', [DashboardController::class, 'logout']);

  // AJAX untuk localhost
  Route::get('/get-karyawan/{nrp}', [KaryawanController::class, 'getKaryawan']);
  Route::get('/get-kompetensi/{nama}', [KompetensiController::class, 'getKompetensi']);
});


// Login - Auth
Route::middleware(['guest'])->group(function () {
  Route::get('/login', [DashboardController::class, 'login'])->name('login');
  Route::get('/login/admin', [DashboardController::class, 'loginAdmin'])->name('loginAdmin');
  Route::post('/login', [DashboardController::class, 'auth']);
  Route::post('/login/admin', [DashboardController::class, 'authAdmin']);
});
