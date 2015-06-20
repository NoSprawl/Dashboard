<?php
class IntegrationsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {
		
	}

	public function getIntegrations() {
		$user = null;
		if(is_null(Auth::user()->parent_user_id)) {
			$user = Auth::user();
		} else {
			$user = User::find(Auth::user()->parent_user_id);
		}
		
		$integrations = $user->integrations;
		$this->layout->content = View::make('integrations.list')->with("integrations", $integrations);
	}
	
	public function getKeyNamesFor($integration_id) {
		$keyNames = DB::table('key_references')->where('integration_id', '=', $integration_id)->lists('remote_url');
		return Response::json($keyNames);
	}
	
	public function getFieldsForServiceProvider() {
		$input = Input::all();
		$integration_class_name = $input['service_provider_name'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
		return Response::json(array('service_provider_name' => $input['service_provider_name'],
																'service_provider_authorization_fields' => json_encode($service_provider_class_instance->fields),
																'service_provider_description' => json_encode($service_provider_class_instance->description)));
	}
	
	public function ensureBackgroundWorkerIsCreated($integration_id) {
		$integration = Integration::find($integration_id);
		$integration->db_integration_id = $integration->id;
		$integrationJson = $integration->toJson();
		Queue::push('ReauthenticateAndRefreshNodeList', array('message' => $integrationJson));
	}
	
	public function createIntegration() {
		$input = Input::all();
		$response = array("status" => "error", "data" => "");
		
		$rules = array();
		
		// Find out how many auth fields we are dealing with. One DB column is required for each.
		$authorization_field_count = 0;
		foreach(array_keys($input) as $field_name) {
			if(strstr($field_name, 'authorization_field_')) {
				$authorization_field_count++;
			}
			
		}
		
		// Add validation rules now that we have the above info.
		for($i = 1; $i <= $authorization_field_count; $i++) {
			$auth_validation_rule = 'required';
				
			// Different validation rule for first auth field. Reason being that
			// unique_with should only be set on one field.
			if($i == 1) {
				$auth_validation_rule .= '|unique_with:integrations';
				// j starts at 2 because we are skipping the first field itself
				for($j = 2; $j <= $authorization_field_count; $j++) {
					$auth_validation_rule .= ", " . array_keys($input)[$i];
				}
				
				// If we didn't do anything, just revert back to simply being required.
				// this would only happen if a service provider only used a single field
				// for validation.
				if($auth_validation_rule == '|unique_with:integrations,') {
					$auth_validation_rule = 'required';
				}
				
			}
			
			$rules['authorization_field_' . $authorization_field_count] = $auth_validation_rule;
			
		}
		
		$integration_class_name = $input['integration_type'] . "Integration";
		$service_provider_class_instance = new $integration_class_name();
									 
		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			$integration = new Integration;
			$integration->name = "";
			$integration->user_id = Auth::id();
			$integration->service_provider = $integration_class_name;
			
			for($i = 1; $i <= $authorization_field_count; $i++) {
				$dynamic_property_name = "authorization_field_" . $i;
				$integration->$dynamic_property_name = $input[$dynamic_property_name];
			}
			
			// This will dynamically get the integration class instance.
			$client = new $integration->service_provider();
			
			// Below line is the only place that is hardcoded to expect 2 auth fields.
			// @todo Need to refactor this to pass in a dynamic array and/or use call_user_func_array
			if($client->verifyAuthentication($integration->authorization_field_1, $integration->authorization_field_2)) {
				$integration->save();
				$integration->db_integration_id = $integration->id;
				$integrationJson = $integration->toJson();
				Queue::push('ReauthenticateAndRefreshNodeList', array('message' => $integrationJson));
				$response['status'] = "created";
				$response['data'] = $integrationJson;
			} else {
				$response['status'] = "api_error";
				$response['data'] = "Could not list instances. NoSprawl requires at least read access.";
			}
			
			return Response::json($response);
		} else {
			$response['status'] = "form_error";
			$response['data'] = "All fields are required &amp; you cannot create a duplicate integration.";
			return Response::json($response);
		}
		
	}
	
	public function deleteIntegration($id) {
		Integration::destroy($id);
		return Redirect::to('/integrations');
	}

}