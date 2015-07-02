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

	public static function boot() {
		parent::boot();
		
		User::deleting(function($user) {
			if(App::environment('local')) {
				Stripe::setApiKey(Config::get('stripe.development.secret'));
			} else {
				Stripe::setApiKey(Config::get('stripe.production.secret'));
			}
			
			$cu = \Stripe\Customer::retrieve($user->stripe_customer_id);
			$cu->delete();		
			return true;
		});
		
	}

	public function integrations() {
		return $this->hasMany('Integration');
	}

	public function nodes() {
		return $this->hasMany('Node', 'owner_id');
	}
	
	public function node_snapshots() {
		return $this->hasMany('NodeSnapshot', 'application_user_id');
	}
	
	public function subusers() {
		return $this->hasMany('User', 'parent_user_id');
	}
	
	public function alerts() {
		return $this->hasMany('Alert', 'owner_user_id');
	}
	
	public function node_groups() {
		return $this->hasMany('NodeGroup', 'user_id');
	}
	
	public function has_parent() {
		return !is_null($this->parent_user_id);
	}
	
	public function owns_subuser($subuser_id) {
		return $this->subusers()->where("id", "=", $subuser_id)->count() > 0;
	}
	
	public function parent() {
		return $this->hasOne('User', 'parent_user_id');
	}

}
