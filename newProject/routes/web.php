<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;


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
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // เปลี่ยนสถานะผู้ใช้
    Route::post('/users/{user_id}/toggleStatus', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // แสดงรายการผู้ใช้
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/acceptance', [UserController::class, 'acceptance'])->name('users.acceptance');
    Route::post('/users/{user_id}/{status}', [UserController::class, 'updateStatus']); // error เกิดจากตัวนี้ !!!!!!!!!!!

    // แก้ไขผู้ใช้
    Route::get('/users/{user_id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user_id}', [UserController::class, 'update'])->name('users.update');


    Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
    Route::post('/shops/{user_id}/toggleStatus', [ShopController::class, 'toggleStatus'])->name('shops.toggleStatus');

    Route::post('/shops/{user_id}/edit', [ShopController::class, 'edit'])->name('shops.edit');
    Route::put('/shops/{user_id}', [ShopController::class, 'update'])->name('shops.update');

    Route::get('/shops/acceptance', [ShopController::class, 'acceptance'])->name('shops.acceptance');
    Route::post('/shops/{shop_id}/updateStatus', [ShopController::class, 'updateStatus'])->name('shops.updateStatus'); // error เกิดจากตัวนี้ !!!!!!!!!!!
    

});


// photographer
Route::prefix('photographer')->name('photographer.')->middleware('auth', 'is_photographer')->group(function () {
    Route::get('/dashboard', function () {})->name('dashboard');

    Route::get('/users', function () {})->name('users');
});


// shop owner
Route::prefix('shopowner')->name('shopowner.')->middleware('auth', 'is_shopowner')->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    
    // เพิ่ม routes สำหรับการจัดการร้านค้า
    Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/my-shop', [ShopController::class, 'myShop'])->name('shops.my-shop');
    Route::get('/shops/{shop_id}/edit-my-shop', [ShopController::class, 'editMyShop'])->name('shops.edit-my-shop');
    Route::put('/shops/{shop_id}/update-my-shop', [ShopController::class, 'updateMyShop'])->name('shops.update-my-shop');
});

//auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
