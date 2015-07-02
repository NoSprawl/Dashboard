<?php
class CloudIntegration {
	function friendly_availability_zone_for($sp_avail_zone) {
		$flipped = array_flip($this->$availability_zones);
		return $flipped[$sp_avail_zone];
	}
	
}
