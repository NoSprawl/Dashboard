<?php
use Aws\Iam\IamClient;

class AmazonWebServicesIntegration extends CloudIntegration
{
	public $fields = [['access_key_id', 'Access Key ID'], ['secret_access_key', 'Secret Access Key']];
	
	public $description = '<p>This IAM user must have access to EC2. NoSprawl will perform the following operations:</p><ul><li>Getting list of EC2 instances</li><li>Get list of Base Images</li><li>Get list of Elastic Beanstalk Clusters</li></ul></ul>';
	
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
					
					$all_ips = array();
					
					foreach($instance['NetworkInterfaces'] as $ni) {
						array_push($all_ips, $ni['PrivateIpAddress']);
						
						if(isset($ni['Association'])) {
							if(isset($ni['PublicIp'])) {
								array_push($all_ips, $ni['PublicIp']);
							}
							
						}
						
					}
					
	        array_push($nodes, array('service_provider_status' => $instance['State']['Name'],
																	 'service_provider_base_image_id' => $instance['ImageId'],
																	 'service_provider_id' => $instance['InstanceId'],
																   'private_dns_name' => $instance['PrivateDnsName'],
																   'public_dns_name' => $instance['PublicDnsName'],
																   'network_interfaces' => $interfaces,
																 	 'service_provider_cluster_id' => $sp_cluster_id,
																	 'service_provider_ip_addresses' => $all_ips));
				}
				
			}
			
		} catch(Exception $exception) {
			$nodes = false;
		}
		
		return $nodes;
	}
	
}