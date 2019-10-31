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

// For loading content on a page via Ajax
// TODO: SHOULD ONLY TAKE POST REQUEST
Route::prefix('load')->group(function () {
    Route::get('home', 'LoadController@home');
    Route::get('article', 'LoadController@article');
    Route::get('category/{id}', 'LoadController@category');
    Route::get('hot/category/{id}', 'LoadController@hotCategory');
    Route::get('top/category/{id}', 'LoadController@topCategory');
    Route::get('sidebar/category/{id}', 'LoadController@sidebar');
    Route::get('related/{post-id}', 'LoadController@related');
});

// test
Route::prefix('test')->group(function () {
    Route::get('feed', 'TestController@feed');
	Route::get('feedio', 'TestController@feedio');
	Route::get('simplexml', 'TestController@simplexml');
});

Auth::routes();
// Voyager admin
Route::group(['prefix' => 'nexus'], function () {
    Voyager::routes();
});

// account page
Route::get('/account', 'HomeController@index')->name('account');
