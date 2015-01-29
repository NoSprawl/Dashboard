@section('check_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Status</h1>
	<ul class="uk-tab" data-uk-tab>
	    <li class="<?php if(!sizeof($managed_nodes) > 0) {echo 'uk-disabled';} ?>"><a href="#">Managed Nodes</a></li>
	    <li class="<?php if(!sizeof($unmanaged_nodes) > 0) {echo 'uk-disabled';} else {echo 'uk-active';} ?>"><a href="#">Unmanaged Nodes <div data-uk-tooltip title="These nodes are not currently being monitored." class="uk-badge uk-badge-warning"><?= sizeof($unmanaged_nodes); ?></div></a></li>
	</ul>
	<table class="uk-table">
		<thead>
	  	<tr>
	    	<th>Node Status</th>
				<th>Service Provider</th>
				<th>Base Image</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($unmanaged_nodes as $node) { ?>
				<tr>
					<?php $integration = $node->integration()->get(); ?>
					<td><?php echo $node['service_provider_status']; ?></td>
					<td>
					<?php
					if(sizeof($integration) > 0) {
						echo $integration[0]['service_provider'];
					}
					
					?>
					</td>
					<td><a class="infotool" href="#" data-uk-tooltip title="Info about the base image here"><?php echo $node['service_provider_base_image_id']; ?></a></td>
					<td><?php echo $node['description']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</article>
@stop