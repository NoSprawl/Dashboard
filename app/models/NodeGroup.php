<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class NodeGroup extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'node_groups';

	protected $fillable = ['name', 'user_id'];

	public function owner() {
		return $this->belongsTo('User', 'user_id');
	}
	
	public function nodes() {
		return $this->hasMany('Node');
	}

}
