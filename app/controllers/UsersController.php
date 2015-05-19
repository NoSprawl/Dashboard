<?php

class UsersController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}

	public function listAccountUsers() {
		$subusers = User::where('parent_user_id', Auth::user()->id)->get();
		$limbo_subusers = LimboUser::where('parent_user_id', Auth::user()->id)->get();
		$this->layout->content = View::make('users.list')->with('active_subusers', $subusers)->with('limbo', $limbo_subusers);
	}

}