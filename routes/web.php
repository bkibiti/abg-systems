<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\CurrentStockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LodgeDashboardController;
use App\Http\Controllers\LodgeExpenseController;
use App\Http\Controllers\LodgeSalesController;
use App\Http\Controllers\PriceCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffLossController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Not named 'login': Auth::routes() owns that name (GET/POST /login). In 5.8 the
// later duplicate silently won; Laravel 13 resolves the first one, which sent
// the login form to POST / and guests into a redirect loop.
Route::get('/', [HomeController::class, 'login']);

Auth::routes(['register' => false]);


Route::middleware(["auth"])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/advanced-dashboard', [HomeController::class, 'dashboard'])->name('dashboard');


    //Products routes
    Route::resource('masters/products', ProductController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Categories routes
    Route::resource('masters/categories', CategoryController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Supplier routes
    Route::resource('masters/suppliers', SupplierController::class)->only([
        'index', 'store', 'destroy', 'update'
    ]);

    //Price Categories routes
    Route::resource('masters/price-categories', PriceCategoryController::class)->only([
        'index', 'update', 'destroy'
    ]);

    //Expense Categories routes
    Route::resource('masters/expense-categories', ExpenseCategoryController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);


    //Store routes
    Route::resource('masters/stores', StoreController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //reminder routes
    Route::resource('reminders', ReminderController::class);

    //Purchase Routes
    Route::get('purchases/goods-receiving', [PurchaseController::class, 'index'])->name('goods-receiving.index');
    Route::post('purchases/goods-receiving', [PurchaseController::class, 'store'])->name('goods-receiving.store');
    Route::post('purchases/goods-receiving/update', [PurchaseController::class, 'update'])->name('goods-receiving.update');
    Route::get('purchases/history', [PurchaseController::class, 'history'])->name('goods-receiving.history');
    Route::get('purchases/history/search', [PurchaseController::class, 'search'])->name('goods-receiving.search');
    Route::post('purchases/previous-purchases', [PurchaseController::class, 'itemHistory'])->name('goods-receiving.item-history');


    //Configurations Routes
    Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('configurations.index');
    Route::post('/configurations', [ConfigurationsController::class, 'store'])->name('configurations.store');
    Route::post('/update-configurations', [ConfigurationsController::class, 'update'])->name('configurations.update');


    //Sales routes
    Route::get('sales/cash-sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('sales/cash-sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/sale-histories', [SaleController::class, 'history'])->name('sales.history');
    Route::post('sales/sale-histories', [SaleController::class, 'historySearch'])->name('sales.history-search');
    Route::get('sales/pending-orders', [SaleController::class, 'pendingOrders'])->name('sales.pending-order');
    Route::get('sales/daily-report/index', [DailySaleController::class, 'index'])->name('sales.daily-index');
    Route::post('sales/update-sales', [SaleController::class, 'update'])->name('sales.update');
    Route::post('sales/daily-report/generate', [DailySaleController::class, 'generate'])->name('sales.daily-generate');
    Route::post('sales/daily-report/update', [DailySaleController::class, 'update'])->name('sales.daily-update');
    Route::post('sales/daily-report/review', [DailySaleController::class, 'review'])->name('sales.daily-review');
    Route::post('sales/daily-report/search', [DailySaleController::class, 'search'])->name('sales.daily-search');
    //sale reports
    Route::get('reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/total-daily-sales', [ReportController::class, 'getReport'])->name('reports.getreport');


    //staff losses and advance
    Route::get('expense-management/staff-loss', [StaffLossController::class, 'index'])->name('losses.index');
    Route::post('expense-management/staff-loss', [StaffLossController::class, 'store'])->name('losses.store');
    Route::post('expense-management/staff-loss-edit', [StaffLossController::class, 'update'])->name('losses.update');
    Route::get('expense-management/staff-loss-search', [StaffLossController::class, 'search'])->name('losses.search');


    /*Inventory Routes*/
    Route::resource('inventory-management/current-stock', CurrentStockController::class)->only([
        'index', 'update'
    ]);
    Route::get('inventory-management/current-stock/filter',[CurrentStockController::class, 'filter'])->name('current-stock-filter');
    Route::get('inventory-management/out-of-stock', [CurrentStockController::class, 'stockOut'])->name('out-of-stock');
    Route::get('inventory-management/below-min-level', [CurrentStockController::class, 'belowMin'])->name('below-min-level');

    Route::get('inventory-management/daily-stock-count', [StockCountController::class, 'index'])->name('daily-stock-count.index');
    Route::get('inventory-management/daily-stock-count-print', [StockCountController::class, 'print'])->name('daily-stock-count.print');

    Route::get('inventory-management/monthly-closing-stock', [StockCountController::class, 'closingIndex'])->name('monthly-closing-stock.index');
    Route::post('inventory-management/monthly-closing-stock', [StockCountController::class, 'closingStockStore'])->name('monthly-closing-stock.store');
    Route::get('inventory-management/monthly-closing-stock/filter', [StockCountController::class, 'closingStockFilter'])->name('monthly-closing-stock.filter');


    /*stock adjustment routes*/
    Route::resource('inventory-management/stock-adjustment', StockAdjustmentController::class)->only([
        'index', 'store',
    ]);
    Route::post('inventory/stock-adjustment/search',[StockAdjustmentController::class, 'search'])->name('adjustment.search');


    /*expense routes*/
    Route::get('expense-management/expenses', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('expense-management/expenses', [ExpenseController::class, 'store'])->name('expense.store');
    Route::post('expense-management/expense-update', [ExpenseController::class, 'update'])->name('expense.update');
    Route::get('expense-management/expense-search', [ExpenseController::class, 'search'])->name('expense.search');

    //customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('customers', [CustomerController::class, 'store'])->name("customers.store");
    Route::post('customers/update', [CustomerController::class, 'update'])->name("customers.update");
    Route::put('customers/delete', [CustomerController::class, 'delete'])->name("customers.delete");

    //user roles
    Route::get('user-roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('user-roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('user-roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('user-roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('user-roles/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('user-roles/delete', [RoleController::class, 'destroy'])->name("roles.destroy");

    //users routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/register', [UserController::class, 'store'])->name("users.register");
    Route::post('users/update', [UserController::class, 'update'])->name("users.update");
    Route::put('users/delete', [UserController::class, 'delete'])->name("users.delete");
    Route::post('users/de-actiavate', [UserController::class, 'deActivate'])->name("users.deactivate");
    Route::post('users/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::get('users/change-password', [UserController::class, 'changePasswordForm'])->name('change-pass-form');
    Route::post('user-profile/update', [UserController::class, 'updateProfile'])->name("update-profile");
    Route::get('users/search', [UserController::class, 'search'])->name("users.search");
    Route::post('users/user-role-id', [UserController::class, 'getRoleID'])->name('getRoleID');


    //Lodge
    Route::get('lodge/dashboard', [LodgeDashboardController::class, 'index'])->name('lodge.index');

    Route::get('lodge/expenses', [LodgeExpenseController::class, 'index'])->name('lodge-expense.index');
    Route::post('lodge/expenses', [LodgeExpenseController::class, 'store'])->name('lodge-expense.store');
    Route::post('lodge/expense-update', [LodgeExpenseController::class, 'update'])->name('lodge-expense.update');
    Route::get('lodge/expense-search', [LodgeExpenseController::class, 'search'])->name('lodge-expense.search');

    Route::get('lodge/sales', [LodgeSalesController::class, 'index'])->name('lodge-sales.index');
    Route::post('lodge/sales', [LodgeSalesController::class, 'store'])->name('lodge-sales.store');
    Route::post('lodge/sales-update', [LodgeSalesController::class, 'update'])->name('lodge-sales.update');
    Route::get('lodge/sales-search', [LodgeSalesController::class, 'search'])->name('lodge-sales.search');


});


