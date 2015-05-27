<?php

class GroupsController extends BaseController {
	public function createGroup() {
		$input = Input::all();
		$rules = [
			'name' => 'required'
		];

		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			$group = new NodeGroup();
			$group->name = $input['name'];
			$group->user_id = Auth::user()->id;
			$group->save();
			
			return Response::json(array('success' => 'true'));
		} else {
			return Response::json(array('success' => 'false'));
		}
		
	}
	
	public function getAllUserGroups() {
		$groups = Auth::user()->groups;
		return Response::json($groups->toArray());
	}

}
