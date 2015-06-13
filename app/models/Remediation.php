<?php
class Remediation extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'remediations';

	protected $fillable = ['name', 'queue_name', 'problem_id'];

	public function problem() {
		return $this->belongsTo('Problem', 'problem_id');
	}

}
