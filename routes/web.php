<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('home_page');
})->name('home');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin_dashboard');
    Route::get('/product',[AdminController::class, 'product_list'])->name('admin_product_list');
    Route::get('/category',[AdminController::class, 'category_list'])->name('admin_category_list');
    Route::get('/product/add',[AdminController::class, 'create'])->name('admin_product_add');
    Route::post('/product/add',[AdminController::class, 'store'])->name('admin_product_add');
    Route::get('/products/{product_id}/edit', [AdminController::class, 'edit'])->name('admin_product_edit');
    Route::put('/products/{product_id}', [AdminController::class, 'update'])->name('admin_product_update');
    Route::delete('/products/{product_id}', [AdminController::class, 'destroy'])->name('admin_product_delete');
    Route::get('/category/add', [AdminController::class, 'createCategory'])->name('admin_category_add');
    Route::post('/category/add', [AdminController::class, 'storeCategory'])->name('admin_category_store');
    Route::get('/category/{id}/edit', [AdminController::class, 'editCategory'])->name('admin_category_edit');
    Route::put('/category/{id}', [AdminController::class, 'updateCategory'])->name('admin_category_update');
    Route::delete('/category/{id}', [AdminController::class, 'destroyCategory'])->name('admin_category_delete');
    Route::get('/users', [AdminController::class, 'userList'])->name('admin_user_list');
    Route::get('/users/create', [AdminController::class, 'showAddUserForm'])->name('admin_user_add');
    Route::get('/users/{id}', [AdminController::class, 'userDetail'])->name('admin_user_detail')->where('id', '[0-9]+');
    Route::post('/users/{id}/disable', [AdminController::class, 'disableUser'])->name('admin_user_disable');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('admin_user_store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin_user_edit');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin_user_update');

});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login_submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register_submit');
Route::get('/forgot-password', [AuthController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'verifyEmail'])->name('password.verify');
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
Route::get('/laptops', [ProductController::class, 'laptopList'])->name('laptops');
Route::get('/accessories', [ProductController::class, 'accessories'])->name('accessories');
Route::get('/products/{product_id}/{product_slug}', [ProductController::class, 'showProductDetail'])->name('product.detail');
Route::post('/cart/add/{product_id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/order/buy-now/{product_id}', [OrderController::class, 'buyNow'])->name('order.buy_now');
Route::post('/products/{product_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
