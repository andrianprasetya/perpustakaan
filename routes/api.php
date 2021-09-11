<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'book'], function () {
    Route::get('/', ['as' => 'book.index', 'uses' => 'BookController@index']);
    Route::post('/store', ['as' => 'book.store', 'uses' => 'BookController@store']);
    Route::get('/show/{id}', ['as' => 'book.show', 'uses' => 'BookController@show']);
    Route::post('/delete', ['as' => 'book.delete', 'uses' => 'BookController@delete']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', ['as' => 'user.index', 'uses' => 'UserController@index']);
    Route::post('/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
    Route::post('/delete', ['as' => 'user.delete', 'uses' => 'UserController@delete']);
});
