<?php

class Integration extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'integrations';

	protected $fillable = ['name', 'description', 'user_id', 'service_provider'];

	public function nodes() {

		return $this->hasMany('node');

	}
	
	public function owner() {

		return $this->belongsTo('User', 'user_id');

	}

}