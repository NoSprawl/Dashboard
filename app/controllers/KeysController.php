<?php
class KeysController extends \BaseController {

	public function upload()
	{
		$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																								   'secret' => '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',
																								   'region' => 'us-east-1'));
		
		
		if(Input::file('key') != null) {
			exec('openssl rsa -noout -in ' . Input::file('key')->getRealPath(), $cli_output, $cli_exec_result_success);
			$output = new Symfony\Component\Console\Output\ConsoleOutput();
			
			// lmao why is this logic reversed? and it works?
			if(!$cli_exec_result_success) {
				$s3->putObject(array(
				    'Bucket'     => 'keys.nosprawl.software',
				    'Key'        => Input::file('key')->getClientOriginalName(),
				    'SourceFile' => Input::file('key')->getRealPath(),
				));
				
				$key = new Key();
				$key->name = Input::get('key_name');
				$key->integration_id = Input::get('integration_id');
				$key->remote_url = Input::file('key')->getClientOriginalName();
				$key->save();
				
				return Redirect::to('integrations')->withMessage("Key added.");
			} else {
				return Redirect::to('integrations')->withMessage("Key not added. Please upload a valid PEM file.");
			}
			
		} else {
			return Redirect::to('integrations')->withMessage("Key not added.");
		}
		
	}
	
}
