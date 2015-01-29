<?php

class HandleOutOfDatePackage {

    public function fire($job, $data)
    {
			$output = new Symfony\Component\Console\Output\ConsoleOutput();
        // Process the job received from the queue
				$output->writeln("out of date. do something about this. alert people. send letters to congress.");
        $output->writeln(print_r($data));
				$job->delete();
    }

}
