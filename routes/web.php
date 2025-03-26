<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Vendor Authentication Routes
Route::get('/register', [VendorController::class, 'showRegister'])->name('vendor.register');
Route::post('/register', [VendorController::class, 'register'])->name('vendor.register.post');

Route::get('/login', [VendorController::class, 'showLogin'])->name('vendor.login');
Route::post('/login', [VendorController::class, 'login'])->name('vendor.login.post');

// Protected Vendor Routes (Require Authentication)
Route::middleware(['auth:vendor'])->group(function () { 
    Route::get('/home', [VendorController::class, 'home'])->name('vendor.home');
    Route::post('/logout', [VendorController::class, 'logout'])->name('vendor.logout');

    // Product Routes (Only Accessible by Vendors)
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

});


//Real Customer to See it
Route::get('/customer/{vendor}', [CustomerController::class, 'show'])->name('customer.show');


// Customer Preview Route (Public)
Route::get('/customers-preview', function () {
    // Dummy vendor data
    $dummyVendor = (object)[
        'name' => 'My Store - Customer View',
        'description' => 'Preview of customer-facing store interface',
        'image' => null
    ];

    // Dummy products data
    $dummyProducts = collect([
        (object)[
            'id' => 1,
            'name' => 'Premium Widget',
            'description' => 'High-quality widget for all your needs',
            'price' => 24.99,
            'quantity' => 15,
            'image' => null,
            'category' => 'Widgets',
            'rating' => 4.5
        ]
    ]);

    // Dummy categories for filters
    $dummyCategories = ['All', 'Widgets', 'Gadgets', 'Tools'];

    return view('customer', [
        'vendor' => $dummyVendor,
        'products' => $dummyProducts,
        'categories' => $dummyCategories
    ]);
});
