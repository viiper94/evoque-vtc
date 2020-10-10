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
Route::any('/apply', 'Controller@apply')->name('apply');
Route::get('/rules/{type?}', 'RulesController@index')->name('rules');
Route::get('/members', 'Controller@members')->name('members');
// TODO KB system
// TODO member report page

Route::any('/evoque/profile/edit', 'ProfileController@edit')->name('evoque.profile.edit');
Route::get('/evoque/profile/updateAvatar', 'ProfileController@updateAvatar')->name('evoque.profile.updateAvatar');
Route::get('/evoque/profile/{id?}', 'ProfileController@profile')->name('evoque.profile');

Route::get('/evoque', 'MembersController@index')->name('evoque.members');
Route::post('/evoque/add', 'MembersController@add');
Route::any('/evoque/admin/member/{id}', 'MembersController@edit')->name('evoque.admin.members.edit');
Route::any('/evoque/admin/member/fire/{id}', 'MembersController@fire')->name('evoque.admin.members.fire');

Route::get('/evoque/rules/{type?}', 'RulesController@index')->name('evoque.rules');
Route::get('/evoque/changelog/{id}', 'RulesController@changelog')->name('evoque.rules.changelog');
Route::any('/evoque/admin/rules/edit/{id}', 'RulesController@edit')->name('evoque.rules.edit');
Route::get('/evoque/admin/rules/delete/{id}', 'RulesController@delete')->name('evoque.rules.delete');
Route::any('/evoque/admin/rules/add', 'RulesController@add')->name('evoque.rules.add');

Route::any('/evoque/rp/report', 'RpController@report')->name('evoque.rp.report');
Route::get('/evoque/rp/moderating', 'RpController@report')->name('evoque.admin.rp');
Route::get('/evoque/rp/{game?}', 'RpController@index')->name('evoque.rp');

Route::get('/evoque/admin/applications', 'ApplicationsController@index')->name('evoque.admin.applications');
Route::get('/evoque/admin/applications/acceptRecruitment/{id}', 'ApplicationsController@acceptRecruitment')->name('evoque.admin.applications.accept.recruitment');
Route::get('/evoque/admin/applications/deleteRecruitment/{id}', 'ApplicationsController@deleteRecruitment')->name('evoque.admin.applications.delete.recruitment');

Route::get('/evoque/admin/roles', 'RolesController@roles')->name('evoque.admin.roles');
Route::any('/evoque/admin/roles/edit/{id}', 'RolesController@edit')->name('evoque.admin.roles.edit');
Route::get('/evoque/admin/roles/delete/{id}', 'RolesController@delete')->name('evoque.admin.roles.delete');
Route::any('/evoque/admin/roles/add', 'RolesController@add')->name('evoque.admin.roles.add');

Route::get('/evoque/admin/users', 'UsersController@index')->name('evoque.admin.users');
Route::get('/evoque/admin/users/member/{id}', 'UsersController@setAsMember')->name('evoque.admin.users.setAsMember');

Route::get('/convoys', 'ConvoysController@convoys')->name('convoys');
Route::get('/evoque/convoys', 'ConvoysController@index')->name('evoque.convoys');
Route::get('/evoque/convoys/plans', 'ConvoysController@plans')->name('evoque.convoys.plans');
Route::get('/evoque/convoys/plans', 'ConvoysController@plans')->name('evoque.convoys.plans');
Route::any('/evoque/admin/convoys/add', 'ConvoysController@add')->name('evoque.admin.convoy.add');
Route::get('/evoque/admin/convoys/delete/{id}', 'ConvoysController@delete')->name('evoque.admin.convoy.delete');
Route::any('/evoque/admin/convoys/edit/{id}', 'ConvoysController@edit')->name('evoque.admin.convoy.edit');
