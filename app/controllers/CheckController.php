<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$nodes = Auth::user()->nodes;
		$managed_nodes = Auth::user()->nodes()->where("managed", "=", true);
		$this->layout->content = View::make('check.list')->with("nodes", $nodes)->with("managed_nodes", $managed_nodes);
	}

}