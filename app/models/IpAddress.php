<?php
class IpAddress extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ip_addresses';

	protected $fillable = ['node_id', 'address', 'private'];

	public $timestamps = true;

	public function integration() {
		return $this->belongsTo('Node', 'node_id');
	}

}
