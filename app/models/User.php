<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes not excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'user_id'];
	protected $fillable = ['password', 'name', 'avatar', 'email', 'username', 'name', 'phone_number', 'limbo', 'company_name', 'full_name'];

	public function integrations() {
		return $this->hasMany('Integration');
	}

	public function nodes() {
		return $this->hasMany('Node', 'owner_id');
	}
	
	public function subusers() {
		return $this->hasMany('User', 'parent_user_id');
	}
	
	public function alerts() {
		return $this->hasMany('Alert', 'owner_user_id');
	}
	
	public function parent() {
		return $this->hasOne('User', 'parent_user_id');
	}

}
