<?php

class IntegrationsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getIntegrations() {
		$this->layout->content = View::make('integrations.list');

	}

}