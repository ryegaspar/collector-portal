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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Route::redirect('/', 'admin/dashboard');

    Route::group(['namespace' => 'Auth'], function() {
        Route::get('login', 'AdminLoginController@showLoginForm')->name('admin.login');
        Route::post('login', 'AdminLoginController@login')->name('admin.login.submit');
        Route::post('logout', 'AdminLoginController@logout')->name('admin.logout');

        Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('admin.forgot_password');
        Route::post('forgot-password',
            'ForgotPasswordController@sendResetLinkEmail')->name('admin.forgot_password.submit');

        Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm')->name('admin.reset_password');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('admin.reset_password.submit');
    });

    Route::group(['namespace' => 'Admin'], function() {
        Route::get('profile', 'ProfileController@index')->name('admin.profile');
        Route::get('profile/show', 'ProfileController@show')->name('admin.profile.show');
        Route::patch('profile', 'ProfileController@update')->name('admin.profile.update');

        Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

        Route::get('adjustments', 'AdjustmentsController@index')->name('admin.adjustments');
        Route::get('adjustments/show', 'AdjustmentsController@show')->name('admin.adjustments.show');
        Route::patch('adjustments/{adjustment}', 'AdjustmentsController@update')->name('admin.adjustments.update');

        Route::get('users', 'UsersController@index')->name('admin.users');
        Route::get('users/show', 'UsersController@show')->name('admin.users.show');
        Route::post('users', 'UsersController@store')->name('admin.users.store');
        Route::get('users/{user}', 'UsersController@edit')->name('admin.users.edit');
        Route::patch('users/{user}', 'UsersController@update')->name('admin.users.update');
        Route::patch('users/toggle-active/{user}', 'UserToggleActiveController@update')->name('admin.users.toggleActive');

        Route::get('scripts', 'ScriptsController@index')->name('admin.scripts');
        Route::get('scripts/show/{script}', 'ScriptsController@show')->name('admin.scripts.show');
        Route::get('scripts/create', 'ScriptsController@create')->name('admin.scripts.create');
        Route::post('scripts', 'ScriptsController@store')->name('admin.scripts.store');
        Route::get('scripts/{script}', 'ScriptsController@edit')->name('admin.scripts.edit');
        Route::patch('scripts/{script}', 'ScriptsController@update')->name('admin.scripts.update');
        Route::patch('scripts/publish/{script}', 'ScriptPublishedController@update')->name('admin.scripts.publish');

        Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth:admin']], function() {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
    });
});

Route::redirect('/', '/dashboard');

Route::group(['namespace' => 'Auth'], function() {
    Route::get('login', 'LoginController@showLoginForm')->name('user.login');
    Route::post('login', 'LoginController@login')->name('user.login.submit');
    Route::post('logout', 'LoginController@logout')->name('user.logout');
});

Route::group(['namespace' => 'Users'], function() {
    Route::get('/dashboard', 'DashboardController@index')->name('user.dashboard');

    Route::get('/dashboard/transactions',
        'DashboardTransactionController@index')->name('user.dashboard.transactions');

    Route::get('/accounts', 'AccountsController@index')->name('user.accounts');
    Route::get('/accounts/show', 'AccountsController@show')->name('user.accounts.show');

    Route::get('/transactions', 'TransactionsController@index')->name('user.transactions');
    Route::get('/transactions/show', 'TransactionsController@show')->name('user.transactions.show');

    Route::get('/adjustments', 'AdjustmentsController@index')->name('user.adjustments');
    Route::get('/adjustments/show', 'AdjustmentsController@show')->name('user.adjustments.show');
    Route::post('/adjustments', 'AdjustmentsController@store')->name('user.adjustments.store');
    Route::delete('/adjustments/{adjustment}', 'AdjustmentsController@destroy')->name('user.adjustments.destroy');
});

Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

//TODO: remove this!
Route::get('testing', function () {
    dd(\App\DBR::first());
});