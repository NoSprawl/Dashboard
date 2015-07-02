<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeployAgentToS3 extends Command {
	protected $name = 'nos:agent-deploy';
	protected $description = 'Deploys a new version of the agent to S3.';

	public function __construct() {
		parent::__construct();
	}

	public function fire() {
		$environment = $this->argument('environment');
		if(!($environment == "production" || $environment == "dev")) {
			return $this->error("Specify whether this should be deployed to production or development.");
		}
		
		$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIL4QPNABCUH3PXVA',
																								   'secret' => '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',
																								   'region' => 'us-west-1'));
		
		// Make sure no blockfile exists. If it does, someone else might be trying to deploy a new agent and we need to abort.
		if($s3->doesObjectExist('agent.nosprawl.software', ($environment == "production" ? 'block' : 'dev/block'))) {
			return $this->error("A deployment is already in process for " . $environment . ". Talk to your fellow devs before deploying an agent.");
		}
		
		// Create blockfile
		$s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? 'block' : 'dev/block'),
			'ContentType'  => 'text/plain',
			'Body' => '',
			'ACL' => 'public-read'
		));
		
		// Make sure the latest file exists. If not, start with 0.
		if(!$s3->doesObjectExist('agent.nosprawl.software', ($environment == "production" ? 'block' : 'dev/latest'))) {
			// Create zero latest
			$s3->putObject(array(
				'Bucket' => 'agent.nosprawl.software',
				'Key' => ($environment == "production" ? 'latest' : 'dev/latest'),
				'ContentType'  => 'text/plain',
				'Body' => '0',
				'ACL' => 'public-read'
			));
			
		}
		
		// Get current latest so we can increment it
		$result = $s3->getObject(array(
	    'Bucket' => 'agent.nosprawl.software',
	    'Key'    => ($environment == "production" ? 'latest' : 'dev/latest')
		));
		
		$current_latest = $result['Body'] . "";
		$new_latest = $current_latest + 1;
		
		$ruby_agent = file_get_contents(($environment == "production" ? 'deployments/agent.rb' : 'deployments/agent_dev.rb'));
		
		$ruby_agent_with_version_info = "VERSION = " . $new_latest . "\n\n" . $ruby_agent;
		
		// Upload new Ruby
		$result = $s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? $new_latest . "" : "dev/" . $new_latest . ""),
			'Body' => $ruby_agent_with_version_info,
			'ContentType'  => 'text/plain',
			'ACL' => 'public-read'
		));
		
		// Delete latest
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? 'latest' : 'dev/latest'),
		));
			
		// Upload new latest
		$result = $s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? 'latest' : 'dev/latest'),
			'Body' => $new_latest . "",
			'ContentType'  => 'text/plain',
			'ACL' => 'public-read'
		));
		
		// Delete the old version
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? $current_latest : 'dev/' . $current_latest),
		));
		
		// Delete blockfile
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => ($environment == "production" ? 'block' : 'dev/block'),
		));
				
	}

	protected function getArguments() {
		return array(
			array('environment', InputArgument::REQUIRED, 'Deploy to either dev or production.'),
		);
		
	}

	protected function getOptions() {
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
		
	}

}
