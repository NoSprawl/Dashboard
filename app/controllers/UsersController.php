<?php

class UsersController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function listAccountUsers() {
		$subusers = Auth::user()->subusers()->get();
		$this->layout->content = View::make('users.list')->with('subusers', $subusers);
	}

}