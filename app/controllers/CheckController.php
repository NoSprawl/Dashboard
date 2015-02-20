<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$clusters_color_array = array('bdfcf5', 'dab2fb', 'bdd2fc', 'f5bdfc');
		
		$unmanaged_nodes = Auth::user()->nodes()->where('managed', '=', 'false')->get();
		$managed_nodes = Auth::user()->nodes()->where('managed', '=', 'true')->get();
		
		$integration_count = Auth::user()->integrations->count();
		
		$this->layout->content = View::make('check.list')->with('page_data', Array('unmanaged_nodes' => $unmanaged_nodes,
																																							 'managed_nodes' => $managed_nodes,
																																						 	 'cluster_colors' => $clusters_color_array,
																																						 	 'cloud_provider_integration_count' => $integration_count));
	}

}