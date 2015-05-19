<?php

class AlertsController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function __construct() {

	}
	
	public function addAlert() {
		$input = Input::all();
		$alert = new Alert;
		$alert->value = $input['value'];
		$alert->condition = $input['condition'];
		$alert->user_id = $input['user'];
		if(Auth::user()->parent_user_id == null) {
			$alert->owner_user_id = Auth::user()->id;
		} else {
			$alert->owner_user_id = User::find(Auth::user()->parent_user_id)->id;
		}
		
		$alert->save();
		return Redirect::to('/alerts');
	}

	public function getAlerts() {
		if(Auth::user()->parent_user_id == null) {
			$users = Auth::user()->subusers()->get();
			$alerts = Auth::user()->alerts()->get();
		} else {
			$users = User::where("parent_user_id", Auth::user()->parent_user_id);
			$alerts = User::find(Auth::user()->parent_user_id)->alerts()->get();
		}
		
		$managed_nodes = Auth::user()->nodes()->where('managed', '=', true)->get();
		$this->layout->content = View::make('alerts.list')->with('users', $users)->with('managed_nodes', $managed_nodes)->with('alerts', $alerts);
	}

}