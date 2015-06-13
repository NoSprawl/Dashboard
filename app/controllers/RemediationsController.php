<?php
class RemediationsController extends \BaseController {
	public function remediate($remediation_id) {
		// Make sure the remediation belongs to the logged in user
		$remediation = Remediation::find($remediation_id);
		$node = $remediation->problem->node;
		if($node->owner_id != Auth::user()->id) {
			return Response::json(array('success' => 'false', 'reason' => 'TOS Violation', 'message' => 'This incident has been logged.'));
		} else {
			Queue::push($remediation->queue_name, array('message' => array('node_id' => $node->id, 'integration_id' => $node->integration_id)));
			$remediation->problem->remediations()->delete();
			$remediation->problem->delete();
			return Response::json(array('success' => 'true'));
		}
		
	}
	
}
