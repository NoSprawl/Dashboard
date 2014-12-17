<?php
require(dirname(__FILE__).'/../integrations/AmazonWebServicesIntegration.php');
require(dirname(__FILE__).'/../integrations/RackspaceCloudIntegration.php');

class IntegrationsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {
		
	}

	public function getIntegrations() {
		$integrations = Auth::user()->integrations->toArray();
		$this->layout->content = View::make('integrations.list')->with("integrations", $integrations);
	}
	
	public function getFieldsForServiceProvider() {
		$input = Input::all();
		$integration_class_name = $input['service_provider_name'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
		return Response::json(array('service_provider_name' => $input['service_provider_name'],
																'service_provider_authorization_fields' => json_encode($service_provider_class_instance->fields)));
	}
	
	public function postIntegration() {
		$input = Input::all();
		$response = array("status" => "error", "data" => "");
		$integration_class_name = $input['integration_type'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
		
		$rules = array('authorization_field_1' => 'required',
									 'authorization_field_2' => 'required');
									 
		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			$integration = new Integration;
			$integration->name = "";
			$integration->user_id = Auth::id();
			$integration->service_provider_id = $integration_class_name;
			$integration->authorization_field_1 = $input['authorization_field_1'];
			$integration->authorization_field_2 = $input['authorization_field_2'];
			$integration->save();
			$response['status'] = "created";
			$response['data'] = $integration->toJson();
			return Response::json($response);
		}
		
		return Response::json($response);
	}

}