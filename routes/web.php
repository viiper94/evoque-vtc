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
Route::get('/kb', 'Controller@kb')->name('kb');

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

Route::get('/evoque/rp/reports', 'RpController@reports')->name('evoque.rp.reports');
Route::any('/evoque/rp/reports/add', 'RpController@addReport')->name('evoque.rp.reports.add');
Route::any('/evoque/rp/reports/results', 'RpController@results')->name('evoque.rp.results');
Route::any('/evoque/rp/reports/results/create/{game}', 'RpController@createResults')->name('evoque.rp.results.create');
Route::any('/evoque/rp/reports/accept/{id}', 'RpController@acceptReport')->name('evoque.rp.reports.accept');
Route::get('/evoque/rp/reports/delete/{id}', 'RpController@deleteReport')->name('evoque.rp.reports.delete');
Route::any('/evoque/rp/reports/stat/{id}', 'RpController@editStat')->name('evoque.rp.stat.edit');
Route::get('/evoque/rp/{game?}', 'RpController@index')->name('evoque.rp');

Route::get('/evoque/applications', 'ApplicationsController@index')->name('evoque.applications');
Route::get('/evoque/applications/recruitment', 'ApplicationsController@recruitment')->name('evoque.applications.recruitment');
Route::get('/evoque/applications/acceptRecruitment/{id}', 'ApplicationsController@acceptRecruitment')->name('evoque.applications.accept.recruitment');
Route::get('/evoque/applications/deleteRecruitment/{id}', 'ApplicationsController@deleteRecruitment')->name('evoque.applications.delete.recruitment');
Route::get('/evoque/applications/accept/{id}', 'ApplicationsController@accept')->name('evoque.applications.accept');
Route::get('/evoque/applications/delete/{id}', 'ApplicationsController@delete')->name('evoque.applications.delete');
Route::any('/evoque/applications/vacation', 'ApplicationsController@vacation')->name('evoque.applications.vacation');
Route::any('/evoque/applications/plate', 'ApplicationsController@plate')->name('evoque.applications.plate');
Route::any('/evoque/applications/rp', 'ApplicationsController@rp')->name('evoque.applications.rp');
Route::any('/evoque/applications/nickname', 'ApplicationsController@nickname')->name('evoque.applications.nickname');
Route::any('/evoque/applications/fire', 'ApplicationsController@fire')->name('evoque.applications.fire');

Route::get('/evoque/admin/roles', 'RolesController@roles')->name('evoque.admin.roles');
Route::any('/evoque/admin/roles/edit/{id}', 'RolesController@edit')->name('evoque.admin.roles.edit');
Route::get('/evoque/admin/roles/delete/{id}', 'RolesController@delete')->name('evoque.admin.roles.delete');
Route::any('/evoque/admin/roles/add', 'RolesController@add')->name('evoque.admin.roles.add');

Route::get('/evoque/admin/users', 'UsersController@index')->name('evoque.admin.users');
Route::get('/evoque/admin/users/member/{id}', 'UsersController@setAsMember')->name('evoque.admin.users.setAsMember');

Route::get('/convoys/{public?}', 'ConvoysController@convoys')->name('convoys');
Route::get('/evoque/convoys', 'ConvoysController@index')->name('evoque.convoys');
Route::any('/evoque/admin/convoys/add', 'ConvoysController@add')->name('evoque.admin.convoy.add');
Route::get('/evoque/admin/convoys/delete/{id}', 'ConvoysController@delete')->name('evoque.admin.convoy.delete');
Route::any('/evoque/admin/convoys/edit/{id}', 'ConvoysController@edit')->name('evoque.admin.convoy.edit');

Route::get('/evoque/convoys/tab', 'ConvoysController@tab')->name('evoque.convoys.tab');
Route::any('/evoque/convoys/tab/add', 'ConvoysController@addTab')->name('evoque.convoys.tab.add');
Route::any('/evoque/convoys/tab/edit/{id}', 'ConvoysController@editTab')->name('evoque.convoys.tab.edit');
Route::any('/evoque/convoys/tab/accept/{id}', 'ConvoysController@acceptTab')->name('evoque.admin.convoys.tab.accept');
Route::get('/evoque/convoys/tab/delete/{id}', 'ConvoysController@deleteTab')->name('evoque.admin.convoys.tab.delete');
