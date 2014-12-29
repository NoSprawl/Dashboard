<?php
use Aws\Iam\IamClient;

class AmazonWebServicesIntegration
{
	public $fields = [['access_key_id', 'Access Key ID'], ['secret_access_key', 'Secret Access Key']];
	
	public $description = '<p>This IAM user must have access to EC2. NoSprawl will perform the following operations:</p><ul><li>Getting list of EC2 instances</li></ul>';
	
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
	
}