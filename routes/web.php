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

Route::group(['namespace' => 'Users'], function () {
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
});