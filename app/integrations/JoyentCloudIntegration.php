<?php
class JoyentCloudIntegration extends OpenStackIntegration {
	public $fields = [['api_key', 'Joyent API Key']];
	
	public $description = '<p>This Joyent user must have at least read access to Cloud Servers. NoSprawl will perform the following operations:</p><ul><li>Getting list of Cloud Server instances</li><li>Get list of Base Images</li><li>Get list of Clusters</li></ul>';
	
	public function verifyAuthentication($access_key_id, $secret_access_key) {
		$success = false;
		
		return $success;
	}
	
}