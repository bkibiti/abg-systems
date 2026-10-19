<?php

use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\CurrentStockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartnerContributionController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerPayoutController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root URL goes to the dashboard. Guests are sent to /login by the auth
// middleware, and after login Laravel returns them to /home (not back to the form).
Route::redirect('/', '/home');

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Products routes
    Route::resource('masters/products', ProductController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    // Product Categories routes
    Route::resource('masters/categories', ProductCategoryController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    // Supplier routes
    Route::resource('masters/suppliers', SupplierController::class)->only([
        'index', 'store', 'destroy', 'update',
    ]);

    // Expense Categories routes
    Route::resource('masters/expense-categories', ExpenseCategoryController::class)->only([
        'index', 'store', 'update', 'destroy',
    ]);

    // reminder routes
    Route::resource('reminders', ReminderController::class);

    // Purchase Routes
    Route::get('purchases/goods-receiving', [PurchaseController::class, 'index'])->name('goods-receiving.index');
    Route::post('purchases/goods-receiving', [PurchaseController::class, 'store'])->name('goods-receiving.store');
    Route::post('purchases/goods-receiving/update', [PurchaseController::class, 'update'])->name('goods-receiving.update');
    Route::get('purchases/history', [PurchaseController::class, 'history'])->name('goods-receiving.history');
    Route::get('purchases/history/search', [PurchaseController::class, 'search'])->name('goods-receiving.search');
    Route::post('purchases/previous-purchases', [PurchaseController::class, 'itemHistory'])->name('goods-receiving.item-history');

    // Configurations Routes
    Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('configurations.index');
    Route::post('/configurations', [ConfigurationsController::class, 'store'])->name('configurations.store');
    Route::post('/update-configurations', [ConfigurationsController::class, 'update'])->name('configurations.update');

    // Sales routes
    Route::get('sales/cash-sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('sales/cash-sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/sale-histories', [SaleController::class, 'history'])->name('sales.history');
    Route::get('sales/sale-histories/search', [SaleController::class, 'historySearch'])->name('sales.history-search');
    Route::post('sales/update-sales', [SaleController::class, 'update'])->name('sales.update');
    // sale reports
    Route::get('reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/total-daily-sales', [ReportController::class, 'getReport'])->name('reports.getreport');

    /* Inventory Routes */
    Route::resource('inventory-management/current-stock', CurrentStockController::class)->only([
        'index', 'update',
    ]);
    Route::get('inventory-management/current-stock/filter', [CurrentStockController::class, 'filter'])->name('current-stock-filter');
    Route::get('inventory-management/out-of-stock', [CurrentStockController::class, 'stockOut'])->name('out-of-stock');
    Route::get('inventory-management/below-min-level', [CurrentStockController::class, 'belowMin'])->name('below-min-level');

    /* stock adjustment routes */
    Route::resource('inventory-management/stock-adjustment', StockAdjustmentController::class)->only([
        'index', 'store',
    ]);
    Route::post('inventory/stock-adjustment/search', [StockAdjustmentController::class, 'search'])->name('adjustment.search');

    /* expense routes */
    Route::get('expense-management/expenses', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('expense-management/expenses', [ExpenseController::class, 'store'])->name('expense.store');
    Route::post('expense-management/expense-update', [ExpenseController::class, 'update'])->name('expense.update');
    Route::get('expense-management/expense-search', [ExpenseController::class, 'search'])->name('expense.search');

    // customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('customers/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::put('customers/delete', [CustomerController::class, 'delete'])->name('customers.delete');

    // user roles
    Route::get('user-roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('user-roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('user-roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('user-roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('user-roles/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('user-roles/delete', [RoleController::class, 'destroy'])->name('roles.destroy');

    // users routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/register', [UserController::class, 'store'])->name('users.register');
    Route::post('users/update', [UserController::class, 'update'])->name('users.update');
    Route::put('users/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::post('users/de-actiavate', [UserController::class, 'deActivate'])->name('users.deactivate');
    Route::post('users/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::get('users/change-password', [UserController::class, 'changePasswordForm'])->name('change-pass-form');
    Route::post('user-profile/update', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');

    // Partnership routes
    Route::get('partnership', [PartnerController::class, 'index'])->name('partnership.index')->middleware('can:View Partnership');
    Route::get('partnership/contributions', [PartnerContributionController::class, 'index'])->name('partnership.contributions.index')->middleware('can:View Partnership');
    Route::get('partnership/partners/{partner}', [PartnerController::class, 'show'])->name('partnership.partners.show')->middleware('can:View Partnership');

    Route::post('partnership/partners', [PartnerController::class, 'store'])->name('partnership.partners.store')->middleware('can:Manage Partnership');
    Route::put('partnership/partners/{partner}', [PartnerController::class, 'update'])->name('partnership.partners.update')->middleware('can:Manage Partnership');
    Route::delete('partnership/partners/{partner}', [PartnerController::class, 'destroy'])->name('partnership.partners.destroy')->middleware('can:Manage Partnership');

    Route::post('partnership/contributions', [PartnerContributionController::class, 'store'])->name('partnership.contributions.store')->middleware('can:Manage Partnership');
    Route::put('partnership/contributions/{contribution}', [PartnerContributionController::class, 'update'])->name('partnership.contributions.update')->middleware('can:Manage Partnership');
    Route::delete('partnership/contributions/{contribution}', [PartnerContributionController::class, 'destroy'])->name('partnership.contributions.destroy')->middleware('can:Manage Partnership');

    Route::get('partnership/payouts', [PartnerPayoutController::class, 'index'])->name('partnership.payouts.index')->middleware('can:View Partnership');
    Route::post('partnership/payouts', [PartnerPayoutController::class, 'store'])->name('partnership.payouts.store')->middleware('can:Manage Partnership');
    Route::post('partnership/payouts/{payout}/reverse', [PartnerPayoutController::class, 'reverse'])->name('partnership.payouts.reverse')->middleware('can:Reverse payouts');

});
