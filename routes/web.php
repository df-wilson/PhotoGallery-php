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

Route::get('/', 'PhotoController@home');

Route::get('/photos/upload', function () {
    return view('photos.upload');
})->middleware('auth');

Route::get('/photos/explore', 'PhotoController@explore');
Route::get('/photos/search', 'PhotoController@search');

Route::get('/photos/{id}', 'PhotoController@show');
Route::get('/photos/explore/photo/{id}', 'PhotoController@showPublicPhoto');

Route::get('/api/keywords', 'Api\KeywordController@getAll');
Route::post('/api/keywords/photo/{id}', 'Api\KeywordController@addPhotoKeyword');
Route::get('/api/photos/explore', 'Api\PhotoController@getAllPublic');
Route::get('/api/photos/explore/keyword/{id}', 'Api\PhotoController@showPublicForKeyword');
Route::get('/api/photos', 'Api\PhotoController@index');
Route::get('/api/photos/{id}', 'Api\PhotoController@show');
Route::get('/api/photos/keyword/{id}', 'Api\PhotoController@showForKeyword');
Route::post('/api/photos/upload', 'Api\PhotoController@upload');
Route::post('/api/photos/{id}/description', 'Api\PhotoController@updateDescription');
Route::post('/api/photos/{id}/title', 'Api\PhotoController@updateTitle');
Route::post('/api/photos/{id}/public', 'Api\PhotoController@updateIsPublic');

Auth::routes();
