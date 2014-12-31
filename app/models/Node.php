<?php

class Node extends Eloquent {

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
