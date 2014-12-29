<?php

class RackspaceCloudIntegration
{
	public $fields = [['username', 'Rackspace Username'], ['api_key', 'Rackspace API Key']];
	
	public $description = '<p>This Rackspace user must have at least read access to Cloud Servers. NoSprawl will perform the following operations:</p><ul><li>Getting list of Cloud Server instances</li></ul>';
	
	public function verifyAuthentication($access_key_id, $secret_access_key) {
		$success = false;
		
		return $success;
	}
	
}