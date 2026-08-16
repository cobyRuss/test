<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomizeController;
use App\Http\Controllers\GcashPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SavedCustomizationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->whereNumber('id')->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/service-photos', ServicePhotoController::class)->name('services.photos');

Route::get('/customize', [CustomizeController::class, 'index'])->name('customize.index');
Route::post('/customize/save', [SavedCustomizationController::class, 'store'])->name('customize.save');
Route::post('/customize/delete', [SavedCustomizationController::class, 'destroy'])->name('customize.delete');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add-custom', [CartController::class, 'addCustom'])->name('cart.addCustom');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/orders/{id}', [OrderController::class, 'show'])->whereNumber('id')->name('orders.show');
Route::get('/orders/{id}/cancel', [OrderController::class, 'cancelForm'])->whereNumber('id')->name('orders.cancel');
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->whereNumber('id')->name('orders.cancel.submit');
Route::get('/orders/cancel-success', [OrderController::class, 'cancelSuccess'])->name('orders.cancel-success');

Route::get('/orders/{id}/gcash', [GcashPaymentController::class, 'show'])->whereNumber('id')->name('orders.gcash');
Route::post('/orders/{id}/gcash', [GcashPaymentController::class, 'store'])->whereNumber('id')->name('orders.gcash.submit');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/forgot', [PasswordController::class, 'forgot'])->name('forgot');
Route::post('/forgot', [PasswordController::class, 'forgot'])->name('forgot.submit');
Route::get('/reset', [PasswordController::class, 'showReset'])->name('reset.show');
Route::post('/reset', [PasswordController::class, 'reset'])->name('reset.submit');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.post');
        Route::get('/orders/{id}/details', [DashboardController::class, 'orderDetails'])->whereNumber('id')->name('admin.orders.details');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
