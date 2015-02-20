<?php

class ReauthenticateAndRefreshNodeList {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		
		$json_array = json_decode($data['message']);
		$service_provider = new $json_array->service_provider();
		
		# Ensure the integration still exists. If not, delete all the nodes and remove the job from the queue forever.
		try {
			$integration = Integration::findOrFail($json_array->db_integration_id);
		} catch (Exception $e) {
			Node::where('integration_id', '=', $json_array->db_integration_id)->delete();
			$job->delete();
			return false;
		}
		
		$service_provider->db_integration_id = $integration->id;
		$nodes = null;
		
		$service_provider_nodes = $service_provider->list_nodes();
		
		$current_nodes = $integration->nodes;
		
		$all_service_provider_ids = [];
		
		if($service_provider_nodes) {
			$integration->status = "Confirmed";
			$integration->save();
			
			foreach($service_provider_nodes as $service_provider_node) {
				$node = Node::firstOrNew(array('service_provider_uuid' => $service_provider_node['service_provider_id']));
				$node->service_provider_status = $service_provider_node['service_provider_status'];
				$node->service_provider_uuid = $service_provider_node['service_provider_id'];
				array_push($all_service_provider_ids, $service_provider_node['service_provider_id']);
				$node->service_provider_base_image_id = $service_provider_node['service_provider_base_image_id'];
				$node->description = $service_provider_node['private_dns_name'] . " " . $service_provider_node['public_dns_name'];
				$node->integration_id = $integration->id;
				$node->owner_id = $integration->user_id;
				$node->managed = false;
				$node->name = "";
				
				$base_image = null;
				
				if(!$node->base_image_id) {
					$base_image = new BaseImage();
					$base_image->rollback_index = 0; // Another bad one
					$base_image->service_provider_id = "";
					$base_image->service_provider_label = "";
					$base_image->integration_id = 0;
					$base_image->save();
					$node->base_image_id = $base_image->id;
				} else {
					$base_image = BaseImage::find($node->base_image_id);
				}
				
				$node->save();
				
				$base_image->rollback_index = 0; // this is going to fuck something up at some point.
																				 // should not be setting this here
						 
				$base_image->service_provider_id = $service_provider_node['service_provider_base_image_id'];
				$base_image->service_provider_label = $service_provider_node['service_provider_base_image_id'];
				$base_image->integration_id = $integration->id;
				$base_image->save();
				
				// Get the mac info for correlation. The try/catch is dumb but will stop dupes for now.
				foreach($service_provider_node['network_interfaces'] as $network_interface) {
					try {
						$mac_address = new MacAddress();
						$mac_address->address = $network_interface;
						$mac_address->node_id = $node_id;
						$mac_address->save();
					} catch(Exception $e) {
						
					}
					
				}
				
				if(!is_null($service_provider_node['service_provider_cluster_id'])) {
					$node->service_provider_cluster_id = $service_provider_node['service_provider_cluster_id'];
					$node->save();
				}
				
			}
			
		} else {
			$integration->status = "Bad";
			$integration->save();
		}
		
		// This is where we delete Nodes that no longer exist on the service provider side.
		if(sizeof($all_service_provider_ids) > 0) {
			Node::whereNotIn('service_provider_uuid', $all_service_provider_ids)->delete();
		}
		
		$job->release(600);
	}
	
}
