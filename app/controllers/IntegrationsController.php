<?php
require(dirname(__FILE__).'/../integrations/AmazonWebServicesIntegration.php');
require(dirname(__FILE__).'/../integrations/RackspaceCloudIntegration.php');

class IntegrationsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getIntegrations() {
		$this->layout->content = View::make('integrations.list');
	}
	
	public function getFieldsForServiceProvider() {
		$input = Input::all();
		$integration_class_name = $input['service_provider_name'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
		return Response::json(array('service_provider_name' => $input['service_provider_name'], 'service_provider_authorization_fields' => json_encode($service_provider_class_instance->fields)));
	}
	
	public function postIntegration() {
		$input = Input::all();
		$integration_class_name = $input['service_provider_name'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
		
		$rules = array();
		
		foreach($service_provider_class_instance->fields as $field) {
			array_push($rules, array($field[0] => 'required'));
		}

		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			$integration = new Integration;
			$integration->user_id = Auth::user()->getId();
			$integration->service_provider_id = $integration_class_name;
			$integration->authorization_field_1 = $input[$service_provider_class_instance[0][0]];
			$integration->authorization_field_2 = $input[$service_provider_class_instance[0][1]];
			$user->save();
			return Redirect::to('/integrations');
		}
		
		return Redirect::to('integrations')->withErrors($validator);
	}

}