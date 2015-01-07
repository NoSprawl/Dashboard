<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$nodes = Auth::user()->nodes;
		$this->layout->content = View::make('check.list')->with("nodes", $nodes);
	}

}