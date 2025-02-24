<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/index-user', [UserController::class, 'index'])->name('user.index');

Route::get('/api/search-suggestions', [UserController::class, 'searchSuggestions']);

Route::get('/user/{username}', [UserController::class, 'showProfile'])->name('user.profile');

Route::get('/photos/{photo_id}', [UserController::class, 'show'])->name('photos.show');

Route::get('/album/{albumId}', [UserController::class, 'showAlbumDetails'])->name('album.details');

Route::post('/album/{album}/add-photos', [UserController::class, 'addExistingPhotos'])->name('album.addExistingPhotos');

Route::patch('/album/{album}/remove-photo/{photo}', [UserController::class, 'removePhoto'])->name('album.removePhoto');

Route::post('/album/{albumId}/upload', [UserController::class, 'uploadPhotoToAlbum'])->name('album.upload');

Route::post('/comments/store', [UserController::class, 'storeComment'])->name('comments.store');

Route::put('/comments/{id}/update', [UserController::class, 'updateComment'])->name('comments.update');

Route::delete('/comments/{id}/delete', [UserController::class, 'deleteComment'])->name('comments.delete');

Route::post('/photos/{photoId}/toggle-like', [UserController::class, 'toggleLike'])->name('photos.toggle-like');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');

Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');

Route::patch('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');

Route::delete('/{photo}/trash', [UserController::class, 'trash'])->name('photos.trash');

Route::delete('/albums/{album}/trash', [UserController::class, 'trashAlbum'])->name('albums.trash');

Route::put('/albums/{album_id}/restore', [UserController::class, 'restoreAlbum'])->name('albums.restore');

Route::delete('/albums/{album_id}/force-delete', [UserController::class, 'forceDeleteALbum'])->name('albums.forceDelete');

// Kembalikan dari trash
Route::put('/{photo}/restore', [UserController::class, 'restore'])->name('photos.restore');

// Hapus permanen
Route::delete('/{photo}/force-delete', [UserController::class, 'forceDelete'])->name('photos.force-delete');

// Tampilkan foto-foto di trash
Route::get('/trashed', [UserController::class, 'trashedPhotos'])->name('photos.trashed');

Route::get('/upload', [UserController::class, 'upload'])->name('upload');

Route::post('/photos/store', [UserController::class, 'store'])->name('photos.store');

Route::get('/photos/{photoId}/edit', [UserController::class, 'editPhoto'])->name('photos.edit');

Route::put('/photos/{photoId}', [UserController::class, 'updatePhoto'])->name('photos.update');

Route::post('/albums/store', [UserController::class, 'storeAlbums'])->name('albums.store');

Route::get('/explore', [UserController::class, 'explore'])->name('jelajah');

Route::get('/albums/{albumId}/edit', [UserController::class, 'editAlbum'])->name('albums.edit');

Route::put('/albums/{albumId}', [UserController::class, 'updateAlbum'])->name('albums.update');

Route::get('/explore/photo/{photoId}', [UserController::class, 'photoDetail'])->name('photo.detail');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// ADMIN

Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/users', [AdminController::class, 'userManagement'])->name('admin.users');

Route::post('/admin/users/create-admin', [AdminController::class, 'createAdmin'])->name('admin.users.create-admin');

Route::get('/admin/photos', [AdminController::class, 'photoManagement'])->name('admin.photos');

Route::delete('/admin/photos/{photo}', [AdminController::class, 'deletePhoto'])->name('admin.photos.delete');

Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');

Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');

Route::patch('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

Route::get('/admin/users/{username}/profile',[AdminController::class, 'showUserProfile'])->name('admin.users.profile');

// Route untuk registrasi
Route::post('register', [AuthController::class, 'register'])->name('register');

// Route untuk login
Route::post('login', [AuthController::class, 'login'])->name('login');

// Route untuk logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
