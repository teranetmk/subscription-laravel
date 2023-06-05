<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PurchaseController;
use App\Actions\Subscription\ManageBilling;
use App\Actions\Subscription\ManageSubscriptions;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::get('admin/products', [ProductController::class, 'index'])->name('products');
    Route::get('admin/product-create', [ProductController::class, 'create'])->name('product-create');
    Route::post('admin/product-store', [ProductController::class, 'store'])->name('product-store');
    Route::get('admin/product-edit/{product:id}', [ProductController::class, 'edit'])->name('product-edit');
    Route::patch('admin/product-update/{product:id}', [ProductController::class, 'update'])->name('product-update');
    Route::delete('admin/product-delete/{product:id}', [ProductController::class, 'destroy'])->name('product-delete');

    Route::get('subscription', [ManageSubscriptions::class, 'show'])->name('subscriptions');
    Route::post('subscription/increase-subscription-quantity', [ManageSubscriptions::class, 'increaseSubscriptionQuantity'])->name('increase-subscription-quantity');
    Route::post('subscription/subscribe-with-existing', [ManageSubscriptions::class, 'subscribeWithExisting'])->name('subscribe-with-existing');
    Route::post('subscription/cancel', [ManageSubscriptions::class, 'cancel'])->name('subscription-cancel');

    Route::get('billing', [ManageBilling::class, 'show'])->name('billing');
    Route::get('/billing/add-payment', [ManageBilling::class, 'addPayment'])->name('add-payment');
    Route::post('/billing/store-payment', [ManageBilling::class, 'storePayment'])->name('store-payment');
    Route::post('/billing/remove-payment', [ManageBilling::class, 'removePayment'])->name('remove-payment');
    Route::post('/billing/remove-all-payments', [ManageBilling::class, 'removeAllPayments'])->name('remove-all-payments');
    Route::post('/billing/set-default-payment', [ManageBilling::class, 'setDefaultPayment'])->name('set-default-payment');

    Route::get('order-form', [CheckoutController::class, 'orderForm'])->name('order-form');
    Route::get('checkout-summary', [CheckoutController::class, 'checkoutSummary'])->name('checkout-summary');
    Route::post('process-checkout', [CheckoutController::class, 'processCheckout'])->name('process-checkout');
    Route::get('order-complete', [CheckoutController::class, 'orderComplete'])->name('order-complete');

    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases');
    Route::get('purchase-show/{purchase:transaction_id}', [PurchaseController::class, 'show'])->name('purchase-show');

});

require __DIR__.'/auth.php';
