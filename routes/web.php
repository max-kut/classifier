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

Auth::routes();

Route::view('/', 'welcome');
Route::post('set-locale', 'LocaleController')->name('set_locale');

Route::middleware('auth')->group(function(){
    Route::get('/app', 'AppController@view')->name('app');
    Route::post('/phrases/import', 'PhrasesController@import');
    Route::get('/phrases/download', 'PhrasesController@download');
    Route::post('/phrases/calculate', 'PhrasesController@calculate');
    Route::put('/phrases/{phrase}/accept', 'PhrasesController@accept');
    Route::put('/phrases/{phrase}/reject', 'PhrasesController@reject');
    Route::resource('/phrases', 'PhrasesController')->only(['index','store','update','destroy']);
});
