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
		$alert->owner_user_id = Auth::user()->id;
		$alert->save();
		return Redirect::to('/alerts');
	}

	public function getAlerts() {
		$users = Auth::user()->subusers()->get();
		$alerts = Auth::user()->alerts()->get();
		$managed_nodes = Auth::user()->nodes()->where('managed', '=', true)->get();
		$this->layout->content = View::make('alerts.list')->with('users', $users)->with('managed_nodes', $managed_nodes)->with('alerts', $alerts);
	}

}