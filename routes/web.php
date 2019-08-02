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

Route::get('/', function () {
    return view('welcome');
});



#threads
Route::get('/threads','ThreadsController@index');
Route::get('/threads/create','ThreadsController@create');
Route::get('/threads/{channel}','ThreadsController@index');
Route::get('/threads/{channel}/{thread}','ThreadsController@show');
Route::delete('/threads/{channel}/{thread}','ThreadsController@destroy');
Route::post('/threads','ThreadsController@store');

#subscribe
Route::post('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@destroy')->middleware('auth');


#replies
Route::post('threads/{channel}/{thread}/replies','RepliesController@store');
Route::get('/threads/{channel}/{thread}/replies','RepliesController@index');
Route::delete('/replies/{reply}','RepliesController@destroy');
Route::patch('/replies/{reply}','RepliesController@update');

#favorites
Route::middleware('auth')->post('/replies/{reply}/favorites','FavoritesController@store');
Route::middleware('auth')->delete('/replies/{reply}/favorites','FavoritesController@destroy');

#profiles
Route::get('/profiles/{user}','ProfilesController@show')->name('profiles');

#notifications
Route::get('/profiles/{user}/notifications','UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');









Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

