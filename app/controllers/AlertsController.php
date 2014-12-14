<?php

class AlertsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getAlerts() {
		$this->layout->content = View::make('alerts.list');

	}

}