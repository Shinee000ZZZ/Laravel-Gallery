<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/index-user', [UserController::class, 'index'])->name('user.index');

Route::get('/user/{username}', [UserController::class, 'showProfile'])->name('user.profile');

Route::get('/photos/{photo_id}', [UserController::class, 'show'])->name('photos.show');

Route::get('/album/{albumId}', [UserController::class, 'showAlbumDetails'])->name('album.details');

Route::post('/album/{album}/add-photos', [UserController::class, 'addExistingPhotos'])->name('album.addExistingPhotos');

Route::patch('/album/{album}/remove-photo/{photo}', [UserController::class, 'removePhoto'])->name('album.removePhoto');

Route::post('/album/{albumId}/upload', [UserController::class, 'uploadPhotoToAlbum'])->name('album.upload');

Route::post('/comments/store', [UserController::class, 'storeComment'])->name('comments.store');

Route::post('/photos/{photoId}/toggle-like', [UserController::class, 'toggleLike'])->name('photos.toggle-like');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');

Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');

Route::patch('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');

Route::delete('/{photo}/trash', [UserController::class, 'trash'])
    ->name('photos.trash');

// Kembalikan dari trash
Route::put('/{photo}/restore', [UserController::class, 'restore'])->name('photos.restore');

// Hapus permanen
Route::delete('/{photo}/force-delete', [UserController::class, 'forceDelete'])->name('photos.force-delete');

// Tampilkan foto-foto di trash
Route::get('/trashed', [UserController::class, 'trashedPhotos'])->name('photos.trashed');

Route::get('/upload', [UserController::class, 'upload'])->name('upload');

Route::post('/photos/store', [UserController::class, 'store'])->name('photos.store');

Route::post('/albums/store', [UserController::class, 'storeAlbums'])->name('albums.store');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

Route::get('/explore', [UserController::class, 'explore'])->name('jelajah');

// Route untuk registrasi
Route::post('register', [AuthController::class, 'register'])->name('register');

// Route untuk login
Route::post('login', [AuthController::class, 'login'])->name('login');

// Route untuk logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
