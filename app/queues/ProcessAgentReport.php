<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$packages = $data['message']['pkginfo']['installed'];
		$matches = DB::table('mac_addresses')->whereIn('address', $data['message']['network'])->get();
		
		$matched_node = null;
		
		if($matches) {
			// Right now just looks for a single matching Mac address. This is troublesome because VMs will prob match VMs
			// across clients and even within clients.
			if(sizeOf($matches) == 1) {
				$node = Node::find(intval($matches[0]->node_id));
				$node->managed = true;
				$node->limbo = false;
				$node->hostname = $data['message']['hostname'];
				$node->last_updated = $data['message']['pkginfo']['last_updated'];
				$node->package_manager = $data['message']['pkginfo']['package_manager'];
				$node->platform = $data['message']['pkginfo']['platform'];
				$node->save();
				$matched_node = $node;
				
				foreach($packages as $package_version) {
					$package_record = Package::firstOrNew(array('name' => $package_version[0], 'node_id' => $node->id));
					$package_record->version = $package_version[1];
					$package_record->save();
				}
				
			} else {
				$output = new Symfony\Component\Console\Output\ConsoleOutput();
				$output->writeln("facked ahp");
			}
			
		}
		
		$job->delete();
	}
	
}
