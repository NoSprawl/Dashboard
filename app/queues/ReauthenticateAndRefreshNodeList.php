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
			return $job->delete();
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
				array_push($all_service_provider_ids, $service_provider_node['service_provider_id']);
				
				$node = Node::firstOrNew(array('service_provider_uuid' => $service_provider_node['service_provider_id'], 'integration_id' => $integration->id));
				$node->service_provider_status = $service_provider_node['service_provider_status'];
				$node->service_provider_uuid = $service_provider_node['service_provider_id'];
				$node->service_provider_base_image_id = $service_provider_node['service_provider_base_image_id'];
				$node->description = $service_provider_node['private_dns_name'] . " " . $service_provider_node['public_dns_name'];
				$node->owner_id = $integration->user_id;
				$node->public_dns_name = $service_provider_node['public_dns_name'];
				$node->name = "";
				
				// This should be handled in the DB schema. Default val of false.
				if($node->managed == null) {
					$node->managed = false;
				}
								
				$base_image = BaseImage::firstOrNew(array('integration_id' => $integration->id,
																								  'service_provider_id' => $service_provider_node['service_provider_base_image_id'],
																								  'integration_id' => $integration->id,
																									'service_provider_label' => $service_provider_node['service_provider_base_image_id']));
				
				$base_image->rollback_index = 0; // Should be handled by schema or just removed. Stupid.
				$base_image->save();
				
				$node->base_image_id = $base_image->id;
				
				if(!is_null($service_provider_node['service_provider_cluster_id'])) {
					$node->service_provider_cluster_id = $service_provider_node['service_provider_cluster_id'];
				}
				
				$node->save();
				
				foreach($service_provider_node['service_provider_ip_addresses'] as $service_provider_ip) {
					$db_ip = IPAddress::firstOrNew(array('address' => $service_provider_ip, 'node_id' => $node->id));
					$db_ip->save();					
				}
				
			}
			
		} else {
			$integration->status = "Bad";
			$integration->save();
		}
		
		//This is where we delete Nodes that no longer exist on the service provider side.
		Node::where('integration_id', '=', $integration->id)->whereNotIn('service_provider_uuid', $all_service_provider_ids)->delete();
		$job->release(600);
	}
	
}
