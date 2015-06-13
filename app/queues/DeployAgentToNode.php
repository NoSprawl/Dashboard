<?php
include('Net/SSH2.php');
include('Crypt/RSA.php');

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
						
			// Make sure node is running
			if($node->service_provider_status != "running") {
				return $job->release();
				$output->writeln("node is not running");
			}
			
			// TODO: Explore the below. It shouldn't be necessary and it will look weird in auth.log anyway.
			// If port 22 isn't open, requeue it and halt execution
			/*try {
				$fp = fsockopen($node->public_dns_name , 22);
				if (!$fp) {
					return $job->release();
				} else {
				  fclose($fp);
				}
				
			} catch(Exception $e) {
				return $job->release();
			}*/
									
			// Keys are always stored on S3. This is the NoS account.
			$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																									   'secret' => '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',
																									   'region' => 'us-east-1'));
			
			$all_keys = Key::where('integration_id', '=', $data['message']['integration_id'])->get();
			
			$unique_key_name = null;
			
			$cmdout = null;
			
			foreach($all_keys as $pem_key_reference) {
				if($pem_key_reference->remote_url) {
					$unique_key_name = rand(0,9999) . $pem_key_reference->remote_url;
					
					$key_bucket = (App::isLocal() ? 'devkeys.nosprawl.software' : 'keys.nosprawl.software');
					$s3->getObject(array('Bucket' => $key_bucket, 'Key' => $pem_key_reference->remote_url, 'SaveAs' => '/tmp/' . $unique_key_name));
					
					exec('chmod 400 /tmp/' . $unique_key_name);
					exec('ssh-add /tmp/' . $unique_key_name);
					
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
						continue;
					} else {
						$ssh->exec("(curl " . $latest_version_url . " > nosprawl-installer.rb) && sudo ruby nosprawl-installer.rb && rm -rf nosprawl-installer.rb");
						$result = $ssh->read();
						$exit_status = $ssh->getExitStatus();
						
						// If the exit status is 0 then the agent is deployed. All is probably well. Probably.
						if($exit_status == 0) {
							return $job->delete();
						} else {
							// 4 Things can go wrong
							//   - No curl
							//   - No ruby
							//   - No sudo priv
							//   - Cronjob file locked
							
							$able_to_download = false;
							$able_to_install = false;
							
							// If there's no curl, we can try wget
							if(strpos($result, 'curl') != false) {
								$ssh->exec("(wget -q -O - \"$@\" " . $latest_version_url . " > nosprawl-installer.rb) && sudo ruby nosprawl-installer.rb && rm -rf nosprawl-installer.rb");
								$wget_result = $ssh->read();
								$wget_exit_status = $ssh->getExitStatus();
								if($exit_status == 0) {
									$able_to_download = true;
								}
								
							} else {
								$able_to_download = true;
							}
							
							// If there's no curl AND no wget we're stuck.
							if(!$able_to_download) {
								$problem = new Problem();
								$problem->description = "Couldn't deploy agent";
								$problem->reason = "Neither cURL or Wget are installed.";
								$problem->node_id = $node->id;
								$problem->save();
								
								$remediation = new Remediation();
								$remediation->name = "Install cURL";
								$remediation->queue_name = "InstallCurlAndRetryDeployment";
								$remediation->problem_id = $problem->id;
								$remediation->save();
								
								$remediation = new Remediation();
								$remediation->name = "Install Wget";
								$remediation->queue_name = "InstallWgetAndRetryDeployment";
								$remediation->problem_id = $problem->id;
								$remediation->save();
								
								$remediation = new Remediation();
								$remediation->name = "Cancel";
								$remediation->queue_name = "CancelDeployAgentToNode";
								$remediation->problem_id = $problem->id;
								$remediation->save();
								
								return $job->delete();
							} else {
								// If there's no Ruby we're stuck.
								if(strpos($result, 'ruby') != false) {
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
								
							}
							
						}
						
					}
					
					return $job->delete();
					
				} else {
					$output->writeln("This is what we do if all we have is a password.");
					continue;
				}
				
				/*foreach($cmdout as $outputline) {
					// Detect if Ruby is installed.
					if(strpos(strtolower($outputline), "not found")) {
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
					
				}*/
				
				
				
			}
			
			return $job->delete();
    }

}
