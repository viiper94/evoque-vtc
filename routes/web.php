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

Route::get('/profile', 'EvoqueController@profile')->name('profile');
Route::get('/evoque', 'EvoqueController@index')->name('evoque');
Route::get('/evoque/rules', 'EvoqueController@rules')->name('evoque.rules');
Route::get('/evoque/convoys', 'EvoqueController@convoys')->name('evoque.convoys');
Route::get('/evoque/table', 'EvoqueController@table')->name('evoque.table');
Route::get('/evoque/rp', 'EvoqueController@rp')->name('evoque.rp');
Route::get('/evoque/applications', 'ApplicationsController@index')->name('evoque.applications');

Route::get('/evoque/admin', 'AdminController@admin')->name('evoque.admin');

Route::get('/evoque/admin/roles', 'RolesController@roles')->name('evoque.admin.roles');
Route::any('/evoque/admin/roles/edit/{id}', 'RolesController@edit')->name('evoque.admin.roles.edit');
Route::get('/evoque/admin/roles/delete/{id}', 'RolesController@delete')->name('evoque.admin.roles.delete');
Route::any('/evoque/admin/roles/add', 'RolesController@add')->name('evoque.admin.roles.add');
