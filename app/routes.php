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

Route::get('/signup/{token}', ['uses' => 'AuthController@onboard', 'as' => 'onboard_user']);
Route::post('/onboard', ['uses' => 'AuthController@postRegistrationFromSubuser', 'as' => 'finalize_onboard']);

Route::group(array('before' => 'auth'), function()
{
	Route::get('/', ['uses' => 'CheckController@getCheck']); // This one is only here because I can't figure out how to define a default route that refers to another named route
	Route::get('check', ['uses' => 'CheckController@getCheck', 'as' => 'check']);
	Route::get('users', ['uses' => 'UsersController@listAccountUsers', 'as' => 'users']);
	Route::get('zones', ['uses' => 'ZonesController@getZones', 'as' => 'zones']);
	Route::get('integrations', ['uses' => 'IntegrationsController@getIntegrations', 'as' => 'integrations']);
	
	Route::post('integration/delete/{id}', ['uses' => 'IntegrationsController@deleteIntegration', 'as' => 'delete_integration']);
	
	Route::post('integration', ['uses' => 'IntegrationsController@createIntegration', 'as' => 'create_integration']);
	Route::post('integrations/fields', ['uses' => 'IntegrationsController@getFieldsForServiceProvider', 'as' => 'service_provider_fields']);
	Route::post('/node/placeInLimbo/{node_id}/{integration_id}', ['uses' => 'NodesController@placeNodeInLimbo']);
	Route::post('/integration/enqueueJobs/{integration_id}', ['uses' => 'IntegrationsController@ensureBackgroundWorkerIsCreated', 'as' => 'check_integration_queue']);
	Route::get('alerts', ['uses' => 'AlertsController@getAlerts', 'as' => 'alerts']);
	Route::get('settings', ['uses' => 'SettingsController@getSettings', 'as' => 'settings']);
	Route::resource('nodes', 'NodesController');
	Route::post('keys', ['uses' => 'KeysController@upload', 'as' => 'upload_key']);
	Route::post('/keyNamesFor/{integration_id}', ['uses' => 'IntegrationsController@getKeyNamesFor', 'as' => 'key_names_for_integration']);
	Route::post('subuser', ['uses' => 'AuthController@createSubuserInLimbo', 'as' => 'create_subuser']);
	Route::post('alert', ['uses' => 'AlertsController@addAlert', 'as' => 'create_alert']);
	Route::post('packages_for_node/{id}', ['uses' => 'NodesController@listPackages', 'as' => 'node_packages']);
	Route::post('vulnerability_info_for', ['uses' => 'NodesController@getVulnerabilityInfoFor', 'as' => 'vulnerability_info']);
	Route::post('/group/create', ['uses' => 'GroupsController@createGroup', 'as' => 'group_create']);
});