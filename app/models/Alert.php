<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Alert extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'alerts';

	protected $fillable = ['user_id', 'condition', 'value'];

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
		return $this->hasMany('Package');
	}

}
