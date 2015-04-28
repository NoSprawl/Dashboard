<?php
class KeysController extends \BaseController {

	public function upload()
	{
		$s3 = \Aws\S3\S3Client::factory(array('key' => 'AKIAIUCV4E2L4HDCDOUA',
																								   'secret' => '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',
																								   'region' => 'us-east-1'));
		
		
		if(Input::file('key') != null) {
			$rules = [
				'key' => 'required',
				'name' => 'required',
			];
			
			$input = Input::all();
			$validator = Validator::make($input, $rules);
			
			exec('openssl rsa -noout -in ' . Input::file('key')->getRealPath(), $cli_output, $cli_return);
			
			if(strpos(implode("", $cli_output), "error") !== false) {
				$s3->putObject(array(
				    'Bucket'     => 'keys.nosprawl.software',
				    'Key'        => Input::file('key')->getClientOriginalName(),
				    'SourceFile' => Input::file('key')->getRealPath(),
				));
				
				$key = new Key();
				$key->name = Input::get('key_name');
				$key->remote_url = "https://keys.nosprawl.software/" . Input::file('key')->getClientOriginalName();
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
