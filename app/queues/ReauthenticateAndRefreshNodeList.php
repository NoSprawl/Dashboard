<?php

class ReauthenticateAndRefreshNodeList {
	public function fire($job, $data) {
		$json_array = json_decode($data['message']);
		$service_provider = new $json_array->service_provider();
		$integration = Integration::find($json_array->db_integration_id);
		
		$service_provider->db_integration_id = $integration->id;
		$nodes = null;
		
		$service_provider_nodes = $service_provider->list_nodes();
		
		if($service_provider_nodes) {
			$integration->status = "Confirmed";
			$integration->save();
			
			foreach($service_provider_nodes as $service_provider_node) {
				$node_id = 0;
				$existing_node = Node::where('service_provider_uuid', '=', $service_provider_node['service_provider_id'])->count();
				if($existing_node == 0) {
					$node = new Node();
					$node->service_provider_status = $service_provider_node['service_provider_status'];
					$node->service_provider_uuid = $service_provider_node['service_provider_id'];
					$node->service_provider_base_image_id = $service_provider_node['service_provider_base_image_id'];
					$node->description = $service_provider_node['private_dns_name'] . " " . $service_provider_node['public_dns_name'];
					$node->integration_id = $integration->id;
					$node->owner_id = $integration->user_id;
					$node->managed = false;
					$node->name = "";
					$node->save();	
					$node_id = $node->id;
				} else {
					$existing_node = Node::where('service_provider_uuid', '=', $service_provider_node['service_provider_id'])->get();
					$existing_node[0]->service_provider_status = $service_provider_node['service_provider_status'];
					$existing_node[0]->service_provider_uuid = $service_provider_node['service_provider_id'];
					$existing_node[0]->service_provider_base_image_id = $service_provider_node['service_provider_base_image_id'];
					$existing_node[0]->description = $service_provider_node['private_dns_name'] . " " . $service_provider_node['public_dns_name'];
					$existing_node[0]->integration_id = $integration->id;
					$existing_node[0]->name = "";
					$existing_node[0]->save();
					$node_id = $existing_node[0]->id;
				}
				
				foreach($service_provider_node['network_interfaces'] as $network_interface) {
					try {
						$mac_address = new MacAddress();
						$mac_address->address = $network_interface;
						$mac_address->node_id = $node_id;
						$mac_address->save();
					} catch(Exception $e) {
						
					}
					
				}
				
			}
			
		} else {
			$integration->status = "Bad";
			$integration->save();
		}
		
		$job->release(600);
	}
	
}
