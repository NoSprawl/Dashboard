<?php
class Rule extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rules';

	protected $fillable = ['policy_id'];

	public function policy() {
		return $this->belongsTo('Policy', 'policy_id');
	}

}
