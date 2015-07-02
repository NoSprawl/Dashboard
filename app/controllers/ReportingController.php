<?php

class ReportingController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function reportingIndex() {
		$user = null;
		if(is_null(Auth::user()->parent_user_id)) {
			$user = Auth::user();
		} else {
			$user = User::find(Auth::user()->parent_user_id);
		}
		
		$nodes = $user->nodes->groupBy('friendly_availability_zone');
		$magnitude_info = array();
		foreach($nodes as $availability_zone => $nodes) {
			foreach($nodes as $node) {
				if(!isset($magnitude_info[$node->friendly_availability_zone])) {
					$sp = new $node->integration->service_provider();
					$lat = $sp->lat_long_index[$node->service_provider_availability_zone]['lat'];
					$lon = $sp->lat_long_index[$node->service_provider_availability_zone]['lon'];
					$magnitude_info[$node->friendly_availability_zone] = array("magnitude" => 10, "lat" => $lat, "lon" => $lon, "sp_count" => array(), "flat_count" => 0);
				} else {
					$magnitude_info[$node->friendly_availability_zone]['magnitude'] = $magnitude_info[$node->friendly_availability_zone]['magnitude'] + 10;
				}
				
				if(!isset($magnitude_info[$node->friendly_availability_zone]['sp_count'][$node->integration->service_provider])) {
					$magnitude_info[$node->friendly_availability_zone]['sp_count'][$node->integration->service_provider] = 1;
					$magnitude_info[$node->friendly_availability_zone]['flat_count'] = 1;
				} else {
					$magnitude_info[$node->friendly_availability_zone]['sp_count'][$node->integration->service_provider]++;
					$magnitude_info[$node->friendly_availability_zone]['flat_count']++;
				}
				
			}
			
		}
		
		$this->layout->content = View::make('reporting.index')->with('magnitude_info', $magnitude_info);
	}

}