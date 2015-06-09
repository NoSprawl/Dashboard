<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Node extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'nodes';

	protected $fillable = ['name', 'description', 'owner_id', 'integration_id', 'status', 'service_provider_uuid', 'service_provider_base_image_id', 'managed', 'base_image_id', 'service_provider_cluster_id', 'vulnerable', 'severe_vulnerable', 'node_group_id'];

	public function integration() {
		return $this->belongsTo('Integration', 'integration_id');
	}

	public function mac_addresses() {
		return $this->hasMany('MacAddress');
	}

	public function owner() {
		return $this->belongsTo('User', 'owner_id');
	}
	
	public function packages() {
		return $this->hasMany('Package')->orderBy('vulnerability_severity', 'desc')->orderBy('created_at', 'desc')->distinct('name');
	}
	
	public function node_group() {
		return $this->belongsTo('NodeGroup', 'node_group_id');
	}
	
	public function groups() {
		return $this->hasManyThrough('Node', 'NodeGroupAssociation');
	}

}
