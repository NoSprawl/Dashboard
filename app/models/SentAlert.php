<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class SentAlert extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sent_alerts';
	protected $fillable = ['recipient_user_id', 'package', 'version', 'node_id'];
}
