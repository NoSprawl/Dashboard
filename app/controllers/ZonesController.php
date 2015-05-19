<?php

class ZonesController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getZones() {
		$this->layout->content = View::make('zones.list');

	}

}