<?php
class Policy extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'policies';

	protected $fillable = ['classification_id'];

	public function classification() {
		return $this->belongsTo('Classification', 'classification_id');
	}

}
