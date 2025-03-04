<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home_page');
});

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
