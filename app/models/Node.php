<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Node extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'nodes';

	protected $fillable = ['name', 'description', 'owner_id', 'integration_id', 'status', 'service_provider_uuid', 'service_provider_base_image_id', 'managed'];

	public function integration() {
		return $this->belongsTo('Integration', 'integration_id');
	}

	public function mac_addresses() {
		return $this->hasMany('MacAddress');
	}

	public function owner() {
		return $this->belongsTo('User', 'owner_id');
	}

}
