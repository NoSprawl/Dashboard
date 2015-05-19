<?php

class TestHome extends TestCase {

	/**
	 * Test the homepage
	 *
	 * @return void
	 */
	public function testHomePageContent()
	{
		
		$response = $this->call('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
		$this->assertContains('Welcome to NoSprawl', $response->getContent());
	
	}

	public function testLoginPageContent()
	{


		$response = $this->call('GET', 'login');

		// We should have a form with email, password, submit button,
		// and a CSRF token file 
		
		$matcher = ['tag' => 'input', 'attributes' => ['name' => 'email', 'type' => 'text']];
		$this->assertTag($matcher, $response->getContent());

	}

}
