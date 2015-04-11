<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		#$packages = $data['message']['packages'];
		$matches = DB::table('mac_addresses')->whereIn('address', $data['message']['network'])->get();
		
		if($matches) {
			if(sizeOf($matches) == 1) {
				$output = new Symfony\Component\Console\Output\ConsoleOutput();
				$node = Node::find(intval($matches[0]->node_id));
				$node->managed = true;
				$node->save();
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
