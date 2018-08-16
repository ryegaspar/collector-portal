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

Route::name('admin.')->prefix('admin')->group(function () {
    Route::redirect('/', 'admin/dashboard')->name('home');

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('login', 'AdminLoginController@showLoginForm')->name('login');
        Route::post('login', 'AdminLoginController@login')->name('login.submit');
        Route::post('logout', 'AdminLoginController@logout')->name('logout');

        Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('forgot_password');
        Route::post('forgot-password', 'ForgotPasswordController@sendResetLinkEmail')->name('forgot_password.submit');

        Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm')->name('reset_password');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('reset_password.submit');
    });

    Route::namespace('Admin')->group(function () {
        Route::get('roles', 'RoleListsController@index')->name('role');

        Route::get('profile', 'ProfileController@index')->name('profile');
        Route::patch('profile', 'ProfileController@update')->name('profile.update');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::resource('adjustments', 'AdjustmentsController')->only(['index', 'update']);

        Route::patch('users/{user}/toggle-active', 'UserToggleActiveController@update')->name('users.toggleActive');
        Route::resource('users', 'UsersController')->only(['index', 'store', 'edit', 'update']);

        Route::patch('scripts/{script}/publish', 'ScriptPublishedController@update')->name('scripts.publish');
        Route::resource('scripts', 'ScriptsController');

        Route::resource('roles-permissions', 'RolesPermissionsController');
    });
});

Route::group(['prefix' => 'admin/filemanager', 'middleware' => ['web', 'auth:admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::redirect('/', '/dashboard');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('user.login');
    Route::post('login', 'LoginController@login')->name('user.login.submit');
    Route::post('logout', 'LoginController@logout')->name('user.logout');
});

Route::name('user.')->namespace('Users')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/dashboard/transactions',
        'DashboardTransactionController@index')->name('dashboard.transactions');

    Route::get('/accounts', 'AccountsController@index')->name('accounts');
    Route::get('/accounts/show', 'AccountsController@show')->name('accounts.show');

    Route::get('/transactions', 'TransactionsController@index')->name('transactions');
    Route::get('/transactions/show', 'TransactionsController@show')->name('transactions.show');

    Route::get('/adjustments', 'AdjustmentsController@index')->name('adjustments');
    Route::get('/adjustments/show', 'AdjustmentsController@show')->name('adjustments.show');
    Route::post('/adjustments', 'AdjustmentsController@store')->name('adjustments.store');
    Route::delete('/adjustments/{adjustment}', 'AdjustmentsController@destroy')->name('adjustments.destroy');

    Route::get('/scripts', 'ScriptsController@index')->name('scripts');
    Route::get('/scripts/{script}', 'ScriptsController@show')->name('scripts.show');
});

Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

//TODO: remove this!
Route::get('testing', function () {
});