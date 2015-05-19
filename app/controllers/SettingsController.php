<?php

class SettingsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getSettings() {
		$this->layout->content = View::make('settings.list');

	}

}