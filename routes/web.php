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

Route::get('/', 'PublicDisplay\PublicDisplayController@index');
Route::post('/access', 'PublicDisplay\PublicDisplayController@verifyAccess');

Route::prefix('public')->group(function () {
    Route::get('/', 'PublicDisplay\PublicDisplayController@publicMenu');

    Route::prefix('shift')->group(function () {
        Route::get('/', 'PublicDisplay\PublicDisplayController@shiftSelector')->name('shift.generator');
        Route::get('/get-data', 'PublicDisplay\SpecialityController@getSpeciality');
        Route::post('/get-client', 'PublicDisplay\ClientController@verifyClient');
        Route::post('/new', 'Dashboard\ShiftController@create');
    });

    Route::prefix('display')->group(function () {
        Route::get('/', 'PublicDisplay\PublicDisplayController@numberDisplay')->name('shift.list');
        Route::get('/list', 'PublicDisplay\PublicDisplayController@getListShifts');
        Route::post('/get', 'PublicDisplay\PublicDisplayController@getShift');
    });   
});


Route::get('dashboard/login', ['as' => 'login', 'uses' => 'Dashboard\LoginController@index']);
Route::post('dashboard/login', ['as' => 'login-dashboard', 'uses' => 'Dashboard\LoginController@store']);
Route::get('dashboard/logout', ['as' => 'logout', 'uses' => 'Dashboard\LoginController@logout']);

Route::group(['prefix' => 'dashboard', 'middleware'=> 'auth'], function() {
    Route::get('/', 'Dashboard\IndexController@index');

    Route::group(['prefix' => 'offices'], function () {
        Route::get('/', 'Dashboard\OfficeController@index');
        Route::get('/create', 'Dashboard\OfficeController@create');
        Route::post('/create', ['as' => 'office-store', 'uses' => 'Dashboard\OfficeController@store']);
    });

    Route::group(['prefix' => 'users-admins'], function () {
        Route::get('/', 'Dashboard\UserAdminController@index');
        Route::get('/create', 'Dashboard\UserAdminController@create');
        Route::post('/create', ['as' => 'user-admin-store', 'uses' => 'Dashboard\UserAdminController@store']);
    });

    Route::group(['prefix' => 'users-supervisors'], function () {
        Route::get('/', 'Dashboard\UserSupervisorController@index');
        Route::get('/create', 'Dashboard\UserSupervisorController@create');
        Route::post('/create', ['as' => 'user-supervisor-store', 'uses' => 'Dashboard\UserSupervisorController@store']);
    });

    Route::group(['prefix' => 'users-advisers'], function () {
        Route::get('/', 'Dashboard\UserAdviserController@index');
        Route::get('/create', 'Dashboard\UserAdviserController@create');
        Route::post('/create', ['as' => 'user-adviser-store', 'uses' => 'Dashboard\UserAdviserController@store']);
    });

    Route::group(['prefix' => 'specialties'], function () {
        Route::get('/', 'Dashboard\SpecialtiesController@index');
        Route::get('/create', 'Dashboard\SpecialtiesController@create');
        Route::post('/create', ['as' => 'specialty-store', 'uses' => 'Dashboard\SpecialtiesController@store']);
    });


    Route::group(['prefix' => 'shift'], function () {
        Route::get('/', 'Dashboard\DashboardController@adminShift');
        Route::post('/get', 'Dashboard\DashboardController@getShiftAdvisor');
        Route::post('/get-data', 'Dashboard\DashboardController@getDataPanel');
        Route::get('/get-advisors', 'Dashboard\DashboardController@getAdvisors');
        
        Route::post('/next', 'Dashboard\ShiftController@nextShift');
        Route::post('/status', 'Dashboard\ShiftController@changeStatusShift');
        Route::post('/reassignment', 'Dashboard\ShiftController@reassignmentShift');
    });
});




Route::get('prueba/{specialityId}', 'AdvisorController@selectAdvisor');
