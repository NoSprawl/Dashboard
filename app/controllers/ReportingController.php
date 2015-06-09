<?php

class ReportingController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function reportingIndex() {
		$this->layout->content = View::make('reporting.index');
	}

}