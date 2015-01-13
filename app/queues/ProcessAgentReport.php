<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		
		$packages = $data['message']['packages'];
		
		foreach($packages as $package) {
			if($package['installed_version'] != $package['latest_version']) {
				$output->writeln(print_r($package));
				$fingerprint = "123";
				
				Queue::push('HandleOutOfDatePackage', array('message' => array('package' => $package['name'], 'installed_version' => $package['installed_version'], 'latest_version' => $package['latest_version'], 'mac_address' => $fingerprint)));
			}
			
		}
		
		$job->delete();
	}
	
}
