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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

//si lo dejamos sin la expresion regular fallara porque laravel va a creer que la ruta en el archivo auth.php /post/create es un id
//lo que podemos hacer es hacer una expresion regular o cambiar el orden de las rutas, poniendo las publicas ultimo
//optaremos por las dos opciones
Route::get('posts/{post}-{slug}','PostController@show')->name('posts.show')->where('post','[0-9]+');
