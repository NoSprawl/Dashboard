<?php
class ProcessAgentReport {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$packages = $data['message']['pkginfo']['installed'];
		$matched_public_ip = false;
		$node = null;
		$matches = 0;
		$ip_node_id_count = array();
		
		foreach($data['message']['ips'] as $server_report_ip) {
			$db_ips = IPAddress::where('address', '=', $server_report_ip)->get();
			foreach($db_ips as $db_ip) {
				if(!isset($ip_node_id_count[$db_ip->node_id])) {
					$ip_node_id_count[$db_ip->node_id] = 0;
				} else {
					$ip_node_id_count[$db_ip->node_id]++;
				}
				
			}
			
		}
		
		ksort($ip_node_id_count);
		
		$node = Node::find(array_keys($ip_node_id_count)[0]);
		
		if($node) {
			$node->managed = true;
			$node->limbo = false;
			$node->hostname = $data['message']['hostname'];
			$node->virtual = $data['message']['virtual'];
			$node->last_updated = $data['message']['pkginfo']['last_updated'];
			$node->package_manager = $data['message']['pkginfo']['package_manager'];
			$node->platform = $data['message']['pkginfo']['platform'];
			$node->save();
			
			$query_version_vendor_query_pairs = array();
			$packages_index = array();
			
			foreach($packages as $package_version) {
				$package_record = Package::firstOrNew(array('name' => $package_version[0], 'node_id' => $node->id));
				
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
				
				$package_record->version = $package_version[1];
				$package_record->save();
				$packages_index[$package_record->name] = $package_record;
				
				// Set up query cache. Only one query per agent report. That is crucial. Multiple queries would kill everything.
				array_push($query_version_vendor_query_pairs, array('$and' => array(array('product' => $package_record->name, 'version' => $package_record->version))));
			}
			
			// Do one giant query. Not one per record.
			$mongo_client = new MongoClient('mongodb://php_worker3:shadowwood@linus.mongohq.com:10026/nosprawl_vulnerabilities');
			$mongo_database = $mongo_client->selectDB('nosprawl_vulnerabilities');
			$mongo_collection = new MongoCollection($mongo_database, 'vulnerabilities');
			$mongo_query = $mongo_collection->find(array('$or' => $query_version_vendor_query_pairs))->timeout(9999999);
			
			// This is where we actually do something.
			// for some reason if i do a sort the query is so slow it times out
			//$mongo_query->sort(array('last_updated' => 1));
			
			// This won't work right if there are multiple vulnerabilties that match the prod and version.
			
			// DONT ROUND THE SCORE. MAKE THE FIELD A FLOAT.
			// The use of round() here is really a disgrace.
			foreach($mongo_query as $document) {
				$packages_index[$document['product']]->vulnerability_severity = round($document['risk_score']);
				if(round($document['risk_score']) > 0) {
					if(round($document['risk_score']) > 3) {
						$node->vulnerable = true;
					} else {
						$node->severe_vulnerable = true;
					}
					
					$node->save();
				}
				
				$packages_index[$document['product']]->save();
			}
			
			$job->delete();
			
		} else {
			$output->writeln(print_r("fuck"));
		}
		
	}
	
}
