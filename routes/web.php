<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//LOGIN and LOGOUT
Route::get('/login', 'AuthController@index')->name('login');
Route::post('/login', 'AuthController@store');
Route::post('/logout', 'AuthController@destroy')->name('logout');

Route::middleware(['auth'])->group(function () {
    //DASHBOARD
    Route::get('/', 'DashboardController@index')->name('dashboard');
    
    //AJAX DASHBOARD
    Route::get('/info/{id}', 'DashboardController@show');
    Route::get('/info/search', 'DashboardController@search');
    Route::post('/info/search', 'DashboardController@search');
    
    //OFFICES
    Route::middleware(['permissions:VO'])->group(function () {
        Route::get('/offices', 'OfficeController@index');
        //AJAX
        Route::get('/offices/search', 'OfficeController@search');
        Route::post('/offices/search', 'OfficeController@search');
    });

    Route::middleware(['permissions:AO, VO'])->group(function () {
        Route::post('/offices', 'OfficeController@store')->name('office.store');
    });

    Route::middleware(['permissions:EO, VO'])->group(function () {
        Route::get('/offices/{office}/edit', 'OfficeController@edit');
        Route::patch('/offices/{office}/edit', 'OfficeController@update');
    });

    //USER
    Route::middleware(['permissions:VU'])->group(function () {
        Route::get('/users', 'UserController@index');
        //AJAX
        Route::get('/users/search', 'UserController@search');
        Route::post('/users/search', 'UserController@search');
    });

    Route::middleware(['permissions:AU, VU'])->group(function () {
        Route::get('/users/create', 'UserController@create');
        Route::post('/users/create', 'UserController@store')->name('user.store');
    });

    Route::middleware(['permissions:EU, VU'])->group(function () {
        Route::get('/users/{user}/edit', 'UserController@edit');
        Route::patch('/users/{user}/edit', 'UserController@update');
    });

    Route::get('/users/change-password', 'AuthController@editPassword');
    Route::post('/users/change-password', 'AuthController@updatePassword');

    //INVENTORY
    Route::middleware(['permissions:VI'])->group(function () {
        Route::get('/inventories', 'InventoryController@index')->name('inventory.overview');
        //AJAX
        Route::post('/inventories/search', 'InventoryController@search');
    });

    Route::middleware(['permissions:AI, VI'])->group(function () {
        Route::get('/inventories/select-category', 'InventoryController@select_category')->name('inventory.select.category');
        Route::post('/inventories', 'InventoryController@create')->name('inventory.item.create');
        Route::post('/inventories/create', 'InventoryController@store')->name('inventory.item.store');
    });

    Route::middleware(['permissions:EI, VI'])->group(function () {
        Route::get('/inventories/{item}/edit', 'InventoryController@edit');
        Route::patch('/inventories/{item}/edit', 'InventoryController@update');
    });

    //STICKER
    Route::middleware(['permissions:VS'])->group(function () {
        Route::get('/inventories/{item}/stickers/{itemQuantity}', 'StickerController@index');
        //AJAX
        Route::get('/inventories/{item}/stickers/{itemQuantity}/search', 'StickerController@search');
        Route::post('/inventories/{item}/stickers/{itemQuantity}/search', 'StickerController@search');
        Route::get('/inventories/{item}/{category}', 'InventoryController@checkifexist');
    });

    Route::middleware(['permissions:VS'])->group(function () {
        Route::get('/sticker/{sticker}/edit', 'StickerController@edit');
        Route::patch('/sticker/{sticker}/edit', 'StickerController@update');
    });

    //ITEM TRANSACTION
    Route::middleware(['permissions:AVT'])->group(function () {
        Route::get('/inventories/{itemQuantity}', 'ItemTransactionController@index');
        Route::post('/inventories/{itemQuantity}', 'ItemTransactionController@store');
    });
    
    // REPORTS
    Route::middleware(['permissions:GR'])->group(function () {
        Route::get('/reports', 'ReportController@index');
        Route::get('/reports/inventory', 'ReportController@inventory');
        Route::post('/reports/inventory', 'ReportController@inventory');
        Route::get('/reports/audit-trail', 'ReportController@auditTrail');
        Route::post('/reports/audit-trail', 'ReportController@auditTrail');
    });

    // FIELDS
    Route::get('/fields', 'FieldController@index')->name('reference_library.fields');
    Route::post('/fields/create', 'FieldController@store')->name('reference_library.field.store');
    Route::get('/fields/{field}', 'FieldController@show');
    Route::get('/fields/{field}/edit', 'FieldController@edit');
    Route::patch('/fields/{field}/edit', 'FieldController@update');

    // FIELD OPTION
    Route::post('/fields/{field}', 'FieldOptionController@store');
    Route::get('/fields/{field}/options/{fieldOption}/edit', 'FieldOptionController@edit');
    Route::patch('/fields/{field}/options/{fieldOption}/edit', 'FieldOptionController@update');

    // CATEGORY
    Route::get('/categories', 'CategoryController@index')->name('reference_library.categories');
    Route::post('/categories', 'CategoryController@store')->name('reference_library.category.store');
    Route::get('/categories/{category}', 'CategoryController@show');
    Route::get('/categories/{category}/edit', 'CategoryController@edit');
    Route::patch('/categories/{category}/edit', 'CategoryController@update');

    //TRANSACTION LOGS
    Route::get('/transaction-logs/select-type', 'TransactionLogsController@selectType');
    Route::get('/transaction-logs', 'TransactionLogsController@index')->name('transaction_logs');
    Route::get('/transaction-logs/search', 'TransactionLogsController@search');
    Route::post('/transaction-logs/search', 'TransactionLogsController@search');

    //AJAX TRANSACTION LOGS
    Route::post('/transactions/{itemQuantity}/search', 'ItemTransactionController@search');
    Route::get('/transaction-logs/search/{id}', 'TransactionLogsController@filterby');
    Route::post('/transaction-logs/search/{id}', 'TransactionLogsController@filterByResult');
    
    //AUDIT TRAIL
    Route::get('/audit-trails', 'AuditTrailController@index');
    Route::get('/audit-trails/search', 'AuditTrailController@search');
    Route::post('/audit-trails/search', 'AuditTrailController@search');

});










