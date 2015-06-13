<?php
use OpenCloud\Rackspace;

class RackspaceCloudIntegration extends CloudIntegration {
	public $fields = [['username', 'Rackspace Username'], ['api_key', 'API Key']];
	
	public $description = '<p>This Rackspace user must have at least read access to Cloud Servers. NoSprawl will perform the following operations:</p><ul><li>Getting list of Cloud Server instances</li><li>Get list of Base Images</li><li>Get list of Clusters</li></ul>';
	
	public $availability_zones = array(
		"HKG" => "Hong Kong",
		"IAD" => "Northern Virginia",
		"ORD" => "Chicago",
		"DFW" => "Dallas",
		"SYD" => "Sydney"
	);
	
	public $authentication_endpoints = array(
		Rackspace::US_IDENTITY_ENDPOINT => "United States",
		Rackspace::UK_IDENTITY_ENDPOINT => "United Kingdom"
	);
	
	public function verifyAuthentication($username, $api_key) {
		$success = false;
		try {
			$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
				'username' => $username,
			  'apiKey' => $api_key,
			));
			
			// Might need to loop through availability zones here? Not sure.
			$computeService = $client->computeService(null, 'IAD');
			$serverList = $computeService->serverList();
			$success = true;
		} catch(Exception $e) {
			$client = new Rackspace(Rackspace::UK_IDENTITY_ENDPOINT, array(
				'username' => $username,
			  'apiKey' => $api_key,
			));
			
			// Might need to loop through availability zones here? Not sure.
			$computeService = $client->computeService(null, 'IAD');
			$serverList = $computeService->serverList();
			$success = true;
		}
		
		return $success;
	}
	
	public function list_nodes($availability_zone_name, $availability_zone_friendly_name) {
		$success = false;
		$integration = Integration::find($this->db_integration_id);
		$nodes = [];
		
		// We need to measure how often this actually matters. I wonder if UK_IDENTITY_ENDPOINT is only
		// for UK-based companies, or if it's needed to connect to Hong Kong.
		try {
			$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
				'username' => $integration->authorization_field_1,
			  'apiKey' => $integration->authorization_field_2,
			));
			
		} catch(Exception $e) {
			$client = new Rackspace(Rackspace::UK_IDENTITY_ENDPOINT, array(
				'username' => $integration->authorization_field_1,
			  'apiKey' => $integration->authorization_field_2,
			));
			
		}
		
		$computeService = $client->computeService(null, $availability_zone_name, 'publicURL');
		$serverList = $computeService->serverList();
		foreach($serverList as $server) {
			$server_ips = [];
				
			foreach($server->addresses->public as $ip) {
				array_push($server_ips, $ip->addr);
			}
			
			foreach($server->addresses->private as $ip) {
				array_push($server_ips, $ip->addr);
			}
			
			$public_dns = null;
			
			foreach($server->addresses->public as $pubdns) {
				$public_dns = $pubdns;
			}
		
			$private_dns = null;
			
			foreach($server->addresses->private as $pdns) {
				$private_dns = $pdns;
			}
			
			$server_status = 'terminated';
			if($server->status == 'ACTIVE') {
				$server_status = 'running';
			}
			
			array_push($nodes, array('service_provider_status' => $server_status,
															 'service_provider_base_image_id' => $server->image->id,
														 	 'service_provider_id' => $server->id,
														 	 'private_dns_name' => $pdns->addr,
														   'public_dns_name' => $pubdns->addr,
														   'network_interfaces' => [],
														   'service_provider_cluster_id' => null,
														 	 'service_provider_ip_addresses' => $server_ips,
														 	 'availability_zone_friendly' => $availability_zone_friendly_name,
														   'availability_zone_name' => $availability_zone_name));
		}
		
		return $nodes;
	}
	
}