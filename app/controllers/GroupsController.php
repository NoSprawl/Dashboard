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
	
	public function deleteAssoc($groupAssocId) {
		NodeGroupAssociation::destroy($groupAssocId);
		return Response::json(array('success' => 'true'));
	}
	
	public function associateGroupAndNode($node_id, $group_id) {
		$assoc = new NodeGroupAssociation();
		$assoc->group_id = $group_id;
		$assoc->node_id = $node_id;
		if($assoc->save()) {
			return Response::json(array('success' => 'true', 'new_id' => $assoc->id, 'new_name' => NodeGroup::find($group_id)->name));
		} else {
			return Response::json(array('success' => 'false'));
		}
		
	}
	
	public function getAllUserGroups() {
		$groups = Auth::user()->groups;
		return Response::json($groups->toArray());
	}

}
