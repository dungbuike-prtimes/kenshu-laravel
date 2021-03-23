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


Route::get('/', "HomeController@index")->name('home');
Route::get('/login', "Auth\LoginController@showLoginForm")->name('showLoginForm');
Route::post('/auth', "Auth\LoginController@login")->name('login');
Route::get('/register', "Auth\RegisterController@showRegistrationForm")->name('showRegisterForm');
Route::post('/register', "Auth\RegisterController@create")->name('register');
Route::get('/logout', "Auth\LoginController@logout")->name('logout')->middleware('auth');

Route::group(['middleware' => 'auth', 'prefix' => 'posts'], function ()  {
    Route::get('/', "PostController@index")->name('post.index');
    Route::get('/create', "PostController@create")->name('post.create');
    Route::post('/', "PostController@store")->name('post.store');
    Route::get('/{id}/edit', "PostController@edit")->name('post.edit');
    Route::put('/{id}', "PostController@update")->name('post.update');
    Route::delete('/{id}', "PostController@destroy")->name('post.destroy');
});

Route::group(['middleware' => 'auth', 'prefix' => 'tags'], function () {
    Route::get('/create', "TagController@create")->name('tag.create');
    Route::post('/', "TagController@store")->name('tag.store');
    Route::get('/{id}/edit', "TagController@edit")->name('tag.edit');
    Route::put('/{id}', "TagController@update")->name('tag.update');
});

Route::get('posts/{id}', "PostController@show")->name('post.show');
