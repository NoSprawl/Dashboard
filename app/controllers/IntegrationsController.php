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
		# Really save
	}

}