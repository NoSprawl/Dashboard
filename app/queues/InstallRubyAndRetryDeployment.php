<?php
class InstallRubyAndRetryDeployment {
  public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$node = Node::find($data['message']['node_id']);
		
		$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																								   'secret' => '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',
																								   'region' => 'us-east-1'));
		
		$all_keys = Key::where('integration_id', '=', $data['message']['integration_id'])->get();
		
		foreach($all_keys as $pem_key_reference) {
			if($pem_key_reference->remote_url) {
				$unique_key_name = rand(0,9999) . $pem_key_reference->remote_url;
				
				$key_bucket = (App::isLocal() ? 'devkeys.nosprawl.software' : 'keys.nosprawl.software');
				$s3->getObject(array('Bucket' => $key_bucket, 'Key' => $pem_key_reference->remote_url, 'SaveAs' => '/tmp/' . $unique_key_name));
				
				// Shouldn't need these two lines at all.
				exec('chmod 400 /tmp/' . $unique_key_name);
				#exec('ssh-add /tmp/' . $unique_key_name);
				
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
					$possible_installers = ["yum", "apt-get"];
					foreach($possible_installers as $possible_installer) {
						$installer_check_result = false;
						$found = false;
						$ssh->exec("sudo " . $possible_installer . " -y install ruby");
						$install_result = $ssh->read();
						$output->writeln($install_result);
						$install_exit_status = $ssh->getExitStatus();
						if($install_exit_status == 0) {
							Queue::push('DeployAgentToNode', array('message' => array('node_id' => $node->id, 'integration_id' => $node->integration->id)));
							return $job->delete();
						}
						
					}
					
				}
				
				// If we got to this point we were unable to install Ruby automatically.
				$problem = new Problem();
				$problem->description = "Couldn't install Ruby.";
				$problem->reason = "Unable to automatically install Ruby. Please install it manually. We tried apt-get and yum.";
				$problem->node_id = $node->id;
				$problem->save();
				
				$remediation = new Remediation();
				$remediation->name = "Retry Deployment";
				$remediation->queue_name = "DeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
				
				$remediation = new Remediation();
				$remediation->name = "Cancel";
				$remediation->queue_name = "CancelDeployAgentToNode";
				$remediation->problem_id = $problem->id;
				$remediation->save();
				return $job->delete();
				
			} else {
				$output->writeln("This is what we do if all we have is a password.");
				continue;
			}
			
		}
		
  }

}
