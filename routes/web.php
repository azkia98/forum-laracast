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
// auth()->loginUsingId(1,true);
Route::get('/', function () {
    return view('welcome');
});



#threads
Route::get('/threads','ThreadsController@index')->name('threads');
Route::get('/threads/create','ThreadsController@create');
Route::get('/threads/{channel}','ThreadsController@index');
Route::get('/threads/{channel}/{thread}','ThreadsController@show');
Route::delete('/threads/{channel}/{thread}','ThreadsController@destroy');
Route::post('/threads','ThreadsController@store')->middleware('must-be-confirmed');

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

#users
Route::get('/profiles/{user}','ProfilesController@show')->name('profiles');
Route::get('/register/confirm','Api\RegisterConfirmationController@index')->name('register.confirm');

#notifications
Route::get('/profiles/{user}/notifications','UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');


#avatars
Route::post('users/{user}/avatar','Api\UserAvatarController@store')->middleware('auth')->name('avatar');





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

