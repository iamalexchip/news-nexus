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

Route::get('/', 'HomeController@index')->name('home');
// Group pages
Route::get('local', 'GroupController@local')->name('local');
Route::get('sport', 'GroupController@sport')->name('sport');
Route::get('business', 'GroupController@business')->name('business');
Route::get('finance', 'GroupController@finance')->name('finance');
Route::get('politics', 'GroupController@politics')->name('politics');
Route::get('lifestyle', 'GroupController@lifestyle')->name('lifestyle');
Route::get('entertainment', 'GroupController@entertainment')->name('entertainment');
Route::get('technology', 'GroupController@technology')->name('technology');
Route::get('media', 'GroupController@media')->name('media');
Route::get('world', 'GroupController@world')->name('world');
Route::get('originals', 'GroupController@originals')->name('originals');

// load more
// TODO: SHOULD ONLY TAKE POST REQUEST
Route::prefix('more')->group(function () {
    Route::get('home', 'MoreController@home');
    Route::get('category/{id}', 'MoreController@category');
    Route::get('hot/category/{id}', 'MoreController@hotCategory');
    Route::get('top/category/{id}', 'MoreController@topCategory');
    Route::get('sidebar/category/{id}', 'MoreController@sidebar');
    Route::get('related/{post-id}', 'MoreController@related');
});

// test
Route::get('test', 'TestController@index');

Auth::routes();
// Voyager admin
Route::group(['prefix' => 'nexus'], function () {
    Voyager::routes();
});

// account page
Route::get('/account', 'HomeController@index')->name('account');
