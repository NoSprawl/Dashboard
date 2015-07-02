<?php
include('Net/SSH2.php');
include('Crypt/RSA.php');

class DeployAgentToNode {
    public function fire($job, $data) {
			$output = new Symfony\Component\Console\Output\ConsoleOutput();
			$node = Node::find($data['message']['node_id']);
			// Make sure node exists
			if(!$node) {
				$output->writeln("node doesn't exist anymore. no need for this job.");
				return $job->delete();
			}
			
			// Make sure node isn't terminated
			/*if($node->service_provider_status == "terminated") {
				return $job->delete();
			}*/
						
			// Make sure node is running
			if($node->service_provider_status != "running") {
				return $job->release();
				$output->writeln("node is not running");
			}
									
			// Keys are always stored on S3. This is the NoS account.
			$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																									   'secret' => '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',
																									   'region' => 'us-east-1'));
			
			$all_keys = Key::where('integration_id', '=', $data['message']['integration_id'])->get();
			
			$unique_key_name = null;
			
			$cmdout = null;
			
			// Make sure the user has added credentials for this integration
			if($all_keys->isEmpty()) {
				$problem = new Problem();
				$problem->description = "Couldn't deploy agent";
				$problem->reason = "No credentials added for this integration.<br /><br />You can manage your integration credentials on the <a href='#'>integrations</a> page.<br />Or <a href='#'>deploy manually</a>.";
				$problem->node_id = $node->id;
				$problem->long_message = true;
				$problem->save();
				
				$remediation = new Remediation();
				$remediation->name = "Cancel";
				$remediation->queue_name = "CancelDeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
			
				$remediation = new Remediation();
				$remediation->name = "Retry";
				$remediation->queue_name = "DeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
				
				return $job->delete();
			}
			
			$eventually_logged_in = false;
			
			foreach($all_keys as $pem_key_reference) {
				if($pem_key_reference->remote_url) {
					$unique_key_name = rand(0,9999) . $pem_key_reference->remote_url;
					
					$key_bucket = (App::isLocal() ? 'devkeys.nosprawl.software' : 'keys.nosprawl.software');
					$s3->getObject(array('Bucket' => $key_bucket, 'Key' => $pem_key_reference->remote_url, 'SaveAs' => '/tmp/' . $unique_key_name));
					
					exec('chmod 400 /tmp/' . $unique_key_name);
					
					$empty = null;
					
					$s3_resource_root = (App::isLocal() ? 'http://agent.nosprawl.software/dev/' : 'http://agent.nosprawl.software/');
					$latest_version = exec("curl -s " . $s3_resource_root . "latest", $empty);
					$latest_version_url = $s3_resource_root . $latest_version;
					
					$ssh = new Net_SSH2($node->public_dns_name);
					$ssh->enableQuietMode();
					$ssh->enablePTY();
					
					$key = new Crypt_RSA();
					$key->loadKey(file_get_contents('/tmp/' . $unique_key_name));
					exec('rm -rf /tmp/' . $unique_key_name);
					
					if(!$ssh->login($pem_key_reference->username, $key)) {
						$output->writeln("ssh fail.");
						continue;
					} else {
						$output->writeln("we are in ssh just fine.");
						// Let's look for any problems running sudo first.
						$ssh->exec("sudo whoami");
						$exit_status = $ssh->getExitStatus();
						if(!$exit_status && $exit_status != 0) {
							// User can't sudo without a password. We can't auto-install.
							$problem = new Problem();
							$problem->description = "Couldn't deploy agent";
							$problem->reason = "User '" . $pem_key_reference->username . "' doesn't have passwordless sudo priviliges. Please either enable  it or <a class='problem_cta_btn' href='#'>Manually deploy the NoSprawl Agent</a>";
							$problem->node_id = $node->id;
							$problem->long_message = true;
							$problem->save();
							
							$remediation = new Remediation();
							$remediation->name = "Cancel";
							$remediation->queue_name = "CancelDeployAgentToNode";
							$remediation->problem_id = $problem->id;
							$remediation->save();
							
							$remediation = new Remediation();
							$remediation->name = "Retry";
							$remediation->queue_name = "DeployAgentToNode";
							$remediation->problem_id = $problem->id;
							$remediation->save();
							
							return $job->delete();
						}
						
						$result = $ssh->read();
						$output->writeln($result);
						
						// Check for problems with curl
						$ssh->exec("curl --help");
						$curl_result = $ssh->read();
						$curl_exit_status = $ssh->getExitStatus();
						
						if($curl_exit_status != 0) {
							$problem = new Problem();
							$problem->description = "Couldn't deploy agent";
							$problem->reason = "cURL isn&rsquo;t installed.";
							$problem->node_id = $node->id;
							$problem->save();
							
							$remediation = new Remediation();
							$remediation->name = "Install cURL";
							$remediation->queue_name = "InstallCurlAndRetryDeployment";
							$remediation->problem_id = $problem->id;
							$remediation->save();
							
							$remediation = new Remediation();
							$remediation->name = "Cancel";
							$remediation->queue_name = "CancelDeployAgentToNode";
							$remediation->problem_id = $problem->id;
							$remediation->save();
							
							return $job->delete();
						}
						
						$ssh->exec("(curl " . $latest_version_url . " > nosprawl-installer.rb) && sudo ruby nosprawl-installer.rb && rm -rf nosprawl-installer.rb");
						$installer_result = $ssh->read();
						$installer_exit_status = $ssh->getExitStatus();
						
						if($installer_exit_status == 0) {
							// Everything is good.
							$node->limbo = false;
							$node->save();
							return $job->delete();
						} else {
							$problem = new Problem();
							$problem->description = "Couldn't deploy agent";
							$problem->reason = "Ruby isn't installed.";
							$problem->node_id = $node->id;
							$problem->save();
					
							$remediation = new Remediation();
							$remediation->name = "Install";
							$remediation->queue_name = "InstallRubyAndRetryDeployment";
							$remediation->problem_id = $problem->id;
							$remediation->save();
					
							$remediation = new Remediation();
							$remediation->name = "Cancel";
							$remediation->queue_name = "CancelDeployAgentToNode";
							$remediation->problem_id = $problem->id;
							$remediation->save();
						
							return $job->delete();
							
						}
						
						return $job->delete();
						
					}
					
				} else {
					$output->writeln("This is what we do if all we have is a password.");
					continue;
				}
				
			}
			
			if(!$eventually_logged_in) {
				$problem = new Problem();
				$problem->description = "Couldn't deploy agent";
				$problem->reason = "None of the credentials provided were sufficient to connect to this node. Manage your credentials on the <a href='#'>integrations</a> page.<br />Or <a href='#'>deploy manually</a>.";
				$problem->node_id = $node->id;
				$problem->long_message = true;
				$problem->save();
			
				$remediation = new Remediation();
				$remediation->name = "Cancel";
				$remediation->queue_name = "CancelDeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
		
				$remediation = new Remediation();
				$remediation->name = "Retry";
				$remediation->queue_name = "DeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
			
				return $job->delete();
			}
			
    }

}
