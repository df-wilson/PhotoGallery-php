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
    return view('photos.home');
})->middleware('auth');

Route::get('/photos/upload', function () {
    return view('photos.upload');
});

Route::get('/photos/{id}', 'PhotoController@show');

Route::post('/api/keywords/photo/{id}', 'Api\KeywordController@addPhotoKeyword');
Route::get('/api/photos', 'Api\PhotoController@index');
Route::get('/api/photos/{id}', 'Api\PhotoController@show');
Route::post('/api/photos/upload', 'Api\PhotoController@upload');
Route::post('/api/photos/{id}/description', 'Api\PhotoController@updateDescription');
Route::post('/api/photos/{id}/title', 'Api\PhotoController@updateTitle');

Auth::routes();
