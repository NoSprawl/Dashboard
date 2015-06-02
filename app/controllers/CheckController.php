<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$unmanaged_nodes = Auth::user()->nodes()->where('managed', '=', 'false')->where('service_provider_status', '!=', 'terminated')->get();
		$managed_nodes = Auth::user()->nodes()->where('managed', '=', 'true')->where('service_provider_status', '!=', 'terminated')->get();
		
		$integration_count = Auth::user()->integrations->count();
		
		$this->layout->content = View::make('check.list')->with('page_data', Array('unmanaged_nodes' => $unmanaged_nodes,
																																							 'managed_nodes' => $managed_nodes,
																																						 	 'cloud_provider_integration_count' => $integration_count));
	}

}