<?php

class CheckController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function getCheck() {
		$unmanaged_nodes = Auth::user()->nodes;
		$managed_nodes = Auth::user()->unmanaged_nodes;
		$this->layout->content = View::make('check.list')->with("unmanaged_nodes", $unmanaged_nodes)->with("managed_nodes", $managed_nodes);
	}

}