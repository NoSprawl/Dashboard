<?php
class DeployAgentToNode {

    public function fire($job, $data)
    {
			$output = new Symfony\Component\Console\Output\ConsoleOutput();
			$node = Node::find($data['message']['node_id']);
			// Make sure node exists
			if(!$node) {
				$output->writeln("node doesn't exist anymore. no need for this job.");
				return $job->delete();
			}
			
			// Make sure node isn't terminated
			if($node->service_provider_status == "terminated") {
				return $job->delete();
			}
			
			$output->writeln("checking if node is up " . $node->service_provider_status);
			
			// Make sure node is running
			if($node->service_provider_status != "running") {
				return $job->release();
				$output->writeln("node is not running");
			}
			
			// If port 22 isn't open, requeue it and halt execution
			try {
				$output->writeln("trying to connect to port 22 on " . $node->public_dns_name);
				$fp = fsockopen($node->public_dns_name , 22);
				if (!$fp) {
					return $job->release();
				} else {
				  fclose($fp);
				}
				
			} catch(Exception $e) {
				return $job->release();
			}
			
			$output->writeln("Was able to connect to port 22");
						
			// Keys are always stored on S3. This is the NoS account.
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
			
			$usernames = ['ec2-user', 'ubuntu', 'root'];
			
			foreach($usernames as $username) {
				$cmdout = "";
				$res = exec('ssh -tto UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i /tmp/' . $unique_key_name . " " . $username . "@" . $node->public_dns_name . " '(curl http://agent.nosprawl.software/`curl http://agent.nosprawl.software/latest` > nosprawl-installer.rb) && sudo ruby nosprawl-installer.rb && rm -rf nosprawl-installer.rb'", $cmdout, $cmdres);
				
				// Detect if Ruby is installed.
				foreach($cmdout as $outputline) {
					if(strpos(strtolower($outputline), "command not found")) {
						// Ruby isn't installed. Let's install it.
						$output->writeln("no ruby was found");
						$possible_installers = ["yum", "apt-get"];
						foreach($possible_installers as $possible_installer) {
							$installer_check_result = false;
							$found = false;
							exec('ssh -tto UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i /tmp/' . $unique_key_name . " " . $username . "@" . $node->public_dns_name . " 'sudo " . $possible_installer . " -y install ruby'", $installer_check_output, $installer_result);
							
							if(!$installer_result) {
								return $job->release();	
							}
							
						}
						
					}
					
				} 
				
			}
			
			return $job->delete();	
    }

}
