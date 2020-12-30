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
  Route::post('/autoload', 'App\Http\Controllers\BlogController@autoload')->name('autoload');
  Route::post('/findExistedUser', 'App\Http\Controllers\AccountController@findExistedUser')->name('findExistedUser');
  Route::post('/getAccount', 'App\Http\Controllers\AccountController@getAccount')->name('getAccount');
  Route::post('/register', 'App\Http\Controllers\AccountController@register')->name('register');
  Route::post('/editProfile', 'App\Http\Controllers\AccountController@editProfile')->name('editProfile');
  Route::post('/addBlog', 'App\Http\Controllers\BlogController@addBLog')->name('addBlog');
  Route::post('/editBlog', 'App\Http\Controllers\BlogController@editBlog')->name('editBlog');
  Route::post('/deleteBlog', 'App\Http\Controllers\BlogController@deleteBlog')->name('deleteBlog');
  Route::post('/result', 'App\Http\Controllers\BlogController@result')->name('result');
  Route::post('/rate', 'App\Http\Controllers\RateController@rate')->name('rate');
  Route::post('/addComment', 'App\Http\Controllers\CommentController@addComment')->name('addComment');
  Route::post('/deleteComment', 'App\Http\Controllers\CommentController@deleteComment')->name('deleteComment');

 ?>
