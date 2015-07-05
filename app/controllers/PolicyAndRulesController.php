<?php
class PolicyAndRulesController extends \BaseController {
	public function createPolicyAndRules($classification_id) {
		// Make sure the classification belongs to the logged in user
		// todo: refactor NodeGroup model and all calls to it to Classification
		$classification = NodeGroup::find($remediation_id);
		$classification = $classification->problem->node;
		if($classification->owner->id != Auth::user()->id) {
			return Response::json(array('success' => 'false', 'reason' => 'TOS Violation', 'message' => 'This incident has been logged.'));
		} else {
			$input = Input::all();
			$policy = new Policy();
			$policy->classification_id = $classification_id;
			$policy->save();
			
			$rule = new Rule();
			$rule->policy_id = $policy->id;
			$rule->restriction = 
			return Response::json(array('success' => 'true'));
		}
		
	}
	
}
