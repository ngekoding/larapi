<?php

use Illuminate\Http\Request;

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');

Route::get('users', 'UserController@users');

Route::middleware(['auth:api'])->group(function() {
    Route::get('users/profile', 'UserController@profile');

    Route::post('posts', 'PostController@add');
    Route::get('posts', 'PostController@allOwn');
    Route::get('posts/{slug}', 'PostController@getDetail');
    Route::put('posts/{id}', 'PostController@update');
    Route::delete('posts/{id}', 'PostController@delete');
});
