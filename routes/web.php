<?php

use Illuminate\Support\Facades\Route;
use Mycools\Shopee\Http\Controllers\ShopController;
use Mycools\Shopee\Http\Controllers\WebhookController;

Route::prefix('shops')->name('shops.')->group(function () {
    Route::get('/authorized', [ShopController::class, 'authorized'])->name('authorized');
});

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::match(['get', 'post'], '', [WebhookController::class, 'index'])->name('index');
});
