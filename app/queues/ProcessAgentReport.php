<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$packages = $data['message']['pkginfo']['installed'];
		$matches = DB::table('mac_addresses')->whereIn('address', $data['message']['network'])->get();
				
		$matched_node = null;
		
		if($matches) {
			// Right now just looks for a single matching Mac address. This is troublesome because VMs will prob match VMs
			// across clients and even within clients. Need private IP list + Mac list to really make this work.
			if(sizeOf($matches) == 1) {
				$node = Node::find(intval($matches[0]->node_id));
				$node->managed = true;
				$node->limbo = false;
				$node->hostname = $data['message']['hostname'];
				$node->last_updated = $data['message']['pkginfo']['last_updated'];
				$node->package_manager = $data['message']['pkginfo']['package_manager'];
				$node->platform = $data['message']['pkginfo']['platform'];
				$matched_node = $node;
				
				$query_version_vendor_query_pairs = array();
				$packages_index = array();
				
				foreach($packages as $package_version) {
					$package_record = Package::firstOrNew(array('name' => $package_version[0], 'node_id' => $node->id));
					$package_record->version = $package_version[1];
					$package_record->save();
					$packages_index[$package_record->name] = $package_record;
					
					// Set up query cache. Only one query per agent report. That is crucial.
					array_push($query_version_vendor_query_pairs, array('$and' => array(array('product' => $package_record->name, 'version' => $package_record->version))));
				}
				
				// REMOVE THIS!!!!!
				// REMOVE THIS. THIS IS TO FORCE AT LEAST ONE RESULT
				array_push($query_version_vendor_query_pairs, array('$and' => array(array('product' => 'freebsd', 'version' => '2.2.4'))));
				// REMOVE THE ABOVE. IT IS TO FORCE THE RESULT FOR AN ALERT
				// REMOVE THIS!!!!!
				
				// Do one giant query. Not one per record.
				$mongo_client = new MongoClient('mongodb://php_worker3:shadowwood@linus.mongohq.com:10026/nosprawl_vulnerabilities');
				$mongo_database = $mongo_client->selectDB('nosprawl_vulnerabilities');
				$mongo_collection = new MongoCollection($mongo_database, 'vulnerabilities');
				$mongo_query = $mongo_collection->find(array('$or' => $query_version_vendor_query_pairs));
				
				$vulnerabilities_found = false;
				$serious_vulnerabilities_found = false;
				
				// This is where we actually do something.
				
				/*$mongo_query->sort(array('last_updated' => 1));
				$last_document = null;
				
				
				foreach($mongo_query as $document) {
					$vulnerabilities_found = true;
					if($document['risk_score'] > 2) {
						$serious_vulnerabilities_found = true;
					}
					
					$last_document = $oducment;
				}
				
				$last_updated_packages[$last_document['product']]->vulnerability_severity = $last_document['product'];
				$last_updated_packages[$last_document['product']]->save();
				
				$old_vulnerable = $node->vulnerable;
				$old_severe_vulnerable = $node->severe_vulnerable;
				
				$node->vulnerable = $vulnerabilities_found;
				$node->severe_vulnerable = $serious_vulnerabilities_found;
				
				// Has this node's vulnerability state changed?
				if($node->vulnerable != $old_vulnerable || $node->severe_vulnerable != $old_severe_vulnerable) {
					if(!$node->vulnerable && !$node->severe_vulnerable) {
						Queue::push('NodeIsHealthy', array('message' => $node->toJson()));
					} else {
						Queue::push('NodeIsVulnerable', array('message' => $node->toJson()));
					}
				}*/
				
				$node->save();
				
			} else {
				$output = new Symfony\Component\Console\Output\ConsoleOutput();
				$output->writeln("facked ahp");
			}
			
		}
		
		$job->delete();
	}
	
}
