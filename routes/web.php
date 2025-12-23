<?php

use App\Http\Controllers\FeasibilityStudyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HtaDanGpaController;
use App\Http\Controllers\HtaGpaController;
use App\Http\Controllers\LembarDisposisiController;
use App\Http\Controllers\MasterApprovalController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\MasterDepartemenController;
use App\Http\Controllers\MasterFormController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterJenisPengajuanController;
use App\Http\Controllers\MasterMerkController;
use App\Http\Controllers\MasterParameterController;
use App\Http\Controllers\MasterPerusahaanController;
use App\Http\Controllers\MasterSatuanController;
use App\Http\Controllers\MasterVendorController;
use App\Http\Controllers\PengajuanPembelianController;
use App\Http\Controllers\PermintaanPembelianController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsulanInventasiController;
use App\Http\Controllers\UsulanInvestasiController;
use App\Models\PengajuanPembelian;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/approval/hta-gpa/{token}/approve', [HtaDanGpaController::class, 'approve'])->name('htagpa.approve');
Route::get('/approval/hta-gpa/{token}/reject', [HtaDanGpaController::class, 'reject'])->name('htagpa.reject');
Route::get('/approval/usulan-investasi/{token}/approve', [UsulanInvestasiController::class, 'approve'])->name('usulan-investasi.approve');
Route::get('/approval/usulan-investasi/{token}/reject', [UsulanInvestasiController::class, 'reject'])->name('usulan-investasi.reject');
Route::get('/approval/fisibility-studi/{token}/approve', [FeasibilityStudyController::class, 'approve'])->name('fs.approve');
Route::get('/approval/fisibility-studi/{token}/reject', [FeasibilityStudyController::class, 'reject'])->name('fs.reject');
Route::get('/validasi-approval/{id}', [MasterApprovalController::class, 'validasi'])
    ->name('approval.validasi');
