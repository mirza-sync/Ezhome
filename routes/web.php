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
Route::get('/','HomestayController@index');
// Route::group(['middleware' => ['web','guest']], function ($route) {
//     $route->post('/booking/checkout','BookingController@checkout')->name('mycheckout');
//     $route->post('/booking/checkout2','BookingController@checkoutChosen')->name('mycheckoutChosen');
//     $route->post('/booking/scroll','BookingController@scroll')->name('myscroll');
//  });
Route::post('/booking/checkout','BookingController@checkout')->name('mycheckout');
Route::post('/booking/checkout2','BookingController@checkoutChosen')->name('mycheckoutChosen');
Route::post('/booking/scroll','BookingController@scroll')->name('myscroll');

Route::prefix('landlord')->group(function()
{   
    Route::get('/register', 'Auth\LandlordAuthController@showRegisterForm')->name('landlord.register');
    Route::post('/register', 'Auth\LandlordAuthController@register')->name('landlord.register');
    Route::get('/login', 'Auth\LandlordAuthController@showLoginForm')->name('landlord.login'); 
    Route::post('/login', 'Auth\LandlordAuthController@login')->name('landlord.login.submit');
    Route::get('/dashboard', 'LandlordController@index')->name('landlord.dashboard');
});

Route::prefix('admin')->group(function()
{   
    Route::get('/register', 'AdminController@showRegisterForm')->name('admin.register');
    Route::post('/register', 'AdminController@register')->name('admin.register');
    Route::get('/login', 'AdminController@showLoginForm')->name('admin.login'); 
    Route::post('/login', 'AdminController@login')->name('admin.login.submit');
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/users', 'AdminController@all1')->name('admin.users');
    Route::get('/landlords', 'AdminController@all2')->name('admin.landlords');
    Route::get('/facilities', 'AdminController@all3')->name('admin.facilities');
});

Route::get('/booking/{id}/create','BookingController@create')->name('booking.homestay');

Route::get('/booking/search','HomestayController@search');

Auth::routes();

Route::resource('booking','BookingController');
Route::resource('homestay','HomestayController');
Route::resource('landlord','LandlordController');
Route::resource('facility','FacilityController');

Route::get('/removeFac/{hs_id}/{fac_id}','HomestayController@removeFacility');
Route::post('/assignFac','HomestayController@assignFacility');
Route::get('/home', 'HomeController@index');





