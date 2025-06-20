<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Auth::routes();

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::post('/impersonate/{codeUser}', [UserController::class, 'impersonate'])->name('impersonate');
Route::get('/loginKembali', [UserController::class, 'stopImpersonate'])->name('impersonate.stop');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/kepegawaian/cuti', [App\Http\Controllers\CutiPegawaiController::class, 'index'])->name('cuti.list');
Route::get('/kepegawaian/cuti/modal/pengajuan-cuti', [App\Http\Controllers\CutiPegawaiController::class, 'create'])->name('cuti.create');
Route::post('/kepegawaian/cuti/pengajuanCuti', [App\Http\Controllers\CutiPegawaiController::class, 'store'])->name('cuti.store');
Route::get('/kepegawaian/cuti/modal/ubah-pengajuan-cuti/{cutiCode}', [App\Http\Controllers\CutiPegawaiController::class, 'edit'])->name('cuti.edit');
Route::put('/kepegawaian/cuti/ubahPengajuanCuti/{cutiCode}', [App\Http\Controllers\CutiPegawaiController::class, 'update'])->name('cuti.update');
Route::delete('/kepegawaian/cuti/hapusPengajuanCuti/{cutiCode}', [App\Http\Controllers\CutiPegawaiController::class, 'destroy']);





Route::get('/kepegawaian/jabatan', [App\Http\Controllers\JabatanController::class, 'index'])->name('jabatan.list');
Route::get('/kepegawaian/jabatan/modal/tambah-jabatan', [App\Http\Controllers\JabatanController::class, 'create'])->name('jabatan.create');
Route::post('/kepegawaian/jabatan/tambahJabatan', [App\Http\Controllers\JabatanController::class, 'store'])->name('jabatan.store');
Route::get('/kepegawaian/jabatan/modal/ubah-jabatan/{jabatanCode}', [App\Http\Controllers\JabatanController::class, 'edit'])->name('jabatan.edit');
Route::put('/kepegawaian/jabatan/ubahJabatan/{jabatanCode}', [App\Http\Controllers\JabatanController::class, 'update'])->name('jabatan.update');
Route::delete('/kepegawaian/jabatan/hapusJabatan/{jabatanCode}', [App\Http\Controllers\JabatanController::class, 'destroy']);

Route::get('/kepegawaian/pegawai', [App\Http\Controllers\PegawaiController::class, 'index'])->name('pegawai.list');
Route::get('/kepegawaian/pegawai/modal/tambah-pegawai', [App\Http\Controllers\PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/kepegawaian/pegawai/tambahPegawai', [App\Http\Controllers\PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/kepegawaian/pegawai/modal/ubah-pegawai/{codePegawai}', [App\Http\Controllers\PegawaiController::class, 'edit'])->name('pegawai.edit');
Route::put('/kepegawaian/pegawai/ubahPegawai/{codePegawai}', [App\Http\Controllers\PegawaiController::class, 'update'])->name('pegawai.update');
Route::delete('/kepegawaian/pegawai/hapusPegawai/{codePegawai}', [App\Http\Controllers\PegawaiController::class, 'destroy']);
Route::get('/kepegawaian/pegawai/roles-pegawai/{codePegawai}', [App\Http\Controllers\PegawaiController::class, 'roles'])->name('pegawai.roles');
Route::put('/kepegawaian/pegawai/rolesPegawai/{codePegawai}', [App\Http\Controllers\PegawaiController::class, 'rolesUpdate'])->name('pegawai.rolesnew');

Route::get('/pengaturan/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.list');
Route::get('/pengaturan/roles/modal/tambah-roles', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
Route::post('/pengaturan/roles/tambahRoles', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
Route::get('/pengaturan/roles/modal/ubah-roles/{roleCode}', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
Route::put('/pengaturan/roles/ubahRoles/{roleCode}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');

Route::get('/pengaturan/permissions', [App\Http\Controllers\PermissionsController::class, 'index'])->name('permissions.list');
Route::get('/pengaturan/permissions/modal/tambah-permissions', [App\Http\Controllers\PermissionsController::class, 'create'])->name('permissions.create');
Route::post('/pengaturan/permissions/tambahPermissions', [App\Http\Controllers\PermissionsController::class, 'store'])->name('permissions.store');
Route::get('/pengaturan/permissions/modal/ubah-permissions/{permissionscode}', [App\Http\Controllers\PermissionsController::class, 'edit'])->name('permissions.edit');
Route::put('/pengaturan/permissions/updatePermissions/{permissionscode}', [App\Http\Controllers\PermissionsController::class, 'update'])->name('permissions.update');
Route::delete('/pengaturan/permissions/deletePermissions/{permissionscode}', [App\Http\Controllers\PermissionsController::class, 'destroy']);
