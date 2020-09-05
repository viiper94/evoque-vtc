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

Auth::routes();
Route::get('/', 'Controller@index')->name('home');
Route::get('/apply', 'Controller@apply')->name('apply');
Route::get('/rules', 'Controller@rulesNobodyRead')->name('rules');
Route::get('/convoys', 'Controller@convoys')->name('convoys');

Route::get('auth/steam', 'AuthController@redirectToSteam')->name('auth.steam');
Route::get('auth/steam/handle', 'AuthController@handle')->name('auth.steam.handle');
