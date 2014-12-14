<?php

class DashboardController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getDashboard() {
		$this->layout->content = View::make('dashboard.main');

	}

}