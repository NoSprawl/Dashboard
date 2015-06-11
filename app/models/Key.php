<?php

class Key extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'key_references';

	protected $fillable = ['name', 'remote_url', 'username', 'password'];

}