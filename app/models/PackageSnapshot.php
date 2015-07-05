<?php
class PackageSnapshot extends Eloquent {
	protected $connection = 'analytics';
	
	protected $table = 'packages';

	protected $fillable = ['application_node_id', 'application_package_name', 'application_package_version', 'application_package_vulnerability_severity'];
	
	public function user() {
		return $this->belongsTo('User', 'id');
	}
	
}