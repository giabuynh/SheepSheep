<?php
  use Illuminate\Support\Facades\Route;
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

  Route::get('/', 'App\Http\Controllers\BlogController@index')->name('index'); // call {{ route('index') }}

  Route::get('/profile/{uname}', 'App\Http\Controllers\AccountController@index')->name('profile');

  Route::get('/blog/{blogId}', 'App\Http\Controllers\BlogController@show')->name('blog')->where('blogId', '^[0-9]+$');

  Route::get('/search', 'App\Http\Controllers\BlogController@search')->name('search');

  Route::post('/login', 'App\Http\Controllers\AccountController@login')->name('login');
  Route::get('logout', function () {
    auth()->logout();
    session_start();
    session_unset();
    session_destroy();

    return Redirect::to('/');
  })->name('logout');

  Route::resource('account', 'App\Http\Controllers\AccountController');
  Route::resource('blog', 'App\Http\Controllers\BlogController');


 ?>
