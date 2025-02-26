<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// make-up
Route::prefix('make-upartist')->name('make-upartist.')->middleware('auth', 'is_makeup')->group(function () {
    Route::get('/dashboard', function () {})->name('dashboard');

    Route::get('/users', function () {})->name('users');
});


// admin
Route::prefix('admin')->name('admin.')->middleware('auth', 'is_admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // แสดงหน้า user index ที่เรียงข้อมูลแล้ว
    Route::post('/users/index_order', [UserController::class, 'index_order'])->name('users.index_order');

    // เปลี่ยนสถานะผู้ใช้
    Route::post('/users/{user_id}/toggleStatus', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // แสดงรายการผู้ใช้
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/acceptance', [UserController::class, 'acceptance'])->name('users.acceptance');
    Route::post('/users/{user_id}/{status}', [UserController::class, 'updateStatus']); // error เกิดจากตัวนี้ !!!!!!!!!!!

    // แก้ไขผู้ใช้
    Route::get('/users/{user_id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user_id}', [UserController::class, 'update'])->name('users.update');
});


// photographer
Route::prefix('photographer')->name('photographer.')->middleware('auth', 'is_photographer')->group(function () {
    Route::get('/dashboard', function () {})->name('dashboard');

    Route::get('/users', function () {})->name('users');
});


// shop owner
Route::prefix('shopowner')->name('shopowner.')->middleware('auth', 'is_shopowner')->group(function () {
    Route::get('/dashboard', function () {})->name('dashboard');

    Route::get('/users', function () {})->name('users');
});

//auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
