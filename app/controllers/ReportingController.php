<?php

class ReportingController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}
	
	public function getReportingDataForRange($start, $end) {
		$all_data = PackageSnapshot::whereBetween('created_at', array(new \DateTime($start), new \DateTime($end)))->groupBy('id')->groupBy('application_package_id')->groupBy('created_at')->get()->sortBy('created_at');
		$riskByDateAndSeverity = array();
		foreach($all_data as $data) {
			$data->created_at = $data->created_at;
			$created_at_string = $data->created_at . "";
			if(!array_key_exists($created_at_string, $riskByDateAndSeverity)) {
				$riskByDateAndSeverity[$created_at_string] = array('all_risk' => array(), 'high_risk' => array(), 'low_risk' => array(), 'medium_risk' => array());
			}
			
			if($data->application_package_vulnerability_severity > 4.5) {
				if($data->application_package_vulnerability_severity < 7) {
					array_push($riskByDateAndSeverity[$created_at_string]['medium_risk'], $data);
				} else {
					array_push($riskByDateAndSeverity[$created_at_string]['high_risk'], $data);
				}
				
			} else {
				array_push($riskByDateAndSeverity[$created_at_string]['low_risk'], $data);
			}
			
			array_push($riskByDateAndSeverity[$created_at_string]['all_risk'], $data);
			
		}
		
		return Response::json($riskByDateAndSeverity);
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