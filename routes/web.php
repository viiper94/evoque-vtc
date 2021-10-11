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
Route::get('auth/steam/handle', 'Auth\SteamAuthController@handle');

Route::get('auth/discord', 'Auth\DiscordAuthController@redirect')->name('auth.discord');
Route::get('auth/discord/handle', 'Auth\DiscordAuthController@handle');

Route::get('/', 'Controller@home')->name('home');
Route::any('/apply', 'Controller@apply')->name('apply');
Route::get('/rules/{type?}', 'RulesController@index')->name('rules');
Route::get('/privacy-policy', 'Controller@privacy')->name('privacy');
Route::get('/terms-of-use', 'Controller@terms')->name('terms');
Route::get('/members', 'Controller@members')->name('members');

Route::any('/kb/add', 'KbController@add')->name('kb.add');
Route::any('/kb/edit/{id}', 'KbController@edit')->name('kb.edit');
Route::get('/kb/delete/{id}', 'KbController@delete')->name('kb.delete');
Route::get('/kb/sort/{id}/{direction}', 'KbController@sort')->name('kb.sort');
Route::get('/kb/changelog/{id}', 'KbController@changelog')->name('kb.changelog');
Route::get('/kb/{id}', 'KbController@article')->name('kb.view');
Route::get('/kb', 'KbController@index')->name('kb');

Route::get('/gallery', 'GalleryController@index')->name('gallery');
Route::any('/gallery/add', 'GalleryController@add')->name('gallery.add');
Route::any('/gallery/toggle/{id}', 'GalleryController@toggle')->name('gallery.toggle');
Route::get('/gallery/delete/{id}', 'GalleryController@delete')->name('gallery.delete');

Route::any('/evoque/profile/edit', 'ProfileController@edit')->name('evoque.profile.edit');
Route::get('/evoque/profile/updateAvatar', 'ProfileController@updateAvatar')->name('evoque.profile.updateAvatar');
Route::post('/evoque/profile/checkPlate', 'ProfileController@checkPlate')->name('evoque.profile.checkPlate');
Route::get('/evoque/profile/dump', 'UsersController@dumpData');
Route::get('/evoque/profile/{id?}', 'ProfileController@profile')->name('evoque.profile');

Route::get('/evoque', 'MembersController@index')->name('evoque.members');
Route::get('/evoque/stats', 'MembersController@weekly')->name('evoque.members.weekly');
Route::post('/evoque/add', 'MembersController@add');
Route::get('/evoque/reset', 'MembersController@resetConvoys');
Route::get('/evoque/trash', 'MembersController@trash')->name('evoque.members.trash');;
Route::any('/evoque/member/{id}', 'MembersController@edit')->name('evoque.admin.members.edit');
Route::any('/evoque/member/fire/{id}/{restore?}', 'MembersController@fire')->name('evoque.admin.members.fire');
Route::any('/evoque/member/restore/{id}', 'MembersController@restore')->name('evoque.admin.members.restore');
Route::any('/evoque/member/changelog/{id}', 'MembersController@changelog')->name('evoque.admin.members.changelog');

Route::get('/evoque/rules/{type?}', 'RulesController@index')->name('evoque.rules');
Route::get('/evoque/rules/changelog/{id}', 'RulesController@changelog')->name('evoque.rules.changelog');
Route::any('/evoque/rules/edit/{id}', 'RulesController@edit')->name('evoque.rules.edit');
Route::get('/evoque/rules/delete/{id}', 'RulesController@delete')->name('evoque.rules.delete');
Route::any('/evoque/rules/add', 'RulesController@add')->name('evoque.rules.add');

Route::get('/evoque/rp/reports', 'RpController@reports')->name('evoque.rp.reports');
Route::any('/evoque/rp/reports/add', 'RpController@addReport')->name('evoque.rp.reports.add');
Route::any('/evoque/rp/reports/results', 'RpController@results')->name('evoque.rp.results');
Route::any('/evoque/rp/reports/results/create', 'RpController@createResults')->name('evoque.rp.results.create');
Route::any('/evoque/rp/reports/{id}', 'RpController@viewReport')->name('evoque.rp.reports.view');
Route::get('/evoque/rp/reports/delete/{id}', 'RpController@deleteReport')->name('evoque.rp.reports.delete');
Route::any('/evoque/rp/reports/stat/{id}', 'RpController@editStat')->name('evoque.rp.stat.edit');
Route::any('/evoque/rp/stats', 'RpController@weekly')->name('evoque.rp.weekly');
Route::get('/evoque/rp', 'RpController@index')->name('evoque.rp');

