<?php

class Integration extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'integrations';

	protected $fillable = ['name', 'description'];

	public function nodes() {

		return $this->hasMany('node');

	}

}