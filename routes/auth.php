<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 5/jun/2019
 * Time: 01:45
 */

//routes that require authentication
//posts
Route::get('posts/create','CreatePostController@create')->name('posts.create');

Route::post('posts/create','CreatePostController@store')->name('posts.store');