Route::get('/evoque/applications/recruitment/{id?}', 'ApplicationsController@recruitment')->name('evoque.applications.recruitment');
Route::any('/evoque/applications/acceptRecruitment/{id}', 'ApplicationsController@acceptRecruitment')->name('evoque.applications.accept.recruitment');
Route::get('/evoque/applications/deleteRecruitment/{id}', 'ApplicationsController@deleteRecruitment')->name('evoque.applications.delete.recruitment');
Route::get('/evoque/applications/edit/{id?}', 'ApplicationsController@edit')->name('evoque.applications.edit');
Route::any('/evoque/applications/accept/{id}', 'ApplicationsController@accept')->name('evoque.applications.accept');
Route::get('/evoque/applications/delete/{id}', 'ApplicationsController@delete')->name('evoque.applications.delete');
Route::any('/evoque/applications/vacation', 'ApplicationsController@vacation')->name('evoque.applications.vacation');
Route::any('/evoque/applications/rp', 'ApplicationsController@rp')->name('evoque.applications.rp');
Route::any('/evoque/applications/nickname', 'ApplicationsController@nickname')->name('evoque.applications.nickname');
Route::any('/evoque/applications/fire', 'ApplicationsController@fire')->name('evoque.applications.fire');
Route::get('/evoque/applications/{id?}', 'ApplicationsController@app')->name('evoque.applications');

Route::get('/evoque/admin/roles', 'RolesController@roles')->name('evoque.admin.roles');
Route::any('/evoque/admin/roles/edit/{id}', 'RolesController@edit')->name('evoque.admin.roles.edit');
Route::any('/evoque/admin/roles/editPermissions/{id}', 'RolesController@editPermissions')->name('evoque.admin.roles.editPermissions');
Route::get('/evoque/admin/roles/delete/{id}', 'RolesController@delete')->name('evoque.admin.roles.delete');
Route::any('/evoque/admin/roles/add', 'RolesController@add')->name('evoque.admin.roles.add');

Route::get('/evoque/admin/users', 'UsersController@index')->name('evoque.admin.users');
Route::get('/evoque/admin/users/member/{id}', 'UsersController@setAsMember')->name('evoque.admin.users.setAsMember');

Route::get('/convoy/public', 'ConvoysController@public')->name('convoy.public');
Route::get('/convoy/discord/{convoy_id}', 'ConvoysController@toDiscord')->name('convoy.discord');
Route::get('/evoque/convoys/private/{all?}', 'ConvoysController@view')->name('convoys.private');
Route::any('/evoque/convoys/add', 'ConvoysController@add')->name('evoque.admin.convoy.add');
Route::post('/evoque/convoys/addcargoman', 'ConvoysController@addCargoMan')->name('evoque.admin.convoy.add.cargoman');
Route::any('/evoque/convoys/toggle/{id}', 'ConvoysController@toggle')->name('evoque.admin.convoy.toggle');
Route::get('/evoque/convoys/delete/{id}', 'ConvoysController@delete')->name('evoque.admin.convoy.delete');
Route::any('/evoque/convoys/edit/{id}/{booking?}', 'ConvoysController@edit')->name('evoque.admin.convoy.edit');

Route::get('/evoque/convoys/tab', 'TabsController@index')->name('evoque.convoys.tab');
Route::any('/evoque/convoys/tab/add', 'TabsController@add')->name('evoque.convoys.tab.add');
Route::any('/evoque/convoys/tab/edit/{id}', 'TabsController@edit')->name('evoque.convoys.tab.edit');
Route::any('/evoque/convoys/tab/accept/{id}', 'TabsController@accept')->name('evoque.admin.convoys.tab.accept');
Route::get('/evoque/convoys/tab/delete/{id}', 'TabsController@delete')->name('evoque.admin.convoys.tab.delete');

Route::get('/evoque/convoys/plans', 'PlansController@plans')->name('evoque.convoys.plans');
Route::post('/evoque/convoys/plans', 'PlansController@quickBook');
Route::any('/evoque/convoys/plans/book/{offset}/{type}', 'PlansController@book')->name('evoque.convoys.plans.book');

Route::any('/evoque/test/add', 'TestController@add')->name('evoque.test.add');
Route::any('/evoque/test/results', 'TestController@results')->name('evoque.test.results');
Route::any('/evoque/test/results/{id}', 'TestController@memberResults')->name('evoque.test.results.member');
Route::any('/evoque/test/edit/{id?}', 'TestController@edit')->name('evoque.test.edit');
Route::get('/evoque/test/delete/{id}', 'TestController@delete')->name('evoque.test.delete');
Route::get('/evoque/test/sort/{id}/{direction}', 'TestController@sort')->name('evoque.test.sort');
Route::any('/evoque/test/{question_number?}', 'TestController@index')->name('evoque.test');

Route::any('/evoque/tuning/load', 'TrucksTuningController@load');
Route::any('/evoque/tuning/add', 'TrucksTuningController@add')->name('evoque.tuning.add');
Route::any('/evoque/tuning/edit/{id}', 'TrucksTuningController@edit')->name('evoque.tuning.edit');
Route::get('/evoque/tuning/delete/{id}', 'TrucksTuningController@delete')->name('evoque.tuning.delete');
Route::get('/evoque/tuning/{q?}', 'TrucksTuningController@index')->name('evoque.tuning');

Route::get('/evoque/discord', 'DiscordController@index')->name('evoque.discord');
