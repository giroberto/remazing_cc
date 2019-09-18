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

Route::get('/stats', 'StatsController@getKpiStats');
Route::get('/price', 'StatsController@getPriceKpi');
Route::get('/review', 'StatsController@getReviewKpi');
Route::get('/rating', 'StatsController@getRatingKpi');
Route::get('/date', 'StatsController@getDateKpi');

Auth::routes();
