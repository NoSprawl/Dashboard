<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MacAddress extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mac_addresses';

	protected $fillable = ['node_id', 'address'];

	public $timestamps = false;

	public function integration() {
		return $this->belongsTo('Integration', 'integration_id');
	}

}
