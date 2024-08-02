<?php

use Illuminate\Support\Facades\{Route, Auth};


// disable register, reset password
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', App\Livewire\Admin\Home::class)->name('home');
// livewire
// profil
Route::get('/profil', App\Livewire\Profil::class)->name('profil');

    //admin.manajemen-user
    Route::get('/admin/manajemen-user', App\Livewire\Admin\ManajemenUser::class)->name('admin.manajemen-user');
    Route::get('/admin/kalender', App\Livewire\Admin\Kalender::class)->name('admin.kalender');
    Route::get('/admin/pegawai', App\Livewire\Admin\Pegawai::class)->name('admin.pegawai');
    Route::get('/admin/program', App\Livewire\Admin\Program::class)->name('admin.program');
    Route::get('/admin/subprogram', App\Livewire\Admin\Subprogram::class)->name('admin.subprogram');
    Route::get('/admin/kegiatan', App\Livewire\Admin\Kegiatan::class)->name('admin.kegiatan');
    Route::get('/admin/subkegiatan', App\Livewire\Admin\Subkegiatan::class)->name('admin.subkegiatan');
    Route::get('/admin/aktivitas', App\Livewire\Admin\Aktivitas::class)->name('admin.aktivitas');
    Route::get('/admin/kalender', App\Livewire\Admin\Kalender::class)->name('admin.kalender');
