<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// ───────────────────────────────────────────────
// Public Routes
// ───────────────────────────────────────────────
Route::get('/', function () {
    $products = Product::with('photos')
        ->where('is_active', true)
        ->latest()
        ->take(8)
        ->get();

    return view('home', compact('products'));
})->name('home');

// ───────────────────────────────────────────────
// Guest Routes (only for unauthenticated users)
// ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// ───────────────────────────────────────────────
// Email Verification Routes (FR3.2)
// ───────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

// ───────────────────────────────────────────────
// Admin-Only Routes (must come before {product} wildcard)
// ───────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // Product Management (Create, Edit, Update, Delete, Restore, Import)
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products-template/download', [ProductController::class, 'downloadTemplate'])->name('products.template');

    // User Management (Admin CRUD)
    Route::resource('users', UserController::class);

    // Review Management (Admin)
    Route::get('reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
    Route::delete('reviews/{review}', [ReviewController::class, 'adminDestroy'])->name('admin.reviews.destroy');

    // Order Management (Admin)
    Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('admin/orders/{order}/delivery', [OrderController::class, 'updateDelivery'])->name('admin.orders.updateDelivery');
});

// ───────────────────────────────────────────────
// Authenticated Routes (any logged-in + verified user)
// ───────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

    // Profile Management (self)
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Users can view products (read-only)
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Reviews (post & update)
    Route::post('products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    // Shopping Cart
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Order History
    Route::get('orders', [CartController::class, 'orders'])->name('orders.index');
});

// Logout must be accessible even if email is not verified
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
