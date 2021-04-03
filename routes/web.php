<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PublicDisplay\PublicDisplayController@index')->name('public.index');
Route::post('/access', 'PublicDisplay\PublicDisplayController@verifyAccess');

Route::group(['prefix' => 'public', 'middleware' => 'public'], function () {
    Route::get('/', 'PublicDisplay\PublicDisplayController@publicMenu')->name('public.menu');

    Route::group(['prefix' => 'shifts'], function () {
        Route::get('/', 'PublicDisplay\PublicDisplayController@shiftSelector')->name('shift.generator');
        Route::get('/get-data', 'PublicDisplay\SpecialityController@getSpeciality');
        Route::post('/get-client', 'PublicDisplay\ClientController@verifyClient');
        Route::post('/new', 'Dashboard\ShiftController@create');
    });

    Route::group(['prefix' => 'display'], function () {
        Route::get('/', 'PublicDisplay\PublicDisplayController@numberDisplay')->name('shift.list');
        Route::get('/list', 'PublicDisplay\PublicDisplayController@getListShifts');
        Route::post('/get', 'PublicDisplay\PublicDisplayController@getShift');
    });   
});


Route::get('dashboard/login', ['as' => 'login', 'uses' => 'Dashboard\LoginController@index']);
Route::post('dashboard/login', ['as' => 'login-dashboard', 'uses' => 'Dashboard\LoginController@store']);
Route::get('dashboard/logout', ['as' => 'logout', 'uses' => 'Dashboard\LoginController@logout']);

