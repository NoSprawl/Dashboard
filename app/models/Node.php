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

	protected $fillable = ['name', 'description', 'owner_id', 'integration_id'];

	public function integration() {

		return $this->belongsTo('Integration', 'integration_id');

	}

	public function owner() {

		return $this->belongsTo('User', 'owner_id');

	}

}
