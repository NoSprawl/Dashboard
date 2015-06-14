<?php
class Problem extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'problems';

	protected $fillable = ['description', 'reason', 'node_id', 'long_message'];

	public function node() {
		return $this->belongsTo('Node', 'node_id');
	}
	
	public function remediations() {
		return $this->hasMany('Remediation');
	}

}
