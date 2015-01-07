<?php

class ReauthenticateAndRefreshNodeList {
	public function fire($job, $data) {
		$json_array = json_decode($data['message']);
		$service_provider = new $json_array->service_provider();
		$integration = Integration::find($json_array->db_integration_id);
		$service_provider->db_integration_id = $integration->id;
		$nodes = null;
		try {
			$service_provider_nodes = $service_provider->list_nodes();
			if($service_provider_nodes) {
				$integration->status = "Confirmed";
				$integration->save();
				foreach($service_provider_nodes as $service_provider_node) {
					$node = new Node();
					$node->service_provider_uuid = $service_provider_node->service_provider_id;
					$output = new Symfony\Component\Console\Output\ConsoleOutput();
					$output->writeln(print_r("Hello"));
					$node->service_provider_base_image_id = $service_provider_node->provider_base_image_id;
					$node->description = $service_provider_node->private_dns_name . " " . $service_provider_node->public_dns_name;
					$node->integration_id = $integration->id;
					$node->owner_id = $integration->owner_id;
					$node->managed = false;
					$node->save();
				}
				
			} else {
				$integration->status = "Bad";
				$integration->save();
			}
			
			$job->release(600);
			
		} catch (Exception $e) {
			$integration->status = "Bad";
			$integration->save();
			$integration = $e;
			$job->release();
		}
		
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$output->writeln(print_r($nodes));
	}
	
}
