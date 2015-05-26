@section('check_link') uk-active @stop

@section('content')

<style type="text/css">
td {
	height: 26px !important;
}
.trim {
	width: 150px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.trim_long {
	width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
<style type="text/css">
.spinner {
  margin: 0px 0 0 0;
	left: 0;
  width: 70px;
  text-align: center;
}

.spinner > div {
  width: 9px;
  height: 9px;
  background-color: #39f;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: bouncedelay 1.4s infinite ease-in-out;
  animation: bouncedelay 1.4s infinite ease-in-out;
  /* Prevent first frame from flickering when animation starts */
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

.spinner .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0.0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes bouncedelay {
  0%, 80%, 100% { 
    transform: scale(0.0);
    -webkit-transform: scale(0.0);
  } 40% { 
    transform: scale(1.0);
    -webkit-transform: scale(1.0);
  }
}
div.limbo {
	font-size: .8em;
	position: absolute;
	left: 70px;
	top: 11px;
	color: #39f;
}
.spinner_holder .spinner {
	position: relative;
	top: 3px;
}
.spinner_holder:hover {
	cursor: pointer;
}
.spinner_holder:hover div.limbo {
	text-decoration: underline;
}
</style>
<article class="uk-article">
	<h1 class="uk-article-title">Environment Status</h1>
	<?php if(!empty($page_data['unmanaged_nodes']) || !empty($page_data['managed_nodes'])) { ?>
	<?php if(!sizeof($page_data['unmanaged_nodes']) && !sizeof($page_data['managed_nodes'])) { ?>
		<div class="advice">
		<p>We can&rsquo;t see any of your nodes yet.</p>
		<p>To start monitoring your nodes, either create a <a data-uk-modal="{target:'#new-integration-form'}" href="#">cloud provider connection</a> or <a href="#">deploy our agent</a> to one of your nodes.</p>
		</div>
	<?php } else { ?>
	<ul class="uk-tab" data-uk-tab>
    <li class="<?php if(sizeof($page_data['managed_nodes']) != 0) {echo 'uk-active';} else {echo 'uk-disabled';} ?>"<?php if(sizeof($page_data['unmanaged_nodes']) == 0 || sizeof($page_data['managed_nodes']) != 0) {echo ' aria-expanded=\'true\'';} ?>><a <?php if(sizeof($page_data['unmanaged_nodes']) == 0 || sizeof($page_data['managed_nodes']) != 0) {echo "class='uk-active' aria-expanded='true'";} else {echo "class='uk-disabled' aria-expanded='false'";} ?> rel='managed_nodes' href="#">
		<?php if(sizeof($page_data['managed_nodes']) > 0) { ?>
		<div class="uk-badge uk-badge-success">
		<?= sizeof($page_data['managed_nodes']); ?>
		</div>
		<?php } ?>
		Managed Assets</a></li>
    <li class="<?php if(!sizeof($page_data['unmanaged_nodes']) > 0) {echo 'uk-disabled';} if(sizeof($page_data['managed_nodes']) == 0) {echo 'uk-active';} ?>"><a class="<?php if(sizeof($page_data['unmanaged_nodes']) == 0) {echo 'uk-disabled';} else {echo 'uk-active';} ?>" href="#" rel="unmanaged_nodes">
		<?php if(sizeof($page_data['unmanaged_nodes']) > 0) { ?>
		<div data-uk-tooltip title="These nodes are not currently being monitored." class="uk-badge uk-badge-warning">
		<?= sizeof($page_data['unmanaged_nodes']); ?>
		</div>
		<?php } ?>
		Unmanaged Assets</a></li>
	</ul>
	
	<div id="unmanaged_nodes" class="nos-hidable" style="<?php if(sizeof($page_data['managed_nodes']) != 0) {echo 'display: none;';} ?>">
		<div class="uk-grid uk-grid-collapse nos-title-row">
	    <div class="uk-width-1-6">Patch Management</div>
	    <div class="uk-width-1-6">Node Status</div>
			<div class="uk-width-1-6">Cloud Integration</div>
			<div class="uk-width-1-6">Cluster</div>
			<div class="uk-width-2-6">Network Info</div>
		</div>
		
		<?php foreach($page_data['unmanaged_nodes'] as $node) { ?>
			<?php $integration = Integration::find($node->integration_id); ?>
			<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
			<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
			
			<div class="uk-grid uk-grid-collapse nos-row">
				<div class="uk-width-1-6 spinner_holder">
				<?php if($node->service_provider_status != "terminated") { ?>
					<?php if(!$node->limbo) { ?>
					<div class="switch switch-yellow">
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="enable" id="toggle-enable-<?= $node->id; ?>">
					  <label data-integration="<?= $integration->id ?>" for="toggle-enable-<?= $node->id; ?>" class="switch-label switch-label-off">Enabled</label>
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="disable" id="toggle-disable-<?= $node->id; ?>" checked>
					  <label for="toggle-disable-<?= $node->id; ?>" class="switch-label switch-label-on">Disabled</label>
					  <span class="switch-selection"></span>
					</div>
					<?php } else { ?>
					<div class="spinner">
					  <div class="bounce1"></div>
					  <div class="bounce2"></div>
					  <div class="bounce3"></div>
					</div>
					<div class="limbo">Activating</div>
					<?php } ?>
				<?php } ?>
				</div>
				<div class="uk-width-1-6" style="position: relative;">
					<?php
					switch($node->service_provider_status) {
						case "stopped":
							print "<span class='stopped'></span><span class='statuslabel'>Stopped</span>";
						break;
					
						case "running":
							print "<span class='running'></span><span class='statuslabel'>Running</span>";
						break;
					
						case "terminated":
							print "<span class='terminated'></span><span class='statuslabel'>Terminated</span>";
						break;
					}
					?>
				</div>
				<div class="uk-width-1-6">
					<?php
					switch($integration['service_provider']) {
						case "AmazonWebServicesIntegration":
							print "<img style='top: 0px; position: relative;' src='/svg/aws.svg' width='55px'>";
						break;
						case "RackspaceCloudIntegration":
							print "<img style='top: 3px; position: relative;' src='/svg/rackspace.svg' width='66px'>";
						break;
					}
					?>
				</div>
				<div class="uk-width-1-6"><?= $service_provider_cluster_id; ?></div>
				<div class="uk-width-2-6"><div class="trim_long"><?= $service_provider_description; ?></div></div>
			</div>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div id="managed_nodes" class="nos-hidable" style="<?php if((sizeof($page_data['managed_nodes']) == 0)) {echo 'display: none;';} ?>">
		<div class="uk-grid uk-grid-collapse nos-title-row">
	    <div class="uk-width-1-6">Patch Risk</div>
	    <div class="uk-width-1-6">Cloud &amp; Platform</div>
			<div class="uk-width-1-6">Last Patch</div>
			<div class="uk-width-1-6">Base Image</div>
			<div class="uk-width-1-6">Host Name</div>
			<div class="uk-width-1-6">Classification Tags</div>
		</div>
	
	<?php foreach($page_data['managed_nodes'] as $node) { ?>
		<?php $integration = Integration::find($node->integration_id); ?>
		<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
		<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
		<div class="uk-grid uk-grid-collapse nos-row nos-activatable" rel="<?= $node->id; ?>" class="<?= $service_provider_cluster_id; ?>">
			<div class="uk-width-1-6 node_status">
			<?php
			if($node->vulnerable) {
				if(!$node->severe_vulnerable) {
					print "<span class='stopped'></span><span class='statuslabel'>Low Risk</span>";
				} else {
					print "<span class='stopped'></span><span class='statuslabel'>High Risk</span>";
				}
			
			} else {
				print "<span class='running'></span><span class='statuslabel'>Healthy</span>";
			}
		
			?>
			</div>
			<div class="uk-width-1-6 node_cloud_provider">
				<a class="nos-integration-info trim_long" id='integration-tooltip-<?= $node->id; ?>' href="#"><?php
					switch($integration->service_provider) {
						case "AmazonWebServicesIntegration":
							print "<img class='i_sp_logo' style='top: -1px; position: relative;' src='/svg/aws.svg' width='40'>";
						break;
						case "RackspaceCloudIntegration":
							print "<img class='i_sp_logo' style='top: 1px; position: relative;' src='/svg/rackspace.svg' width='50'>";
						break;
					}
		
					print "<span class='slash'>/</span>";
					print "<span class='package_man t_platform'>" . $node->platform . "</span>";
					print "<span class='slash'>/</span>";
					print "<span class='package_man t_type'>" . ($node->virtual ? "Virtual" : "Metal") . "</span>";
				?></a>
			</div>
			<div class="uk-width-1-6 node_last_updated">
			<div class="trim_long shift5"><?php if(is_int($node->last_updated)) {$dt = new DateTime($node->last_updated); echo $dt->format("m-d-Y H:i:s");} else {print "Never";} ?></div>
			</div>
			<div class="uk-width-1-6 node_base_image_id"><div class="trim shift5"><?= $node->service_provider_base_image_id; ?></div></div>
			<div class="uk-width-1-6 node_hostname"><div class="trim_long shift5"><?= $node->hostname; ?></div></div>
			<div class="uk-width-1-6 node_packages">
				<div class="shift5">
					<div class="uk-badge uk-badge-success nos-deletable"><div class="remove"><i class="fa fa-times"></i></div>GRC</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php } ?>
	</div>
	<script type="text/javascript">
	$("#managed_nodes .uk-width-1-6").click(function(ev) {
		table_row = $(this).parent();
		$("table", $("#node_details_modal_inner")).hide();
		$("#node_details_modal_container #node_details_modal .i_hostname").html($('div.node_hostname', table_row).text());
		$("#node_details_modal_container #node_details_modal .i_last_patch").html($('div.node_last_updated', table_row).text());
		$("#node_details_modal_container #node_details_modal .i_base_image").html($('div.node_base_image_id', table_row).text());
		$("#node_details_modal_container #node_details_modal .i_platform").html($('.t_platform', table_row).text());
		$("#node_details_modal_container #node_details_modal .i_type").html($('.t_type', table_row).text());
		$("#node_details_modal_container #node_details_modal #i_sp_logo").replaceWith($('.i_sp_logo', table_row).clone().attr('id', 'i_sp_logo'));
		$("#node_details_modal_container #node_details_modal .i_sp_logo").attr('width', parseInt($("#node_details_modal_container #node_details_modal .i_sp_logo").attr('width')) + 30);
		$("table tbody", $("#node_details_modal_inner")).html("");
		$("body").addClass('overlay2');
		$.post("/packages_for_node/" + table_row.attr('rel'), function(result) {
			// Animation is clashing with blur animation so I am adding a delay. :(
			setTimeout(function(e) {
				$("#package_info_loading").remove();
				$("table", $("#node_details_modal_inner")).show();
				$.each(result['packages'], function(key, value) {
					var statusIcon = '<i style="color: green;" class="fa fa-check"></i>';
					if(value['vulnerability_severity'] > 0) {
						if(value['vulnerability_severity'] > 2) {
							statusIcon = '<div class="vuln_info" data-product="' + value['name'] + '" data-version="' + value['version'] + '"><i style="color: red;" class="fa fa-exclamation"></i></div>';
						} else {
							statusIcon = '<div class="vuln_info" data-product="' + value['name'] + '" data-version="' + value['version'] + '"><i style="color: #faa732;" class="fa fa-asterisk"></i></div>';
						}
						
					} 
					
					$("table tbody", $("#node_details_modal_inner")).append("<tr><td style='text-align: center;'>" + statusIcon + "</td><td>" + value['name'] + "</td><td>" + value['version'] + "</td></tr>");
					
				});
				
				$("#node_details_modal_container").click(function(ev) {
					$("body").removeClass('overlay2');
				});
				
			}, 250);
			
		});
		
	});
	
	$(document).on('mouseenter', '.vuln_info', function(ev) {
		var vuln_btn = $(this);
		$.post('/vulnerability_info_for', {'product': $(this).data('product'), 'upstream_version': $(this).data('version')}, function(result) {
			if(!$(".vuln_info.active").length) {
				vuln_btn.addClass('active');
				vuln_btn.append('<div class="info_bubble"><div class="package-name">' + vuln_btn.data('product') + ' ' + vuln_btn.data('version') + '</div><div class="cve-id">' + result['cve_id'] + '</div><div class="i-description">' + result['vulnerability_summary'] + '</div><div class="risk-factor"><strong>Risk Score:</strong> ' + result['risk_score'] + '</div><div class="access-complexity"><strong>Access Complexity:</strong> ' + result['access_complexity'] + '</div><div class="authentication"><strong>Authentication:</strong> ' + result['autentication'] + '</div><div class="confidentiality-impact"><strong>Confidentiality Impact:</strong> ' + result['confidentiality_impact'] + '</div></div>');
			}
			
		});
		
	});
	
	$(document).on('mouseleave', '.vuln_info', function(ev) {
		$(this).removeClass('active');
		$(".info_bubble", $(this)).remove();
	});
	
	$(document).on('click', '.nos-deletable .remove', function(ev) {
		ev.preventDefault();
		ev.stopPropagation();
		ev.stopImmediatePropagation();
	});
	
	$(document).mousemove(function(ev) {
		if(ev.clientX < 30) {
			if(!$("#groups_panel").hasClass('open')) {
				$("#groups_panel").addClass('open');
			}
			
		} else {
			if(ev.clientX > 60) {
				if(!$(".divved").hasClass('pop-out') && !$(".divved").hasClass('popped-out')) {
					$("#groups_panel").removeClass('open');
				}
				
			}
			
		}
		
	});
	
	</script>
</article>
<script type="text/javascript" src="/js/nos.toggle.js"></script>
<script type="text/javascript" src="/js/nos.tabs.js"></script>
@stop