@section('check_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Cloud Status</h1>
	<?php if(!empty($page_data['unmanaged_nodes']) || !empty($page_data['managed_nodes'])) { ?>
	<ul class="uk-tab" data-uk-tab>
	    <li class="<?php if(!sizeof($page_data['managed_nodes']) > 0) {echo 'uk-disabled';} ?>"><a rel='managed_nodes' href="#">
			<?php if(sizeof($page_data['managed_nodes']) > 0) { ?>
			<div class="uk-badge uk-badge-success">
			<?= sizeof($page_data['managed_nodes']); ?>
			
			</div>
			<?php } ?>
			Managed Nodes</a></li>
	    <li class="<?php if(!sizeof($page_data['unmanaged_nodes']) > 0) {echo 'uk-disabled';} else {echo 'uk-active';} ?>"><a href="#" rel="unmanaged_nodes">
			<?php if(sizeof($page_data['unmanaged_nodes']) > 0) { ?>
			<div data-uk-tooltip title="These nodes are not currently being monitored." class="uk-badge uk-badge-warning">
			<?= sizeof($page_data['unmanaged_nodes']); ?>
			
			</div>
			<?php } ?>
			Unmanaged Nodes</a></li>
	</ul>
	<table id="unmanaged_nodes" class="uk-table">
		<thead>
	  	<tr>
				<!--<th></th>-->
				<th>Patch Management</th>
				<th>Service Provider</th>
				<th>Base Image</th>
				<th>Cluster</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php $custom_css_for_clusters = array(); ?>
		<?php foreach($page_data['unmanaged_nodes'] as $node) { ?>
			<?php $integration = Integration::find($node->integration_id); ?>
			<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
			<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
			<?php
			if(!in_array($service_provider_cluster_id, $custom_css_for_clusters)) {
				$color = array_shift($page_data['cluster_colors']);
				if($service_provider_cluster_id != 'None') {
			?>
			<style type='text/css'>
			tr.<?= $service_provider_cluster_id; ?> td { border: none !important; }
			tr.<?= $service_provider_cluster_id; ?> td { background: #<?= $color; ?>; }
			</style>
			<?php
				}
				array_push($custom_css_for_clusters, $color);
			}
			?>
			<tr class="<?= $service_provider_cluster_id; ?>">
				<!--<td><span class="handle"></span></td>-->
				<td>
					<div class="switch switch-yellow">
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="enable" id="toggle-enable-<?= $node->id; ?>">
					  <label for="toggle-enable-<?= $node->id; ?>" class="switch-label switch-label-off">Enabled</label>
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="disable" id="toggle-disable-<?= $node->id; ?>" checked>
					  <label for="toggle-disable-<?= $node->id; ?>" class="switch-label switch-label-on">Disabled</label>
					  <span class="switch-selection"></span>
					</div>
				</td>
				<td class="shift"><a id='integration-tooltip-<?= $node->id; ?>' href="#"><?= $integration->service_provider; ?></a></td>
				<td class="shift"><?= $node->service_provider_base_image_id; ?></td>
				<td class="shift"><?= $service_provider_cluster_id; ?></td>
				<td class="shift"><?= $service_provider_description; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<table style="display: none;" id="managed_nodes" class="uk-table">
		<thead>
	  	<tr>
				<!--<th></th>-->
				<th width="140">Patch Management</th>
				<th width="180">Service Provider</th>
				<th>Host Name</th>
				<th>Last Updated</th>
				
				<th>Base Image</th>
				
				<th>More</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($page_data['managed_nodes'] as $node) { ?>
			<?php $integration = Integration::find($node->integration_id); ?>
			<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
			<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
			<?php
			if(!in_array($service_provider_cluster_id, $custom_css_for_clusters)) {
				$color = array_shift($page_data['cluster_colors']);
				if($service_provider_cluster_id != 'None') {
			?>
			<style type='text/css'>
			tr.<?= $service_provider_cluster_id; ?> td { border: none !important; }
			tr.<?= $service_provider_cluster_id; ?> td { background: #<?= $color; ?>; }
			</style>
			<?php
				}
				array_push($custom_css_for_clusters, $color);
			}
			?>
			<tr class="<?= $service_provider_cluster_id; ?>">
				<!--<td><span class="handle"></span></td>-->
				<td>
					<div class="switch">
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="enable" id="toggle-enable-<?= $node->id; ?>" checked>
					  <label for="toggle-enable-<?= $node->id; ?>" class="switch-label switch-label-off">Enabled</label>
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="disable" id="toggle-disable-<?= $node->id; ?>">
					  <label for="toggle-disable-<?= $node->id; ?>" class="switch-label switch-label-on">Disabled</label>
					  <span class="switch-selection"></span>
					</div>
				</td>
				<td class="shift"><a id='integration-tooltip-<?= $node->id; ?>' href="#"><?php
					switch($integration->service_provider) {
						case "AmazonWebServicesIntegration":
							print "<img style='top: -1px; position: relative;' src='/svg/aws.svg' width='40px'>";
						break;
					}
					
					print "<span class='slash'>/</span>";
					print "<span class='package_man'>" . $node->platform . "</span>";
					print "<span class='slash'>/</span>";
					print "<span class='package_man'>" . $node->package_manager . "</span>";
				?></a></td>
				<td class="shift"><?= $node->hostname; ?></td>
				<td class="shift"><?php if($node->last_updated != "") {print $node->last_updated;} else {print "Never";} ?></td>
				<td class="shift"><?= $node->service_provider_base_image_id; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } else { ?>
		<?php if($page_data['cloud_provider_integration_count'] == 0) { ?>
			<div class="advice">
			<p>We can&rsquo;t see any of your nodes yet.</p>
			<p>To start monitoring your nodes, either create a <a data-uk-modal="{target:'#new-integration-form'}" href="#">cloud provider connection</a> or <a href="#">deploy our agent</a> to one of your nodes.</p>
			</div>
		<?php } else { ?>
			<div class="advice">
			<p>We haven&rsquo;t imported all of your nodes yet. It&rsquo;ll just be a few more minutes.</p>
			</div>
		<?php } ?>
	<?php } ?>
</article>
<script type="text/javascript" src="/js/nos.toggle.js"></script>
<script type="text/javascript" src="/js/nos.tabs.js"></script>
@stop