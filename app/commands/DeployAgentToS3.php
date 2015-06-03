<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeployAgentToS3 extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'nos:agent-deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploys a new version of the agent to S3.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		
		$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIL4QPNABCUH3PXVA',
																								   'secret' => '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',
																								   'region' => 'us-west-1'));
		
		// Make sure no blockfile exists. If it does, someone else might be trying to deploy a new agent and we need to abort.
		if($s3->doesObjectExist('agent.nosprawl.software', 'block')) {
			return false;
		}
		
		// Create blockfile
		$s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => 'block',
			'ContentType'  => 'text/plain',
			'Body' => '',
			'ACL' => 'public-read'
		));
		
		// Get current latest so we can increment it
		$result = $s3->getObject(array(
	    'Bucket' => 'agent.nosprawl.software',
	    'Key'    => 'latest'
		));
		
		$current_latest = $result['Body'] . "";
		$new_latest = $current_latest + 1;
		
		$ruby_agent = file_get_contents('deployments/agent.rb');
		
		$ruby_agent_with_version_info = "VERSION = " . $new_latest . "\n\n" . $ruby_agent;
		
		// Upload new Ruby
		$result = $s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => $new_latest . "",
			'Body' => $ruby_agent_with_version_info,
			'ContentType'  => 'text/plain',
			'ACL' => 'public-read'
		));
		
		// Delete latest
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => 'latest',
		));
			
		// Upload new latest
		$result = $s3->putObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => 'latest',
			'Body' => $new_latest . "",
			'ContentType'  => 'text/plain',
			'ACL' => 'public-read'
		));
		
		// Delete the old version
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => $current_latest,
		));
		
		// Delete blockfile
		$s3->deleteObject(array(
			'Bucket' => 'agent.nosprawl.software',
			'Key' => 'block',
		));
				
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
