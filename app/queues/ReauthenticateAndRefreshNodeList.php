<?php
class ReauthenticateAndRefreshNodeList {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$json_array = json_decode($data['message']);
		$output->writeln($json_array);
		$service_provider = new $json_array->service_provider();
		# Ensure the integration still exists. If not, delete all the nodes and remove the job from the queue forever.
		try {
			$integration = Integration::findOrFail($json_array->db_integration_id);
		} catch (Exception $e) {
			$output->writeln("kill the node");
			Node::where('integration_id', '=', $json_array->db_integration_id)->delete();
			return $job->delete();
		}
		
		$user_id = $integration->user_id;
		
		$service_provider->db_integration_id = $integration->id;
		$nodes = null;
		
		$service_provider_nodes = array();
		
		foreach($service_provider->availability_zones as $availability_zone_name => $availability_zone_friendly_name) {
			// I think this could be done with a recursive array merge
			foreach($service_provider->list_nodes($availability_zone_name, $availability_zone_friendly_name) as $availability_zone_node_list) {
				array_push($service_provider_nodes, $availability_zone_node_list);
			}
			
		}
		
		$current_nodes = $integration->nodes;
		
		$all_service_provider_ids = [];
		
		if($service_provider_nodes && sizeof($service_provider_nodes) > 0) {
			$integration->status = "Confirmed";
			$integration->save();
			
			foreach($service_provider_nodes as $service_provider_node) {
				array_push($all_service_provider_ids, $service_provider_node['service_provider_id']);
				$node = Node::firstOrNew(array('service_provider_uuid' => $service_provider_node['service_provider_id'], 'integration_id' => $integration->id));
				
				// Delete the node if it's been terminated
				if($node->service_provider_status == "terminated") {
					$node->delete();
					continue;
				}
				
				$node->service_provider_status = $service_provider_node['service_provider_status'];
				$node->service_provider_uuid = $service_provider_node['service_provider_id'];
				$node->service_provider_base_image_id = $service_provider_node['service_provider_base_image_id'];
				$node->description = $service_provider_node['private_dns_name'] . " " . $service_provider_node['public_dns_name'];
				$node->owner_id = $integration->user_id;
				$node->public_dns_name = $service_provider_node['public_dns_name'];
				$node->platform = $service_provider_node['platform'];
				$node->name = "";
				try {
					$node->service_provider_availability_zone = $service_provider_node['availability_zone_name'];
					$node->friendly_availability_zone = $service_provider_node['availability_zone_friendly'];
				} catch(Exception $e) {
					$node->service_provider_availability_zone = "San Francisco!";
					$node->friendly_availability_zone = "San Francisco!";
				}
				
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
					$db_ip = IpAddress::firstOrNew(array('address' => $service_provider_ip, 'node_id' => $node->id));
					$db_ip->save();					
				}
				
				// Delete ips from the database that don't appear in client report
				IpAddress::where('address', '=', $integration->id)->where('node_id', '=', $node->id)->whereNotIn('address', $service_provider_node['service_provider_ip_addresses'])->delete();
			}
			
			// This is where we delete Nodes that no longer exist on the service provider side.
			Node::where('integration_id', '=', $integration->id)->where('owner_id', '=', $user_id)->whereNotIn('service_provider_uuid', $all_service_provider_ids)->delete();
			
		} else {
			if(sizeof($service_provider_nodes) == 0) {
				
			}
			
			$integration->status = "Bad";
			$integration->save();
		}
		
		// Do this job again in 30 seconds.
		$job->release(900);
	}
	
}
