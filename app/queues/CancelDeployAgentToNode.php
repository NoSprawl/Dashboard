<?php
class CancelDeployAgentToNode {
	public function fire($job, $data) {
		$node = Node::find($data['message']['node_id']);
		$node->limbo = false;
		$node->save();
		return $job->delete();
	}
		
}