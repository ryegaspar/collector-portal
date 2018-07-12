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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
Route::post('login', 'Auth\LoginController@login')->name('user.login.submit');
Route::post('logout', 'Auth\LoginController@logout')->name('user.logout');

Route::redirect('/', '/dashboard');

Route::get('/dashboard', 'Users\DashboardController@index')->name('user.dashboard');

Route::get('/dashboard/transactions', 'Users\DashboardTransactionController@index')->name('user.dashboard.transactions');

Route::get('/accounts', 'Users\AccountsController@index')->name('user.accounts');
Route::get('/accounts/show', 'Users\AccountsController@show')->name('user.accounts.show');

Route::get('/transactions', 'Users\TransactionsController@index')->name('user.transactions');
Route::get('/transactions/show', 'Users\TransactionsController@show')->name('user.transactions.show');

Route::get('/adjustments', 'Users\AdjustmentsController@index')->name('user.adjustments');



Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

Route::prefix('admin')->group(function() {
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');

    Route::get('dashboard', 'AdminDashboardController@index')->name('admin.dashboard');
});

//TODO: remove this!
Route::get('testing', function() {
    dd(\App\DBR::first());
});