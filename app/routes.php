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

Route::get('register', ['uses' => 'AuthController@getRegistration', 'as' => 'signup']);
Route::post('register', 'AuthController@postRegistration');
Route::get('logout', ['uses' => 'AuthController@getLogout', 'as' => 'signout']);
Route::get('login', ['uses' => 'AuthController@getLogin', 'as' => 'signin']);
Route::post('login', 'AuthController@postLogin');

Route::group(array('before' => 'auth'), function()
{
	Route::get('/', ['uses' => 'CheckController@getCheck']); // This one is only here because I can't figure out how to define a default route that refers to another named route
	Route::get('check', ['uses' => 'CheckController@getCheck', 'as' => 'check']);
	Route::get('zones', ['uses' => 'ZonesController@getZones', 'as' => 'zones']);
	Route::get('integrations', ['uses' => 'IntegrationsController@getIntegrations', 'as' => 'integrations']);
	
	Route::post('integration/delete/{id}', ['uses' => 'IntegrationsController@deleteIntegration', 'as' => 'delete_integration']);
	
	Route::post('integration', ['uses' => 'IntegrationsController@createIntegration', 'as' => 'create_integration']);
	Route::post('integrations/fields', ['uses' => 'IntegrationsController@getFieldsForServiceProvider', 'as' => 'service_provider_fields']);
	Route::get('alerts', ['uses' => 'AlertsController@getAlerts', 'as' => 'alerts']);

	Route::resource('nodes', 'NodesController');
});