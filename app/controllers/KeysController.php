<?php
class KeysController extends \BaseController {

	public function upload()
	{
		$s3 = \Aws\S3\S3Client::factory(array('credentials' => array(
                                                'key' => 'AKIAIUCV4E2L4HDCDOUA',
                                                'secret' => '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'
                                              ),
                                              'version' => '2006-03-01',
											  'region' => 'us-east-1'
                                              ));
		
		$key = new Key();
		
		if(Input::file('key') != null) {
			exec('openssl rsa -noout -in ' . Input::file('key')->getRealPath(), $cli_output, $cli_exec_result_error);
			if(!$cli_exec_result_error) {
				
				$s3->putObject(array(
			    'Bucket'     => (App::isLocal() ? 'nos.keys' : 'nos.keys'),
			    'Key'        => Input::file('key')->getClientOriginalName(),
			    'SourceFile' => Input::file('key')->getRealPath(),
				));
				
				$key->remote_url = Input::file('key')->getClientOriginalName();
				
			} else {
				return Redirect::to('integrations')->withMessage("Key not added. Please upload a valid PEM file.");
			}
			
		}
						
		$key->integration_id = Input::get('integration_id');
	
		$key->username = Input::get('username');
		$key->password = Input::get('password');
		
		if($key->save()) {
			return Redirect::to('integrations')->withMessage("Credentials added.");
		} else {
			return Redirect::to('integrations')->withMessage("Key not added.");
		}
		
	}
	
}
