<?php

class DeployAgentToNode {

    public function fire($job, $data)
    {
			$node = Node::find($data['message']['node_id']);
			// Make sure node is running
			if($node->service_provider_status != "running") {
				$output->writeln("node isn't running");
				return $job->release();
			}
			
			$output = new Symfony\Component\Console\Output\ConsoleOutput();
			// If port 22 isn't open, requeue it and halt execution
			$fp = fsockopen($node->public_dns_name , 22);
			if (!$fp) {
				$output->writeln("22 isn't ready");
				return $job->release();
			} else {
			  // port is open and available. Continue with job
			  fclose($fp);
			}
			
			$output->writeln("Starting job");
			
			$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																									   'secret' => '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',
																									   'region' => 'us-east-1'));
																										 
			$all_keys = Key::where('integration_id', '=', $data['message']['integration_id'])->get();
			foreach($all_keys as $pem_key_reference) {
				$unique_key_name = rand(0,9999) . $pem_key_reference->remote_url;
				$s3->getObject(array('Bucket' => 'keys.nosprawl.software', 'Key' => $pem_key_reference->remote_url, 'SaveAs' => '/tmp/' . $unique_key_name));
				exec('chmod 400 /tmp/' . $unique_key_name);
				exec('ssh-add /tmp/' . $unique_key_name);
			}
			
			$usernames_offset = 0;
			$usernames = ['ec2-user', 'ubuntu', 'root'];
			$times_tried_with_username = 0;
			$times_to_try_per_username = 1;
			
			foreach($usernames as $username) {
				$res = shell_exec('ssh -tto UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i /tmp/' . $unique_key_name . " " . $username . "@" . $node->public_dns_name . " '(curl http://agent.nosprawl.software/`curl http://agent.nosprawl.software/latest` > nosprawl-installer.rb) && sudo ruby nosprawl-installer.rb && rm -rf nosprawl-installer.rb'");
					
					$output->writeln(print_r($res));
					$output->writeln(print_r($username));

				
			}
			
			return $job->delete();	
    }

}
