<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

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
    Route::get('/suppliers', [AdminController::class, 'supplier_list'])->name('admin_supplier_list');
    Route::get('/suppliers/{id}/edit', [AdminController::class, 'editSupplier'])->name('admin_supplier_edit');
    Route::delete('/suppliers/{id}', [AdminController::class, 'destroySupplier'])->name('admin_supplier_delete');
    Route::get('/suppliers/create', [AdminController::class, 'createSupplier'])->name('admin_supplier_add');
    Route::post('/suppliers', [AdminController::class, 'storeSupplier'])->name('admin_supplier_store');
    Route::get('/orders',[AdminController::class, 'orderList'])->name('admin_order_list');
    Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('admin.orders.destroy');
    Route::get('/orders_detail/{order_id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order_id}/accept', [OrderController::class, 'acceptOrder'])->name('admin.orders.accept');


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
Route::get('/laptops/{product_id}/{product_slug}', [ProductController::class, 'showProductDetail'])->name('product.detail');
Route::post('/order/buy-now/{product_id}', [OrderController::class, 'buyNow'])->name('order.buy_now');
Route::post('/laptops/{product_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::prefix('/cart')->group(function () {

    Route::post('/add/{product_id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/', [CartController::class, 'viewCart'])->name('user.cart');
    Route::get('/quantity', function () {
        if (!auth()->check()) {
            return response()->json(['totalQuantity' => 0]); // Nếu chưa đăng nhập, số lượng giỏ hàng là 0
        }
        $cart = session()->get('cart', []);
        return response()->json(['totalQuantity' => array_sum(array_column($cart, 'quantity'))]);
    });
    Route::post('/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/checkout', [CartController::class, 'checkoutCart'])->name('cart.checkout');
    Route::post('/processCheckout', [OrderController::class,'ProcessCheckoutCart'])->name('cart.processCheckout');
    Route::post('/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');

});

Route::prefix('api')->group(function () {
    Route::get('/admin/products', [AdminController::class, 'getProductListApi'])->name('admin_product_api');
    Route::get('/admin/categories', [AdminController::class, 'api_category_list'])->name('admin.api.category.list');
    Route::get('/admin/suppliers', [AdminController::class, 'apiSupplierList']);
    Route::get('/admin/orders',[AdminController::class,'getOrders'])->name('admin.order.list');
});

    Route::get('/user/info',[UserController::class, 'showUserInfo'])->name('user.info');
    Route::get('/api/districts/{province_code}', [AddressController::class, 'getDistricts']);
    Route::get('/api/wards/{district_code}', [AddressController::class, 'getWards']);
    Route::post('/user/address/store', [UserController::class, 'store'])->name('address.store');

    Route::post('/buy-now/{product_id}', [OrderController::class, 'buyNow'])->name('order.buy_now');
    Route::post('/checkout', [OrderController::class, 'checkoutSubmitBuyNow'])->name('order.checkout_submit');
