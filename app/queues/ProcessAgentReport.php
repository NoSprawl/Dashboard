<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$packages = $data['message']['pkginfo']['installed_package'];
		$matches = DB::table('mac_addresses')->whereIn('address', $data['message']['network'])->get();
		
		if($matches) {
			if(sizeOf($matches) == 1) {
				$node = Node::find(intval($matches[0]->node_id));
				$node->managed = true;
				$node->hostname = $data['message']['hostname'];
				$node->last_updated = $data['message']['pkginfo']['last_updated'];
				$node->package_manager = $data['message']['pkginfo']['package_manager'];
				$node->platform = $data['message']['pkginfo']['platform'];
				$output = new Symfony\Component\Console\Output\ConsoleOutput();
				$output->writeln($data['message']['pkginfo']);
				$node->save();
				foreach($data['message']['pkginfo']['installed'] as $package_version) {
					$package_record = NodePackageRecord::firstOrNew(array('package' => $package_version[0], 'node_id' => $node->id));
					$package_record->version = $package_version[1];
					$package_record->save();
				}
				
			} else {
				$output = new Symfony\Component\Console\Output\ConsoleOutput();
				$output->writeln("facked ahp");
			}
			
		}
		
		
		
		/*foreach($packages as $package) {
			if($package['installed_version'] != $package['latest_version']) {
				$fingerprint = $data['message']['network'];
				// Need to correlate this information to a node here or just disregard it.
				$node_id_value = null;
				
				
				
				// Need to correlate everything here
				$c = DB::table('mac_addresses')->select(DB::raw())
				
				Queue::push('HandleOutOfDatePackage', array('message' => array('package' => $package['name'],
																																			 'installed_version' => $package['installed_version'],                                                                            'latest_version' => $package['latest_version'],         
																																			 'mac_address' => $fingerprint)));
			}
			
		}*/
		
		$job->delete();
	}
	
}
