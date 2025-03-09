<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\OutfitController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SelectServiceController;
use App\Http\Controllers\SelectStaffDetailController;
use App\Models\CartItem;
use App\Models\SelectService;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

Route::get('/', [OutfitController::class, 'index'])->name('outfits.index');
Route::prefix('outfits')->name('outfits.')->group(function(){
    Route::get('/search/{searchkey}',[OutfitController::class, 'searchOutfits'])->name('search');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['is_active'])->group(function () {


// make-up
Route::prefix('make-upartist')->name('make-upartist.')->middleware('auth', 'is_makeup')->group(function () {
    Route::get('/', [SelectStaffDetailController::class, 'index'])->name('dashboard');
    Route::get('/work/details/{id}', [SelectStaffDetailController::class, 'show'])->name('work.details');
    Route::post('/work/finish/{id}', [SelectStaffDetailController::class, 'finishWork'])->name('work.finish');
    Route::get('/work-list', [SelectServiceController::class, 'getAvailableJobs'])->name('work-list');


    Route::post('/accept-job', [SelectServiceController::class, 'acceptJob'])->name('accept-job');
});

// photographer
Route::prefix('photographer')->name('photographer.')->middleware('auth', 'is_photographer')->group(function () {
    Route::get('/', [SelectStaffDetailController::class, 'index'])->name('dashboard');
    Route::get('/work/details/{id}', [SelectStaffDetailController::class, 'show'])->name('work.details');
    Route::post('/work/finish/{id}', [SelectStaffDetailController::class, 'finishWork'])->name('work.finish');
    Route::get('/work-list', [SelectServiceController::class, 'getAvailableJobs'])->name('work-list');

    Route::post('/accept-job', [SelectServiceController::class, 'acceptJob'])->name('accept-job');
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
    

    //crud outfit
    Route::get('/outfits', [OutfitController::class, 'AdminIndex'])->name('outfits.adminindex');
    Route::get('/outfits/{id}/edit', [OutfitController::class, 'AdminEdit'])->name('outfits.edit');
    Route::PUT('/outfits/{id}', [OutfitController::class, 'update'])->name('outfits.update');
    Route::delete('/outfits/{id}', [OutfitController::class, 'destroy'])->name('outfits.destroy');

});




// shop owner
Route::prefix('shopowner')->name('shopowner.')->middleware('auth', 'is_shopowner')->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    
    // จัดการร้านค้า
    Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/my-shop', [ShopController::class, 'myShop'])->name('shops.my-shop');
    Route::get('/shops/{shop_id}/edit-my-shop', [ShopController::class, 'editMyShop'])->name('shops.edit-my-shop');
    Route::put('/shops/{shop_id}/update-my-shop', [ShopController::class, 'updateMyShop'])->name('shops.update-my-shop');
    
    // จัดการชุด
    Route::get('/shop/costumes', [ShopController::class, 'listCostumes'])->name('shop.costumes');
    Route::get('/shop/new-form', [ShopController::class, 'newForm'])->name('shop.new-form');
    Route::post('/shop/store-costume', [ShopController::class, 'storeCostume'])->name('shop.store-costume');

    // Outfit Routes
    Route::get('/outfits', [OutfitController::class, 'shopOwnerIndex'])->name('outfits.index');
    Route::get('/outfits/create', [OutfitController::class, 'create'])->name('outfits.create');
    Route::post('/outfits', [OutfitController::class, 'store'])->name('outfits.store');
    Route::get('/outfits/{outfit}/edit', [OutfitController::class, 'edit'])->name('outfits.edit');
    Route::put('/outfits/{outfit}', [OutfitController::class, 'update'])->name('outfits.update');
    Route::delete('/outfits/{outfit}', [OutfitController::class, 'destroy'])->name('outfits.destroy');

    // Category management routes
    Route::resource('categories', CategoryController::class);
});
//auth
Route::middleware('auth')->group(function () {

    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    //Customer
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/editCus', [ProfileController::class, 'editCus'])->name('profile.editCus');

    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::middleware(['auth', 'is_customer'])->group(function () {
    Route::get('/outfit/all', [OutfitController::class, 'index'])->name('outfit.all');
});

Route::prefix('orderdetail')->name('orderdetail.')->group(function(){
    Route::get('/outfit/{idOutfit}', [OrderDetailController::class, 'index'])->name('orderdetail.index');
});

//search
Route::prefix('outfits')->name('outfits.')->group(function(){
    Route::get('/search', [OutfitController::class, 'searchOutfits'])->name('search');
});

Route::prefix('cartItem')->name('cartItem.')->group(function(){
    Route::post('/addToCart', [CartItemController::class, 'addToCart'])->name('cart.add');
    Route::get('/allItem',[CartItemController::class, 'index'])->name('allItem');

    Route::get('/deleteItem/{idItem}', [CartItemController::class, 'deleteItem'])->name('deleteItem');
    Route::get('/updateAmount/{idItem}', [CartItemController::class, 'updateItem'])->name('updateItem');
});




});


require __DIR__ . '/auth.php';
