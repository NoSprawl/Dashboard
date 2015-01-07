<?php

class ProcessAgentReport {
	public function fire($job, $data) {
		$job->delete();
	}
	
}
