<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/index-user', [UserController::class, 'index'])->name('user.index');

Route::get('/explore', [UserController::class, 'explore'])->name('jelajah');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');

Route::get('/upload', [UserController::class, 'upload'])->name('upload');

Route::post('/photos/store', [UserController::class, 'store'])->name('photos.store');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Route untuk registrasi
Route::post('register', [AuthController::class, 'register'])->name('register');

// Route untuk login
Route::post('login', [AuthController::class, 'login'])->name('login');

// Route untuk logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
