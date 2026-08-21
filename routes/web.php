<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//customer
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/', function () {
        return view('customer.home.index');
    })->name('home');

    Route::get('/shop', function () {
        return view('customer.shop.index');
    })->name('shop');

    Route::get('/shop/produk/{id}', function () {
        return view('customer.shop.produk-detail');
    })->name('shop.produk-detail');

    Route::get('/shop/store/{id}', function () {
        return view('customer.shop.store-detail');
    })->name('shop.store-detail');

    Route::get('/search', function () {
        return view('customer.search.index');
    })->name('search');

    Route::get('/chart', function () {
        return view('customer.chart.index');
    })->name('chart');

    Route::get('/checkout', function () {
        return view('customer.checkout.index');
    })->name('checkout');

    Route::get('/order-tracking', function () {
        return view('customer.order-tracking.index');
    })->name('order-tracking');

    Route::get('/account', function () {
        return view('customer.account.index');
    })->name('account');

    Route::get('/account/edit', function () {
        return view('customer.account.edit');
    })->name('account.edit');

    Route::get('/account/password', function () {
        return view('customer.account.password');
    })->name('account.password');

    Route::get('/address', function () {
        return view('customer.address.index');
    })->name('address');

    Route::get('/wishlist', function () {
        return view('customer.wishlist.index');
    })->name('wishlist');
});