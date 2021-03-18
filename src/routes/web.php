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


Route::get('/', "homeController@index")->name('home.index');
Route::get('/auth/login', "UserController@login")->name('auth.login');
Route::post('/auth', "UserController@auth")->name('auth');
Route::get('/auth/register', "UserController@register")->name('auth.register');
Route::post('/auth/register', "UserController@store")->name('auth.create');

Route::group(['middleware' => 'auth'], function ()  {
    Route::get('/auth/logout', "UserController@logout")->name('auth.logout');
    Route::get('/tags/create', "TagController@create")->name('tag.create');
    Route::post('/tags', "TagController@store")->name('tag.store');
    Route::get('/tags/{id}/edit', "TagController@edit")->name('tag.edit');
    Route::post('/tags/{id}', "TagController@update")->name('tag.update');

    Route::get('/posts', "PostController@index")->name('post.index');
    Route::get('/posts/create', "PostController@create")->name('post.create');
    Route::post('/posts', "PostController@store")->name('post.store');
    Route::get('/posts/{id}/edit', "PostController@edit")->name('post.edit');
    Route::get('/posts/{id}', "PostController@show")->name('post.show');
    Route::put('/posts/{id}', "PostController@update")->name('post.update');
    Route::delete('/posts/{id}', "PostController@delete")->name('post.delete');
});
