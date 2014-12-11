<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('test', function(){

	$jira = App::make('Jira');

	$jira->connect(Config::get('jira.rest_version'), Config::get('jira.gh_version'), Config::get('jira.subdomain'), Config::get('jira.username'), Config::get('jira.password'));

	dd($jira->getStatuses());
});

Route::get('register', 'AuthController@getRegistration');
Route::post('register', 'AuthController@postRegistration');
Route::get('logout', 'AuthController@getLogout');
Route::get('login', 'AuthController@getLogin');
Route::post('login', 'AuthController@postLogin');
Route::get('/', ['before' => 'auth', 'uses' => 'DashboardController@getDashboard']);