<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$user = null;
		if(is_null(Auth::user()->parent_user_id)) {
			$user = Auth::user();
		} else {
			$user = User::find(Auth::user()->parent_user_id);
		}
		
		$unmanaged_nodes = $user->nodes()->where('managed', '=', 'false')->where('service_provider_status', '!=', 'terminated')->get();
		$managed_nodes = $user->nodes()->where('managed', '=', 'true')->where('service_provider_status', '!=', 'terminated')->get();
		
		$integration_count = $user->integrations->count();
		
		$this->layout->content = View::make('check.list')->with('page_data', Array('unmanaged_nodes' => $unmanaged_nodes,
																																							 'managed_nodes' => $managed_nodes,
																																						 	 'cloud_provider_integration_count' => $integration_count));
	}

}