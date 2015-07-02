<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Node extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'nodes';

	protected $fillable = ['name', 'description', 'owner_id', 'integration_id', 'status', 'service_provider_uuid', 'service_provider_base_image_id', 'managed', 'base_image_id', 'service_provider_cluster_id', 'vulnerable', 'severe_vulnerable', 'node_group_id', 'service_provider_availability_zone', 'friendly_availability_zone'];

	public static function boot() {
		parent::boot();
		
		Node::created(function($node) {
			$user = null;
			if(is_null(Auth::user()->parent_user_id)) {
				$user = Auth::user();
			} else {
				$user = User::find(Auth::user()->parent_user_id);
			}
			
			$snapshot = new NodeSnapshot();
			$snapshot->application_node_id = $node->id;
			$snapshot->application_user_id = $node->owner_id;
			$snapshot->service_provider_type = $node->integration->service_provider;
			$snapshot->risk = $node->packages->sum('vulnerability_severity');
			$snapshot->application_is_managed = $node->managed;
			$snapshot->vulnerability_count_critical = $node->packages()->where('vulnerability_severity', '>', 7)->groupBy('vulnerability_severity')->count();
			$snapshot->vulnerability_count_high = $node->packages()->where('vulnerability_severity', '>', 5)->groupBy('vulnerability_severity')->count();
			$snapshot->vulnerability_count_low = $node->packages()->where('vulnerability_severity', '>', 0)->groupBy('vulnerability_severity')->count();
			$snapshot->application_classification_id = 0;
			$snapshot->service_provider_availability_zone = $node->service_provider_availability_zone;
			$snapshot->account_nodes = $node->owner->nodes()->count();
			$snapshot->save();
			return true;
		});
		
		Node::updated(function($node) {
			$user = null;
			if(is_null(Auth::user()->parent_user_id)) {
				$user = Auth::user();
			} else {
				$user = User::find(Auth::user()->parent_user_id);
			}
			
			$snapshot = new NodeSnapshot();
			$snapshot->application_node_id = $node->id;
			$snapshot->application_user_id = $node->owner_id;
			$snapshot->service_provider_type = $node->integration->service_provider;
			$snapshot->risk = $node->packages->sum('vulnerability_severity');
			$snapshot->application_is_managed = $node->managed;
			$snapshot->vulnerability_count_critical = $node->packages()->where('vulnerability_severity', '>', 7)->groupBy('vulnerability_severity')->count();
			$snapshot->vulnerability_count_high = $node->packages()->where('vulnerability_severity', '>', 5)->groupBy('vulnerability_severity')->count();
			$snapshot->vulnerability_count_low = $node->packages()->where('vulnerability_severity', '>', 0)->groupBy('vulnerability_severity')->count();
			$snapshot->application_classification_id = 0;
			$snapshot->service_provider_availability_zone = $node->service_provider_availability_zone;
			$snapshot->account_nodes = $node->owner->nodes()->count();
			$snapshot->save();			
			return true;
		});
		
	}

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
		// This doesn't work for shit.
		return $this->hasMany('Package')->orderBy('vulnerability_severity', 'desc');
	}
	
	public function node_group() {
		return $this->belongsTo('NodeGroup', 'node_group_id');
	}
	
	public function groups() {
		return $this->hasManyThrough('Node', 'NodeGroupAssociation');
	}
	
	public function problems() {
		return $this->hasMany('Problem');
	}

}
