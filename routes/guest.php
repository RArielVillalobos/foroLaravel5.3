<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 5/jun/2019
 * Time: 01:45
 */
Route::get('register','RegisterController@create')->name('register');
Route::post('register','RegisterController@store')->name('store');
Route::get('register/confirmation','RegisterController@confirmation')->name('register_confirmation');
Route::get('token','TokenController@create')->name('token');
Route::post('token','TokenController@store')->name('token.store');
Route::get('login/{token}','LoginController@login')->name('login');

