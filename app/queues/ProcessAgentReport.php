<?php
class ProcessAgentReport {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$packages = $data['message']['pkginfo']['installed'];
		$matched_public_ip = false;
		$node = null;
		$matches = 0;
		$ip_node_id_count = array();
		
		$all_matched_ips = IpAddress::whereIn('address', $data['message']['ips'])->get();
		if($all_matched_ips) {
			foreach($all_matched_ips as $db_ip) {
				if(!isset($ip_node_id_count[$db_ip->node_id])) {
					$ip_node_id_count[$db_ip->node_id] = 1;
				} else {
					$ip_node_id_count[$db_ip->node_id]++;
				}
				
			}
			
		} else {
			$output->writeln("Couldn't find any ip addresses in the database from this report.");
			return $job->delete();
		}
		
		asort($ip_node_id_count, SORT_NUMERIC);
		end($ip_node_id_count);
		$matched_node_id = key($ip_node_id_count);
		
		$node = Node::find($matched_node_id);
				
		if($node) {
			$node->managed = true;
			$node->limbo = false;
			$node->hostname = $data['message']['hostname'];
			$node->virtual = $data['message']['virtual'];
			$node->last_updated = $data['message']['pkginfo']['last_updated'];
			$node->package_manager = $data['message']['pkginfo']['package_manager'];
			
			if(!$node->platform) {
				$node->platform = $data['message']['pkginfo']['platform'];
			}
			
			$node->save();
			
			$query_version_vendor_query_pairs = array();
			$packages_index = array();
			
			foreach($packages as $package_version) {
				// Get rid of debian epochs
				//https://ask.fedoraproject.org/en/question/6987/whats-the-meaning-of-the-number-which-appears-sometimes-when-i-use-yum-to-install-a-fedora-package-before-a-colon-at-the-beginning-of-the-name-of-the/
				$explode_epoch = explode(":", $package_version[1]);
				if(isset($explode_epoch[1])) {
					array_shift($explode_epoch);
					$package_version[1] = implode(":", $explode_epoch);
				}
				
				// Get rid of trailing periods. Note: Explore improving the regex on the agent because this really shouldn't happen but it does.
				$package_version[1] = rtrim($package_version[1], ".");
				
				// Get rid of downstream versioning that comes from dpkg. This should definitely be done on the agent side
				// because I am 99% sure this is a dpkg thing only. And I'm sure some ying yangs legit put dashes in version
				// numbers.
				$last_dash = strrpos($package_version[1], "-");
				if($last_dash) {
					$package_version[1] = substr($package_version[1], 0, $last_dash);
				}
				
				$package_record = Package::firstOrNew(array('name' => $package_version[0], 'node_id' => $node->id));
				if(!$package_record->version) {
					$package_record->version = $package_version[1];
				}
				
				try {
					$package_record->save();
				} catch (Exception $e) {
					$output->writeln("couldn't save record" . print_r($e->getMessage()));
				}
				
				$packages_index[$package_record->name] = $package_record;
				
				// Set up query cache. Only one query per agent report. That is crucial. Multiple queries would kill everything.
				array_push($query_version_vendor_query_pairs, array('$and' => array(array('product' => $package_record->name, 'version' => $package_record->version))));
			}

			// Do one giant query. Not one per record.
			$mongo_client = new MongoClient('mongodb://nos_app:shadowwood@capital.3.mongolayer.com:10201,capital.2.mongolayer.com:10201/vulnerabilities?replicaSet=set-5558d1202c6859fcde00176a');
			$mongo_database = $mongo_client->selectDB('vulnerabilities');
			$mongo_collection = new MongoCollection($mongo_database, 'processed');
			$mongo_query = $mongo_collection->find(array('$or' => $query_version_vendor_query_pairs))->timeout(9999999);
			
			// This is where we actually do something.
			// for some reason if i do a sort the query is so slow it times out
			//$mongo_query->sort(array('last_updated' => 1));
			
			// This won't work right if there are multiple vulnerabilties that match the prod and version.
			
			$might_alert_for = array();
						
			foreach($mongo_query as $document) {
				$packages_index[$document['product']]->vulnerability_severity = $document['risk_score'];
								
				if($document['risk_score'] > 0) {
					if($document['risk_score'] > 1) {
						$node->vulnerable = true;
					} else {
						// See if any alerts need to go out. Severe only for now.
						/*$alerts = Alert::where("owner_user_id", "=", $node->owner_id)->where("value", "=", 1)->get();
						foreach($alerts as $alert) {
							if(!isset($might_alert_for[$alert->user_id])) {
								$might_alert_for[$alert->user_id] = array();
							}
							
							array_push($might_alert_for, $document);
						}*/
						if($document['risk_score'] > 5) {
							$node->severe_vulnerable = true;
						}
						
					}
					
				}
				
				$packages_index[$document['product']]->save();
			}
			
			$node->save();
						
			//$output->writeln(print_r($might_alert_for));
			
			// Check to see if target user has been alerted about these particular issues before.
			/*foreach($might_alert_for as $user_id, $list) {
				foreach($list as $k, $list_item) {
					$query = DB::table('sent_alerts')->where('node_id', '=', $node->id);
					$query->where('package', '=', $list_item['product']);
					$query->where('version', '=', $list_item['version']);
					$count_result = $query->count();
					if($count_result > 0) {
						// If we've alerted before, remove this item from the list.
						unset($might_alert_for[$user_id][$k]);
					}
					
				}
				
			}
			
			foreach($might_alert_for as $user_id, $list) {
				foreach($list as $k, $list_item) {
					Mail::send('emails.alerts.vulnerable', $data, function($message) use ($list_item) {
						$message->from("stayup@nosprawl.com", "NoSprawl Vulnerability Alerting");
						$message->to(User::find($list_item['user_id'])->email, User::find($list_item['user_id'])->name)->subject('NoSprawl New Vulnerability Discovered');
					});
					
				}
				
			}*/
			#$output->writeln("Done");
			$job->delete();
			
		} else {
			$output->writeln("Got an agent report we couldn't match.");
			$job->delete();
		}
		
	}
	
}
