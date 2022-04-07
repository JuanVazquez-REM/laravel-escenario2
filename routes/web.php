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

Route::get('/', function () {
    return view('auth/register');
});

Route::get('/login', function () {
    return view('auth/login');
})->name('event.login');

Route::get('/home', function () {
    return view('home');
});

Route::get('verificacion/{id}',function($id){
    return view('verificacion',['id'=>$id]);
})->name('verificacion');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
