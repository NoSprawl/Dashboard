<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class NodeGroupAssociation extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'node_group_associations';

	protected $fillable = ['node_id', 'group_id'];

	public function owner() {
		return $this->belongsTo('User', 'user_id');
	}
	
	public function nodes() {
		return $this->hasMany('Node');
	}

}