Route::get('/approval/lembar-disposisi/{token}/approve', [LembarDisposisiController::class, 'approve'])->name('lembar-disposisi.approve');
Route::get('/approval/lembar-disposisi/{token}/reject', [LembarDisposisiController::class, 'reject'])->name('lembar-disposisi.reject');
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::prefix('users')->group(function () {
        Route::get('/getDd', [MasterPerusahaanController::class, 'index'])->name('perusahaan.index');
    });
    Route::prefix('master/perusahaan')->group(function () {
        Route::get('/', [MasterPerusahaanController::class, 'index'])->name('perusahaan.index');
        Route::get('/create', [MasterPerusahaanController::class, 'create'])->name('perusahaan.create');
        Route::post('/store', [MasterPerusahaanController::class, 'store'])->name('perusahaan.store');
        Route::get('/edit/{id}', [MasterPerusahaanController::class, 'edit'])->name('perusahaan.edit');
        Route::put('/update/{id}', [MasterPerusahaanController::class, 'update'])->name('perusahaan.update');
        Route::get('/show/{id}', [MasterPerusahaanController::class, 'show'])->name('perusahaan.show');
        Route::delete('/delete/{id}', [MasterPerusahaanController::class, 'destroy'])->name('perusahaan.destroy');
    });
    Route::prefix('master/merk')->group(function () {
        Route::get('/', [MasterMerkController::class, 'index'])->name('merk.index');
        Route::get('/create', [MasterMerkController::class, 'create'])->name('merk.create');
        Route::post('/store', [MasterMerkController::class, 'store'])->name('merk.store');
        Route::get('/edit/{id}', [MasterMerkController::class, 'edit'])->name('merk.edit');
        Route::put('/update/{id}', [MasterMerkController::class, 'update'])->name('merk.update');
        Route::get('/show/{id}', [MasterMerkController::class, 'show'])->name('merk.show');
        Route::delete('/delete/{id}', [MasterMerkController::class, 'destroy'])->name('merk.destroy');
    });
    Route::prefix('master/barang')->group(function () {
        Route::get('/', [MasterBarangController::class, 'index'])->name('barang.index');
        Route::get('/create', [MasterBarangController::class, 'create'])->name('barang.create');
        Route::post('/store', [MasterBarangController::class, 'store'])->name('barang.store');
        Route::get('/edit/{id}', [MasterBarangController::class, 'edit'])->name('barang.edit');
        Route::put('/update/{id}', [MasterBarangController::class, 'update'])->name('barang.update');
        Route::get('/show/{id}', [MasterBarangController::class, 'show'])->name('barang.show');
        Route::delete('/delete/{id}', [MasterBarangController::class, 'destroy'])->name('barang.destroy');
    });
    Route::prefix('master/vendor')->group(function () {
        Route::get('/', [MasterVendorController::class, 'index'])->name('vendor.index');
        Route::get('/create', [MasterVendorController::class, 'create'])->name('vendor.create');
        Route::post('/store', [MasterVendorController::class, 'store'])->name('vendor.store');
        Route::get('/edit/{id}', [MasterVendorController::class, 'edit'])->name('vendor.edit');
        Route::put('/update/{id}', [MasterVendorController::class, 'update'])->name('vendor.update');
        Route::get('/show/{id}', [MasterVendorController::class, 'show'])->name('vendor.show');
        Route::delete('/delete/{id}', [MasterVendorController::class, 'destroy'])->name('vendor.destroy');
    });
    Route::prefix('master/departemen')->group(function () {
        Route::get('/', [MasterDepartemenController::class, 'index'])->name('departemen.index');
        Route::get('/create', [MasterDepartemenController::class, 'create'])->name('departemen.create');
        Route::post('/store', [MasterDepartemenController::class, 'store'])->name('departemen.store');
        Route::post('/sinkron-data-departemen', [MasterDepartemenController::class, 'sinkron'])->name('departemen.sinkron');
        Route::get('/edit/{id}', [MasterDepartemenController::class, 'edit'])->name('departemen.edit');
        Route::put('/update/{id}', [MasterDepartemenController::class, 'update'])->name('departemen.update');
        Route::get('/show/{id}', [MasterDepartemenController::class, 'show'])->name('departemen.show');
        Route::delete('/delete/{id}', [MasterDepartemenController::class, 'destroy'])->name('departemen.destroy');
    });
    Route::prefix('permintaan-pembelian')->group(function () {
        Route::get('/', [PermintaanPembelianController::class, 'index'])->name('pp.index');
        Route::get('/create', [PermintaanPembelianController::class, 'create'])->name('pp.create');
        Route::post('/store', [PermintaanPembelianController::class, 'store'])->name('pp.store');
        Route::get('/edit/{id}', [PermintaanPembelianController::class, 'edit'])->name('pp.edit');
        Route::put('/update/{id}', [PermintaanPembelianController::class, 'update'])->name('pp.update');
        Route::get('/show/{id}', [PermintaanPembelianController::class, 'show'])->name('pp.show');
        Route::get('/print/{id}', [PermintaanPembelianController::class, 'print'])->name('pp.print');
        Route::delete('/delete/{id}', [PermintaanPembelianController::class, 'destroy'])->name('pp.destroy');
        Route::post('/approve', [PermintaanPembelianController::class, 'approve'])->name('pp.approve');

        // Tambahan route untuk ACC
        Route::post('/acc-kepala-divisi/{id}', [PermintaanPembelianController::class, 'accKepalaDivisi'])->name('pp.acc-kepala-divisi');
        Route::post('/acc-kepala-divisi-penunjang-medis/{id}', [PermintaanPembelianController::class, 'accKepalaDivisiPenunjang'])->name('pp.acc-kepala-divisi-penunjang-medis');
        Route::post('/acc-direktur/{id}', [PermintaanPembelianController::class, 'accDirektur'])->name('pp.acc-direktur');
        Route::post('/acc-smi/{id}', [PermintaanPembelianController::class, 'accSmi'])->name('pp.acc-smi');
    });
    Route::prefix('ajukan-pembelian')->group(function () {
        Route::get('/', [PengajuanPembelianController::class, 'index'])->name('ajukan.index');
        Route::get('/ajukan-pembelian/{id}', [PengajuanPembelianController::class, 'create'])->name('ajukan.create');
        Route::get('/simpan-draft/{id}', [PengajuanPembelianController::class, 'SimpanDraft'])->name('ajukan.buat-draft');
        Route::post('/store', [PengajuanPembelianController::class, 'store'])->name('ajukan.store');
        Route::get('/edit/{id}', [PengajuanPembelianController::class, 'edit'])->name('ajukan.edit');
        Route::put('/update/{id}', [PengajuanPembelianController::class, 'update'])->name('ajukan.update');
        Route::post('/update-status-pengajuan/{id}', [PengajuanPembelianController::class, 'UpdatePengajuan'])->name('ajukan.update-status');
        Route::get('/show/{id}', [PengajuanPembelianController::class, 'show'])->name('ajukan.show');
        Route::delete('/delete/{id}', [PengajuanPembelianController::class, 'destroy'])->name('ajukan.destroy');
    });
    Route::prefix('master/satuan')->group(function () {
        Route::get('/', [MasterSatuanController::class, 'index'])->name('satuan.index');
        Route::get('/create', [MasterSatuanController::class, 'create'])->name('satuan.create');
        Route::post('/store', [MasterSatuanController::class, 'store'])->name('satuan.store');
        Route::get('/edit/{id}', [MasterSatuanController::class, 'edit'])->name('satuan.edit');
        Route::put('/update/{id}', [MasterSatuanController::class, 'update'])->name('satuan.update');
        Route::get('/show/{id}', [MasterSatuanController::class, 'show'])->name('satuan.show');
        Route::delete('/delete/{id}', [MasterSatuanController::class, 'destroy'])->name('satuan.destroy');
    });
    Route::prefix('master/jabatan')->group(function () {
        Route::get('/', [MasterJabatanController::class, 'index'])->name('jabatan.index');
        Route::get('/create', [MasterJabatanController::class, 'create'])->name('jabatan.create');
        Route::post('/store', [MasterJabatanController::class, 'store'])->name('jabatan.store');
        Route::get('/edit/{id}', [MasterJabatanController::class, 'edit'])->name('jabatan.edit');
        Route::put('/update/{id}', [MasterJabatanController::class, 'update'])->name('jabatan.update');
        Route::get('/show/{id}', [MasterJabatanController::class, 'show'])->name('jabatan.show');
        Route::delete('/delete/{id}', [MasterJabatanController::class, 'destroy'])->name('jabatan.destroy');
    });
    Route::prefix('master/parameter')->group(function () {
        Route::get('/', [MasterParameterController::class, 'index'])->name('parameter.index');
        Route::get('/create', [MasterParameterController::class, 'create'])->name('parameter.create');
        Route::post('/store', [MasterParameterController::class, 'store'])->name('parameter.store');
        Route::get('/edit/{id}', [MasterParameterController::class, 'edit'])->name('parameter.edit');
        Route::put('/update/{id}', [MasterParameterController::class, 'update'])->name('parameter.update');
        Route::get('/show/{id}', [MasterParameterController::class, 'show'])->name('parameter.show');
        Route::delete('/delete/{id}', [MasterParameterController::class, 'destroy'])->name('parameter.destroy');
    });
    Route::prefix('master/form')->group(function () {
        Route::get('/', [MasterFormController::class, 'index'])->name('nama-form.index');
        Route::get('/create', [MasterFormController::class, 'create'])->name('nama-form.create');
        Route::post('/store', [MasterFormController::class, 'store'])->name('nama-form.store');
        Route::get('/edit/{id}', [MasterFormController::class, 'edit'])->name('nama-form.edit');
        Route::put('/update/{id}', [MasterFormController::class, 'update'])->name('nama-form.update');
        Route::get('/show/{id}', [MasterFormController::class, 'show'])->name('nama-form.show');
        Route::delete('/delete/{id}', [MasterFormController::class, 'destroy'])->name('nama-form.destroy');
    });
    Route::prefix('master/jenis-pengajuan')->group(function () {
        Route::get('/', [MasterJenisPengajuanController::class, 'index'])->name('jenis-pengajuan.index');
        Route::get('/create', [MasterJenisPengajuanController::class, 'create'])->name('jenis-pengajuan.create');
        Route::post('/store', [MasterJenisPengajuanController::class, 'store'])->name('jenis-pengajuan.store');
        Route::get('/edit/{id}', [MasterJenisPengajuanController::class, 'edit'])->name('jenis-pengajuan.edit');
        Route::put('/update/{id}', [MasterJenisPengajuanController::class, 'update'])->name('jenis-pengajuan.update');
        Route::get('/show/{id}', [MasterJenisPengajuanController::class, 'show'])->name('jenis-pengajuan.show');
        Route::delete('/delete/{id}', [MasterJenisPengajuanController::class, 'destroy'])->name('jenis-pengajuan.destroy');
    });
    Route::prefix('master/pengaturan-approval')->group(function () {
        Route::get('/', [MasterApprovalController::class, 'index'])->name('master-approval.index');
        Route::get('/create/{id}', [MasterApprovalController::class, 'create'])->name('master-approval.create');
        Route::post('/store', [MasterApprovalController::class, 'store'])->name('master-approval.store');
        Route::get('/edit/{id}', [MasterApprovalController::class, 'edit'])->name('master-approval.edit');
        Route::put('/update/{id}', [MasterApprovalController::class, 'update'])->name('master-approval.update');
        Route::get('/show/{id}', [MasterApprovalController::class, 'show'])->name('master-approval.show');
        Route::delete('/delete/{id}', [MasterApprovalController::class, 'destroy'])->name('master-approval.destroy');
        Route::get('/ttd/{id}/{KodePerusahaan}', [MasterApprovalController::class, 'aturTtd'])->name('master-approval.ttd');
    });
    Route::prefix('rekomendasi')->group(function () {
        Route::get('/', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
        Route::get('/rekomendasi-pembelian/{idPengajuan}/{idPengajuanItem}', [RekomendasiController::class, 'create'])->name('rekomendasi.create');
        Route::get('/review-pembelian/{IdPengajuan}/{barang}', [RekomendasiController::class, 'Review'])->name('rekomendasi.review');
        Route::get('/simpan-draft/{id}', [RekomendasiController::class, 'SimpanDraft'])->name('rekomendasi.buat-draft');
        Route::post('/store', [RekomendasiController::class, 'store'])->name('rekomendasi.store');
        Route::get('/edit/{id}', [RekomendasiController::class, 'edit'])->name('rekomendasi.edit');
        Route::get('/show/{id}', [RekomendasiController::class, 'show'])->name('rekomendasi.show');
        Route::delete('/delete/{id}', [RekomendasiController::class, 'destroy'])->name('rekomendasi.destroy');
        Route::get('/cetak-review/{IdPengajuan}/{barang}', [RekomendasiController::class, 'Cetak'])->name('rekomendasi.detail-print');
        Route::get('/lihat-review/{IdPengajuan}/{barang}', [RekomendasiController::class, 'detail'])->name('rekomendasi.detail-view');
        Route::post('/setujui-rekomendasi', [RekomendasiController::class, 'Approval'])->name('rekomendasi.setujui-rekomendasi');
        Route::POST('/update-rekomendasi', [RekomendasiController::class, 'UpdateRekomendasi'])->name('rekomendasi.update-rekomendasi');
    });
    Route::prefix('usulan-investasi')->group(function () {
        Route::get('/', [UsulanInvestasiController::class, 'index'])->name('usulan-investasi.index');
        Route::get('/create/{IdPengajuan}/{barang}', [UsulanInvestasiController::class, 'create'])->name('usulan-investasi.create');
        Route::post('/store', [UsulanInvestasiController::class, 'store'])->name('usulan-investasi.store');
        Route::get('/edit/{id}', [UsulanInvestasiController::class, 'edit'])->name('usulan-investasi.edit');
        Route::put('/update/{id}', [UsulanInvestasiController::class, 'update'])->name('usulan-investasi.update');
        Route::get('/show/{IdPengajuan}/{barang}', [UsulanInvestasiController::class, 'show'])->name('usulan-investasi.show');
        Route::get('/print/{IdPengajuan}/{barang}', [UsulanInvestasiController::class, 'print'])->name('usulan-investasi.print');
        Route::delete('/delete/{id}', [UsulanInvestasiController::class, 'destroy'])->name('usulan-investasi.destroy');
        Route::get('/cetak/{id}', [UsulanInvestasiController::class, 'cetak'])->name('usulan-investasi.cetak');

        Route::post('/setujui-kadiv/{id}', [UsulanInvestasiController::class, 'approveKadiv'])->name('rekomendasi.setujui-kadiv');
        Route::post('/setujui-direktur/{id}', [UsulanInvestasiController::class, 'approveDirektur'])->name('rekomendasi.setujui-direktur');
    });
    Route::prefix('form-hta-atau-gpa')->group(function () {
        Route::get('/hta/{idPengajuan}/{idPengajuanItem}', [HtaDanGpaController::class, 'index'])->name('htagpa.form-hta');
        Route::post('/simpan', [HtaDanGpaController::class, 'store'])->name('htagpa.store');
        Route::post('/simpan-umum', [HtaDanGpaController::class, 'storeUmum'])->name('htagpa.store-umum');
        Route::post('/ajukan-hta', [HtaDanGpaController::class, 'ajukan'])->name('htagpa.ajukan');
        Route::get('/show/{idPengajuan}/{idPengajuanItem}', [HtaDanGpaController::class, 'show'])->name('htagpa.show');
        Route::get('/print/{idPengajuan}/{idPengajuanItem}', [HtaDanGpaController::class, 'print'])->name('htagpa.print');

        // input penilai
        Route::POST('/simpan-penilai', [HtaDanGpaController::class, 'SimpanPenilai'])->name('htagpa.simpan-penilai');
        Route::post('/acc-penilai1/{id}', [HtaDanGpaController::class, 'accPenilai1'])->name('htagpa.acc-penilai1');
        Route::post('/acc-penilai2/{id}', [HtaDanGpaController::class, 'accPenilai2'])->name('htagpa.acc-penilai2');
        Route::post('/acc-penilai3/{id}', [HtaDanGpaController::class, 'accPenilai3'])->name('htagpa.acc-penilai3');
        Route::post('/acc-penilai4/{id}', [HtaDanGpaController::class, 'accPenilai4'])->name('htagpa.acc-penilai4');
        Route::post('/acc-penilai5/{id}', [HtaDanGpaController::class, 'accPenilai5'])->name('htagpa.acc-penilai5');
    });
    Route::prefix('lembar-disposisi')->group(function () {
        Route::get('/', [LembarDisposisiController::class, 'index'])->name('lembar-disposisi.index');
        Route::get('/create/{idPengajuan}/{idPengajuanItem}', [LembarDisposisiController::class, 'create'])->name('lembar-disposisi.create');
        Route::post('/store', [LembarDisposisiController::class, 'store'])->name('lembar-disposisi.store');
        Route::get('/edit/{id}', [LembarDisposisiController::class, 'edit'])->name('lembar-disposisi.edit');
        Route::put('/update/{id}', [LembarDisposisiController::class, 'update'])->name('lembar-disposisi.update');
        Route::get('/show/{idPengajuan}/{idPengajuanItem}', [LembarDisposisiController::class, 'show'])->name('lembar-disposisi.show');
        Route::delete('/delete/{id}', [LembarDisposisiController::class, 'destroy'])->name('lembar-disposisi.destroy');
        Route::get('/print/{idPengajuan}/{idPengajuanItem}', [LembarDisposisiController::class, 'print'])->name('lembar-disposisi.print');
    });
    Route::prefix('fs')->group(function () {
        Route::get('/', [FeasibilityStudyController::class, 'index'])->name('fs.index');
        Route::get('/create/{idPengajuan}/{idPengajuanItem}', [FeasibilityStudyController::class, 'create'])->name('fs.create');
        Route::post('/store', [FeasibilityStudyController::class, 'store'])->name('fs.store');
        Route::get('/edit/{id}', [FeasibilityStudyController::class, 'edit'])->name('fs.edit');
        Route::put('/update/{id}', [FeasibilityStudyController::class, 'update'])->name('fs.update');
        Route::get('/show/{idPengajuan}/{idPengajuanItem}', [FeasibilityStudyController::class, 'show'])->name('fs.show');
        Route::get('/cetak/{idPengajuan}/{idPengajuanItem}', [FeasibilityStudyController::class, 'cetak'])->name('fs.cetak');
        Route::delete('/delete/{id}', [FeasibilityStudyController::class, 'destroy'])->name('fs.destroy');
        Route::get('/approval/feasibility-study/{token}/approve', [FeasibilityStudyController::class, 'approve'])->name('fs.approve');
    });
});
