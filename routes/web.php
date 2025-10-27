<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\POS\PurchaseController;
use App\Http\Controllers\POS\ProductController as PosProductController;
use App\Http\Controllers\POS\PartyController as PosPartyController;
use App\Http\Controllers\POS\PaymentController as PosPaymentController;
use App\Http\Controllers\POS\ExpenseController;
use App\Http\Controllers\POS\LocationController;
use App\Http\Controllers\POS\WarehouseController;
use App\Http\Controllers\POS\ReportController;
use App\Http\Controllers\POS\BackupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/;

Route::middleware('auth')->group(function () {

    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',[DashboardController::class, 'edit'])->name('edit');
        Route::put('update',[DashboardController::class, 'update'])->name('update');
    });

    Route::prefix('operations')->name('operations.')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::resource('tasks', TaskController::class);

    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('users',UserController::class);
        Route::resource('roles',RoleController::class);

    });

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('sell', [SaleController::class, 'index'])->name('sell');
        Route::post('sell', [SaleController::class, 'store'])->name('sell.store');
        Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('sales', [SaleController::class, 'history'])->name('sales.index');
        Route::get('lookup/product', [SaleController::class, 'productLookup'])->name('lookup.product');

        Route::get('purchase', [PurchaseController::class, 'index'])->name('purchase');
        Route::post('purchase', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::get('purchases', [PurchaseController::class, 'history'])->name('purchases.index');

        Route::resource('products', PosProductController::class)->only(['index','store','update','destroy']);
        Route::resource('parties', PosPartyController::class)->only(['index','store','update','destroy']);

        Route::post('payments/sale/{sale}', [PosPaymentController::class, 'storeForSale'])->name('payments.sale');
        Route::post('payments/purchase/{purchase}', [PosPaymentController::class, 'storeForPurchase'])->name('payments.purchase');

        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');

        Route::resource('locations', LocationController::class)->only(['index','store','update','destroy']);
        Route::resource('warehouses', WarehouseController::class)->only(['index','store','update','destroy']);

        Route::get('stock', [ReportController::class, 'stockOnHand'])->name('stock.index');

        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup/run', [BackupController::class, 'run'])->name('backup.run');
        Route::get('backup/sql', [BackupController::class, 'downloadSql'])->name('backup.sql');
    });


});


require __DIR__.'/auth.php';
