<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	// Added this test only to suppress the warning when 
	// running PHPUnit. I'm sure there's a better way

	public function testOk() 
	{
		$this->assertTrue(true);
	}

}
