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

Route::prefix('admin')->group(function() {
    Route::redirect('/', 'admin/dashboard');

    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::post('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::get('dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');

    Route::get('adjustments', 'Admin\AdjustmentsController@index')->name('admin.adjustments');
    Route::get('adjustments/show', 'Admin\AdjustmentsController@show')->name('admin.adjustments.show');
    Route::patch('adjustments/{adjustment}', 'Admin\AdjustmentsController@update')->name('admin.adjustments.update');

    Route::get('users', 'Admin\UsersController@index')->name('admin.users');
    Route::get('users/show', 'Admin\UsersController@show')->name('admin.users.show');
});

Route::redirect('/', '/dashboard');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
Route::post('login', 'Auth\LoginController@login')->name('user.login.submit');
Route::post('logout', 'Auth\LoginController@logout')->name('user.logout');

Route::get('/dashboard', 'Users\DashboardController@index')->name('user.dashboard');

Route::get('/dashboard/transactions', 'Users\DashboardTransactionController@index')->name('user.dashboard.transactions');

Route::get('/accounts', 'Users\AccountsController@index')->name('user.accounts');
Route::get('/accounts/show', 'Users\AccountsController@show')->name('user.accounts.show');

Route::get('/transactions', 'Users\TransactionsController@index')->name('user.transactions');
Route::get('/transactions/show', 'Users\TransactionsController@show')->name('user.transactions.show');

Route::get('/adjustments', 'Users\AdjustmentsController@index')->name('user.adjustments');
Route::post('/adjustments', 'Users\AdjustmentsController@store')->name('user.adjustments.store');
Route::get('/adjustments/show', 'Users\AdjustmentsController@show')->name('user.adjustments.show');
Route::delete('/adjustments/{adjustment}', 'Users\AdjustmentsController@destroy')->name('user.adjustments.destroy');



Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

//TODO: remove this!
Route::get('testing', function() {
    dd(\App\DBR::first());
});