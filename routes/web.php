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

Route::name('admin.')->prefix('admin')->group(function () {
    Route::redirect('/', 'admin/dashboard')->name('home');

    Route::namespace('Auth')->group(function () {
        Route::get('login', 'AdminLoginController@showLoginForm')->name('login');
        Route::post('login', 'AdminLoginController@login')->name('login.submit');
        Route::post('logout', 'AdminLoginController@logout')->name('logout');

        Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('forgot_password');
        Route::post('forgot-password', 'ForgotPasswordController@sendResetLinkEmail')->name('forgot_password.submit');

        Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm')->name('reset_password');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('reset_password.submit');
    });

    Route::namespace('Admin')->group(function () {
        Route::get('profile', 'ProfileController@index')->name('profile');
        Route::patch('profile', 'ProfileController@update')->name('profile.update');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::resource('adjustments', 'AdjustmentsController')->only(['index', 'update']);

        Route::get('active-letter-request-types', 'ActiveLetterRequestTypesController@index')->name('active-letter-request-types');
        Route::patch('letter-requests/{letter_request}/approve', 'LetterRequestFulfillController@approve')->name('letter-request.fulfill');
        Route::patch('letter-requests/{letter_request}/deny', 'LetterRequestFulfillController@deny')->name('letter-request.deny');
        Route::resource('letter-requests', 'LetterRequestController')->except(['show', 'create']);

        Route::patch('letter-request-type/{letterRequestType}/toggle-active', 'LetterRequestTypeToggleActiveController@update')->name('letter-request-types');
        Route::resource('letter-request-type', 'LetterRequestTypeController')->only(['index', 'store', 'edit', 'update']);

        Route::patch('scripts/{script}/publish', 'ScriptPublishedController@update')->name('scripts.publish')->name('publish-script');
        Route::resource('scripts', 'ScriptsController');

        Route::patch('collectors/{collector}/reset-password', 'CollectorResetPasswordController@update')->name('collector.reset-password');
        Route::patch('collectors/{collector}/toggle-active', 'CollectorToggleActiveController@update')->name('collector.toggle-active');
        Route::get('collectors/collector-options', 'CollectorOptionsController@index')->name('collector-option-lists');
        Route::resource('collectors', 'CollectorsController')->only(['index', 'store', 'edit', 'update']);

        Route::get('collector-batches/{id}/list', 'CollectorBatchListsController@index')->name('collector-batch-lists');
        Route::resource('collector-batches', 'CollectorBatchesController')->only(['index', 'store', 'destroy']);

        Route::patch('admins/{admin}/toggle-active', 'AdminToggleActiveController@update')->name('admins.toggle-active');
        Route::get('admins/admin-options', 'AdminOptionsController@index')->name('admin-options');
        Route::resource('admins', 'AdminsController')->only(['index', 'store', 'edit', 'update']);

        Route::get('roles', 'RoleListsController@index')->name('role');
        Route::resource('roles-permissions', 'RolesPermissionsController')->only(['index', 'show', 'update']);

        Route::resource('sites', 'SitesController')->except(['create', 'destroy']);

        Route::resource('sub-sites', 'SubSitesController')->only(['index', 'store', 'edit', 'update']);
    });
});

Route::group(['prefix' => 'admin/filemanager', 'middleware' => ['web', 'auth:admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::redirect('/', '/dashboard')->name('home');

Route::name('collector.')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.submit');
        Route::post('logout', 'LoginController@logout')->name('logout');
    });

    Route::namespace('Collector')->middleware(['auth', 'activeCollector'])->group(function() {
        Route::get('reset-password', 'CollectorResetPasswordController@index')->name('collector-reset-password');
        Route::post('reset-password', 'CollectorResetPasswordController@reset')->name('collector-reset-password.submit');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/transactions', 'DashboardTransactionController@index')->name('dashboard.transactions');

        Route::get('accounts', 'AccountsController@index')->name('accounts');

        Route::get('transactions', 'TransactionsController@index')->name('transactions');

        Route::resource('adjustments', 'AdjustmentsController')->only(['index', 'store', 'destroy']);

        Route::get('letter-request-types', 'LetterRequestTypesController@index');
        Route::resource('letter-requests', 'LetterRequestController')->except(['show', 'create']);

        Route::get('scripts', 'ScriptsController@index')->name('scripts');
        Route::get('scripts/{script}', 'ScriptsController@show')->name('scripts.show');
    });
});

Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

//TODO: remove this!
Route::get('testing', function () {
});