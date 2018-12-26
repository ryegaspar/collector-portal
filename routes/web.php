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
        Route::get('collector-hours', 'CollectorHoursController@index')->name('collector-hours');
        Route::get('todays-totals', 'TodaysTotalsController@index')->name('todays-totals');

        Route::get('calendars', 'CalendarsController@index')->name('calendars');

        Route::get('clientreports', 'ClientReportingController@index');
        Route::post('clientreports', 'ClientReportingController@compute');

        Route::get('operationalreports', 'OperationalReportsController@index')->name('operationalreports');

        Route::resource('adjustments', 'AdjustmentsController')->only(['index', 'update']);

        Route::get('active-letter-request-types', 'ActiveLetterRequestTypesController@index')->name('active-letter-request-types');
        Route::patch('letter-requests/{letter_request}/approve', 'LetterRequestFulfillController@approve')->name('letter-request.fulfill');
        Route::patch('letter-requests/{letter_request}/deny', 'LetterRequestFulfillController@deny')->name('letter-request.deny');
        Route::resource('letter-requests', 'LetterRequestController')->except(['show', 'create', 'edit']);

        Route::patch('letter-request-type/{letterRequestType}/toggle-active', 'LetterRequestTypeToggleActiveController@update')->name('letter-request-types');
        Route::resource('letter-request-type', 'LetterRequestTypeController')->only(['index', 'store', 'update']);

        Route::patch('scripts/{script}/publish', 'ScriptPublishedController@update')->name('scripts.publish')->name('publish-script');
        Route::resource('scripts', 'ScriptsController');

        Route::get('closures/sif-closures', 'SifClosuresController@index')->name('closures.sif-closures');
        Route::get('closures/closed-accounts-pdc', 'ClosedAccountsPdcController@index')->name('closures.closed-accounts-pdc');
        Route::get('closures/recalls', 'RecallController@index')->name('closures.recall');
        Route::post('closures/recalls', 'RecallController@store')->name('closures.recall.store');

        Route::patch('collectors/{collector}/reset-password', 'CollectorResetPasswordController@update')->name('collector.reset-password');
        Route::patch('collectors/{collector}/toggle-active', 'CollectorToggleActiveController@update')->name('collector.toggle-active');
        Route::get('collectors/collector-options', 'CollectorOptionsController@index')->name('collector-option-lists');
        Route::resource('collectors', 'CollectorsController')->only(['index', 'store', 'update']);

        Route::get('collector-batches/{id}/list', 'CollectorBatchListsController@index')->name('collector-batch-lists');
        Route::resource('collector-batches', 'CollectorBatchesController')->only(['index', 'store', 'destroy']);

        Route::patch('admins/{admin}/toggle-active', 'AdminToggleActiveController@update')->name('admins.toggle-active');
        Route::get('admins/admin-options', 'AdminOptionsController@index')->name('admin-options');
        Route::resource('admins', 'AdminsController')->only(['index', 'store', 'update']);

        Route::get('roles', 'RoleListsController@index')->name('role');
        Route::resource('roles-permissions', 'RolesPermissionsController')->only(['index', 'show', 'update']);

        Route::resource('sites', 'SitesController')->only(['index', 'store', 'update']);

        Route::resource('sub-sites', 'SubSitesController')->only(['index', 'store', 'update']);
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

    Route::namespace('Collector')->middleware(['auth', 'activeCollector'])->group(function () {
        Route::get('reset-password', 'CollectorResetPasswordController@index')->name('collector-reset-password');
        Route::post('reset-password',
            'CollectorResetPasswordController@reset')->name('collector-reset-password.submit');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/transactions', 'DashboardTransactionController@index')->name('dashboard.transactions');

        Route::get('collectorPDC', 'CollectorPDCController@index')->name('collectorPDC');
        Route::get('accounts', 'AccountsController@index')->name('accounts');

        Route::get('transactions', 'TransactionsController@index')->name('transactions');

        Route::resource('adjustments', 'AdjustmentsController')->only(['index', 'store', 'destroy']);

        Route::get('letter-request-types', 'LetterRequestTypesController@index');
        Route::resource('letter-requests', 'LetterRequestController')->except(['show', 'create', 'edit']);

        Route::get('scripts', 'ScriptsController@index')->name('scripts');
    });
});

Route::name('api.')->prefix('api')->namespace('Api')->group(function () {

    Route::get('clients', 'ApiController@clients');

});

Route::get('/placements/jcap', 'Placements\JcapController@index')->name('jcap-plc');
Route::post('/placements/jcap', 'Placements\JcapController@show')->name('jcap-plc.view');

//TODO: remove this!
Route::get('testing', function () {
    $columns = [ //column => select query
        'DBR_CLI_REF_NO'    => 'DBR_CLI_REF_NO',
        'ADR_NAME'          => 'ADR_NAME',
        'DBR_NO'            => 'DBR_NO',
        'DBR_NAME1'         => 'DBR_NAME1',
        'DBR_ASSIGN_DATE_O' => 'DBR_ASSIGN_DATE_O',
        'DBR_CLOSE_DATE_O'  => 'DBR_CLOSE_DATE_O',
        'DBR_ASSIGN_AMT'    => 'DBR_ASSIGN_AMT',
        'DBR_RECVD_TOT'     => 'DBR_RECVD_TOT',
        'STS_DESC'          => 'STS_DESC',
        'DBR_COM_RATE'      => 'DBR_COM_RATE',
        'DBR_CLIENT'        => 'DBR_CLIENT',
        'DBR_LAST_WORKED_O' => 'DBR_LAST_WORKED_O',
        'DBR_STATUS'        => 'DBR_STATUS',
        'count_pdc'         => '(SELECT(*) FROM CDSMSC.CHK WHERE DBR.DBR_NO = CHK.CHK_DBR_NO) as count_pdc',
        'XCR_CODE'          => "DBR_NO+'01XCR' as XCR_CODE"
    ];

    $text = implode(",", array_values($columns));

    dd($text);
});