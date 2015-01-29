<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$packages = $data['message']['packages'];
		foreach($packages as $package) {
			if($package['installed_version'] != $package['latest_version']) {
				$fingerprint = $data['message']['network'];
				// Need to correlate this information to a node here or just disregard it.
				$node_id_value = null;
				
				foreach($fingerprint as $mac_address) {
					// See if we find this mac in the database. We also need to find out if
					// a) all mac accresses in the $fingerprint are present
					// b) while the above is true, all present mac addresses must reference the
					//    same node_id. This confirms we are definitely dealing with a known node.
					// Problem is b is really hard to implement. Mac addresses might not be an adequate identifier anyway
					if(MacAddress::where('address', '=', $mac_address)->count() > 0) {
						$mac_address = MacAddress::where('address', '=', $mac_address)->select('node_id')->getFirst();
						
						$output = new Symfony\Component\Console\Output\ConsoleOutput();
						$output->writeln(print_r($mac_address));
						
					}
					
				}
				
				// Need to correlate everything here
				/*$c = DB::table('mac_addresses')->select(DB::raw())
				
				Queue::push('HandleOutOfDatePackage', array('message' => array('package' => $package['name'],
																																			 'installed_version' => $package['installed_version'],                                                                            'latest_version' => $package['latest_version'],         
																																			 'mac_address' => $fingerprint)));*/
			}
			
		}
		
		$job->delete();
	}
	
}
