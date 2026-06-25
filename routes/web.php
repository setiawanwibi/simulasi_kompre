<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\JenisBBMController;
use App\Http\Controllers\Admin\LaporanBBMController;
use App\Http\Controllers\Admin\PermohonanController as AdminPermohonanController;
use App\Http\Controllers\Driver\PermohonanController;
use App\Http\Controllers\Driver\LaporanBBMController as DriverLaporan;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth','admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class,'index'])
            ->name('dashboard');
            
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile');

        Route::post('/profile/update', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::resource('akun', UserController::class);

        Route::resource('kendaraan', KendaraanController::class);

        Route::resource('jenis-bbm', JenisBBMController::class);

        Route::get('laporan-bbm', [LaporanBBMController::class, 'index'])
            ->name('laporan-bbm.index');

        Route::get('laporan-bbm/export/pdf', [LaporanBBMController::class,'exportPdf'])
            ->name('laporan-bbm.export.pdf');

        Route::get('laporan-bbm/create', [LaporanBBMController::class, 'create'])
            ->name('laporan-bbm.create');
        
        Route::resource('permohonan', AdminPermohonanController::class);

        Route::post('permohonan/{id}/reject',[AdminPermohonanController::class,'reject']);
        
        Route::post('permohonan/{id}/approve',[AdminPermohonanController::class,'approve'])
            ->name('permohonan.approve');

        Route::post('laporan-bbm', [LaporanBBMController::class, 'store'])
            ->name('laporan-bbm.store');

        Route::get('laporan-bbm/{id}', [LaporanBBMController::class, 'show'])
            ->name('laporan-bbm.show');

        Route::get('laporan-bbm/{id}/edit', [LaporanBBMController::class, 'edit'])
            ->name('laporan-bbm.edit');

        Route::put('laporan-bbm/{id}', [LaporanBBMController::class, 'update'])
            ->name('laporan-bbm.update');

        Route::delete('laporan-bbm/{id}', [LaporanBBMController::class, 'destroy'])
            ->name('laporan-bbm.destroy');
        
        Route::get('laporan-bbm/{id}/nota',[LaporanBBMController::class, 'nota'])
            ->name('laporan-bbm.nota');
});

Route::prefix('driver')
    ->name('driver.')
    ->middleware(['auth','driver'])
    ->group(function () {

        Route::redirect('/dashboard', '/driver/permohonan')
            ->name('dashboard');

        Route::resource('permohonan', PermohonanController::class);
        
        Route::get('/laporan-bbm', [DriverLaporan::class,'index'])
            ->name('laporan.index');

        Route::get('/laporan-bbm/{id}', [DriverLaporan::class,'show'])
            ->name('laporan.show');
        
        Route::put('/laporan-bbm/{id}', [DriverLaporan::class,'update'])
            ->name('laporan.update');
        
        Route::get('/laporan-bbm/{id}/nota',[DriverLaporan::class,'nota'])
            ->name('laporan.nota');

});