Route::group(['prefix' => 'dashboard', 'middleware'=> 'auth'], function() {
    Route::get('/', 'Dashboard\IndexController@index')->name('dashboard.index');
    Route::get('/data-chart', 'Dashboard\IndexController@getDataChart');

    Route::group(['prefix' => 'offices'], function () {
        Route::get('/', 'Dashboard\OfficeController@index')->name('offices.index');
        Route::get('/create', 'Dashboard\OfficeController@create');
        Route::post('/create', ['as' => 'office-store', 'uses' => 'Dashboard\OfficeController@store']);
        Route::get('/edit/{id}', 'Dashboard\OfficeController@edit');
        Route::put('/update', ['as' => 'office-update', 'uses' => 'Dashboard\OfficeController@update']);
        Route::get('/delete/{id}', 'Dashboard\OfficeController@delete');
    });

    Route::group(['prefix' => 'users-admins'], function () {
        Route::get('/', 'Dashboard\UserAdminController@index')->name('user-admins.index');
        Route::get('/create', 'Dashboard\UserAdminController@create');
        Route::post('/create', ['as' => 'user-admin-store', 'uses' => 'Dashboard\UserAdminController@store']);
        Route::get('/edit/{id}', 'Dashboard\UserAdminController@edit');
        Route::put('/update', ['as' => 'user-admin-update', 'uses' => 'Dashboard\UserAdminController@update']);
        Route::get('/delete/{id}', 'Dashboard\UserAdminController@delete');
    });

    Route::group(['prefix' => 'users-supervisors'], function () {
        Route::get('/', 'Dashboard\UserSupervisorController@index')->name('user-supervisors.index');
        Route::get('/create', 'Dashboard\UserSupervisorController@create');
        Route::post('/create', ['as' => 'user-supervisor-store', 'uses' => 'Dashboard\UserSupervisorController@store']);
        Route::get('/edit/{id}', 'Dashboard\UserSupervisorController@edit');
        Route::put('/update', ['as' => 'user-supervisor-update', 'uses' => 'Dashboard\UserSupervisorController@update']);
        Route::get('/delete/{id}', 'Dashboard\UserSupervisorController@delete');
    });

    Route::group(['prefix' => 'users-advisers'], function () {
        Route::get('/', 'Dashboard\UserAdviserController@index')->name('user-advisers.index');
        Route::get('/create', 'Dashboard\UserAdviserController@create');
        Route::post('/create', ['as' => 'user-adviser-store', 'uses' => 'Dashboard\UserAdviserController@store']);
        Route::get('/edit/{id}', 'Dashboard\UserAdviserController@edit');
        Route::put('/update', ['as' => 'user-adviser-update', 'uses' => 'Dashboard\UserAdviserController@update']);
        Route::get('/delete/{id}', 'Dashboard\UserAdviserController@delete');

        Route::get('/speciality/{id}', 'Dashboard\UserAdviserController@speciality');
        Route::post('/speciality/store', ['as' => 'speciality-store', 'uses' => 'Dashboard\UserAdviserController@storeSpeciality']);
        Route::get('/speciality/delete/{id}', 'Dashboard\UserAdviserController@deleteSpeciality');
    });

    Route::group(['prefix' => 'specialties'], function () {
        Route::get('/', 'Dashboard\SpecialtiesController@index')->name('specialties.index');
        Route::get('/create', 'Dashboard\SpecialtiesController@create');
        Route::post('/create', ['as' => 'specialty-store', 'uses' => 'Dashboard\SpecialtiesController@store']);
        Route::get('/edit/{id}', 'Dashboard\SpecialtiesController@edit');
        Route::put('/update', ['as' => 'specialty-update', 'uses' => 'Dashboard\SpecialtiesController@update']);
        Route::get('/delete/{id}', 'Dashboard\SpecialtiesController@delete');
    });

    Route::group(['prefix' => 'shifts'], function () {
        Route::get('/', 'Dashboard\DashboardController@adminShift')->name('shift.index');
        Route::post('/get', 'Dashboard\DashboardController@getShiftAdvisor');
        Route::post('/get-data', 'Dashboard\DashboardController@getDataPanel');
        Route::get('/get-advisors', 'Dashboard\DashboardController@getAdvisors');
        
        Route::post('/next', 'Dashboard\ShiftController@nextShift');
        Route::post('/status', 'Dashboard\ShiftController@changeStatusShift');
        Route::post('/reassignment', 'Dashboard\ShiftController@reassignmentShift');

        Route::post('/break', 'Dashboard\AdvisorController@break');
        Route::post('/user-status', 'Dashboard\AdvisorController@userStatusOn');
        Route::get('/reassigned', 'Dashboard\AdvisorController@reassined');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', 'Dashboard\ReportsController@index')->name('report.index');
        Route::get('/general', 'Dashboard\ReportsController@generalReport')->name('general.report');
        Route::get('/shift', 'Dashboard\ReportsController@shiftReport')->name('shift.report');
        Route::post('/advisor', 'Dashboard\ReportsController@advisorReport')->name('advisor.report');
    });

    Route::group(['prefix' => 'ads'], function () {
        Route::get('/', 'Dashboard\AdsController@index')->name('ads.index');
        Route::get('/create', 'Dashboard\AdsController@create')->name('ad.create');
        Route::post('/store', 'Dashboard\AdsController@store')->name('ad.store');
        Route::get('/delete/{id}', 'Dashboard\AdsController@delete')->name('ad.delete');
    });

    Route::group(['prefix' => 'boxes'], function () {
        Route::get('/', 'Dashboard\BoxesController@index')->name('boxes.index');
        Route::get('/create', 'Dashboard\BoxesController@create');
        Route::post('/create', ['as' => 'box-store', 'uses' => 'Dashboard\BoxesController@store']);
        Route::get('/edit/{id}', 'Dashboard\BoxesController@edit');
        Route::put('/update', ['as' => 'box-update', 'uses' => 'Dashboard\BoxesController@update']);
    });

    Route::group(['prefix' => 'clients'], function () {
        Route::get('/', 'Dashboard\ClientsController@index')->name('clients.index');
        Route::get('/create', 'Dashboard\ClientsController@create')->name('clients.create');
        Route::post('/create', ['as' => 'client-store', 'uses' => 'Dashboard\ClientsController@store']);
        Route::get('/edit/{id}', 'Dashboard\ClientsController@edit');
        Route::put('/update', ['as' => 'client-update', 'uses' => 'Dashboard\ClientsController@update']);
        Route::get('/delete/{id}', 'Dashboard\ClientsController@delete');
    });
});


Route::get('prueba', 'Dashboard\AdvisorController@adviserAvialable');

Route::get('adv/{specialityId}', 'Dashboard\AdvisorController@selectAdviser');
