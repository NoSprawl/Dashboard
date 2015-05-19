<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Package extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';

	protected $fillable = ['name', 'version', 'node_id'];

	public function node() {
		return $this->belongsTo('Node', 'node_id');
	}

}
