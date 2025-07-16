<?php

use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
});


Route::get('/cart', function () {
    return view('cart');
})->name('cart');
// Auth
Route::view('/register', 'register')->name('register');
Route::view('/login', 'login')->name('login');

// Password Reset
Route::get('/reset-password/{token}', function ($token) {
    return view('reset-password', [
        'token' => $token,
        'email' => request('email')
    ]);
})->middleware('guest')->name('password.reset');

// Brands
Route::view('/brands', 'brands')->name('brands');

// Reviews
Route::view('/reviews', 'reviews')->name('reviews');

// Payment Failed Page
Route::view('/payment-failed', 'payment-failed')->name('payment.failed');



// Categories Page
Route::view('/categories', 'categories')->name('categories');

// Dynamic Category Products Page
Route::get('/categories/{slug}', function ($slug) {
    return "Products for category: " . ucfirst($slug);
})->name('category.products');


// Checkout Page
Route::view('/checkout', 'checkout')->name('checkout');

// Orders List
Route::get('/orders', function () {
    $orders = collect([
        (object)[ 'id' => 101, 'date' => '2024-06-01', 'status' => 'Pending', 'payment_status' => 'Paid', 'amount' => 12000.00 ],
        (object)[ 'id' => 102, 'date' => '2024-06-10', 'status' => 'Shipped', 'payment_status' => 'Paid', 'amount' => 24000.00 ],
        (object)[ 'id' => 103, 'date' => '2024-07-01', 'status' => 'Pending', 'payment_status' => 'Unpaid', 'amount' => 7500.00 ],
    ]);

    return view('orders', compact('orders'));
})->name('orders');

// Order Details (used by "View Details" button)
Route::get('/orders/{id}', function ($id) {
    return "Showing order detail for order ID: $id";
})->name('orders.show');

// Static Order Details Page (if used)
Route::view('/order-details', 'order-details')->name('order.details');

// Products Page
Route::view('/products', 'products')->name('products.index');

// Order Confirmation Page
Route::view('/order/confirmation', 'order.confirmation')->name('order.confirmation');
