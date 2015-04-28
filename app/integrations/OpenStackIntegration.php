<?php
class OpenStackIntegration extends CloudIntegration {
	public $fields = [['username', 'Username'], ['password', 'Password'], ['tenant_id', 'Tenant ID']];
	
	public $description = '<p>This OpenStack user must have access to view node details. NoSprawl will perform the following operations:</p><ul><li>Getting list of OpenStack Instances</li><li>Get list of Base Images</li></ul></ul>';
	
	public $db_integration_id;
	
	public function verifyAuthentication($access_key_id, $secret_access_key) {
		$success = false;
		try {
			$client = \Aws\Ec2\Ec2Client::factory(array('key' => $access_key_id, 'secret' => $secret_access_key, 'region' => 'us-east-1'));
			$res = $client->DescribeInstances();
			$success = true;
		} catch(Exception $exception) {
			
		}
		
		return $success;
	}
	
	public function list_nodes() {
		$success = false;
		$integration = Integration::find($this->db_integration_id);
		$nodes = [];
		try {
			$client = \Aws\Ec2\Ec2Client::factory(array('key' => $integration->authorization_field_1, 'secret' => $integration->authorization_field_2, 'region' => 'us-east-1'));
			$res = $client->DescribeInstances();
			$reservations = $res['Reservations'];
			$success = [];
			
			foreach($reservations as $reservation) {
				$instances = $reservation['Instances'];
				foreach ($instances as $instance) {
					$interfaces = [];
					foreach($instance['NetworkInterfaces'] as $network_interface) {
						array_push($interfaces, $network_interface['MacAddress']);
					}
					
					// Find out if we're part of a cluster
					$sp_cluster_id = null;
					try {
						foreach($instance['Tags'] as $tag) {
							if($tag['Key'] == 'elasticbeanstalk:environment-id') {
								$sp_cluster_id = $tag['Value'];
							}
					
						}
						
					} catch(Exception $e) {
					
					}
					
	        array_push($nodes, array('service_provider_status' => $instance['State']['Name'],
																	 'service_provider_base_image_id' => $instance['ImageId'],
																	 'service_provider_id' => $instance['InstanceId'],
																   'private_dns_name' => $instance['PrivateDnsName'],
																   'public_dns_name' => $instance['PublicDnsName'],
																   'network_interfaces' => $interfaces,
																 	 'service_provider_cluster_id' => $sp_cluster_id,
																 	 'node_count' => $integration->nodes->count()));
				}
				
			}
			
		} catch(Exception $exception) {
			$nodes = false;
		}
		
		return $nodes;
	}
	
}
