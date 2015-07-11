<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Package extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';

	protected $fillable = ['name', 'version', 'node_id', 'created_at'];
	
	public static function boot() {
		parent::boot();
		
		/*Package::created(function($package) {			
			$snapshot = new PackageSnapshot();
			$snapshot->application_package_id = $package->id;
			$snapshot->application_package_name = $package->name;
			$snapshot->application_package_version = $package->version;
			$snapshot->application_package_vulnerability_severity = $package->vulnerability_severity;
			$snapshot->save();
			return true;
		});
		
		Package::updated(function($package) {
			$snapshot = new PackageSnapshot();
			$snapshot->application_package_id = $package->id;
			$snapshot->application_package_name = $package->name;
			$snapshot->application_package_version = $package->version;
			$snapshot->application_package_vulnerability_severity = $package->vulnerability_severity;
			$snapshot->save();			
			return true;
		});*/
		
	}
	
	public function node() {
		return $this->belongsTo('Node', 'node_id');
	}

}
