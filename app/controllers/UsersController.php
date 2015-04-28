<?php

class UsersController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function listAccountUsers() {
		$this->layout->content = View::make('users.list');

	}

}