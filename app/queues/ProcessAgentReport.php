<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$output = new Symfony\Component\Console\Output\ConsoleOutput();
		$output->writeln(print_r($data));
		
		$job->delete();
	}
	
}
