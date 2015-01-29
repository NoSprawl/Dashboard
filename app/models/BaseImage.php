<?php

class BaseImage extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'base_images';

	protected $fillable = ['rollback_index', 'service_provider_id', 'service_provider_label', 'service_provider', 'label'];

	public function integration() {
		return $this->belongsTo('Integration', 'integration_id');
	}

}