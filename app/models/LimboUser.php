<?php

class LimboUser extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'limbo_users';

	/**
	 * The attributes not excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'parent_user_id', 'user_confirmation_token'];

}
