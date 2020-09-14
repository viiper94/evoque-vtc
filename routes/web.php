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
Route::get('auth/steam', 'Auth\SteamAuthController@redirectToSteam')->name('auth.steam');
Route::get('auth/steam/handle', 'Auth\SteamAuthController@handle')->name('auth.steam.handle');

Route::get('/', 'Controller@index')->name('home');
Route::get('/apply', 'Controller@apply')->name('apply');
Route::get('/rules', 'Controller@rulesNobodyRead')->name('rules');
Route::get('/convoys', 'Controller@convoys')->name('convoys');
Route::get('/members', 'Controller@members')->name('members');

Route::get('/evoque', 'EvoqueController@index')->name('evoque');
Route::get('/profile', 'EvoqueController@profile')->name('profile');
