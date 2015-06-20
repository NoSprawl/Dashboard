<?php
class NodeSnapshot extends Eloquent {
	protected $connection = 'analytics';
	
	protected $table = 'nodes';

	protected $fillable = ['application_user_id', 'application_node_id', 'service_provider_type', 'risk', 'vulnerability_count_critical', 'vulnerability_count_high', 'vulnerability_count_low', 'application_is_managed', 'application_classification_id'];
	
}