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

.spinner {
  margin: 0px 0 0 0;
	left: 0;
  width: 70px;
  text-align: center;
	opacity: 1;
	-webkit-transition: opacity .5s;
}

.spinner.out {
	opacity: 0;
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
	top: 3px;
	color: #39f;
	-webkit-transition: opacity .5s;
	opacity: 1;
}

div.limbo.out {
	font-size: .8em;
	position: absolute;
	left: 70px;
	top: 3px;
	color: #39f;
	opacity: 0;
}

.spinner_holder .spinner {
	position: relative;
	top: 3px;
}

.spinner_holder:hover div.limbo {
	text-decoration: underline;
}
</style>
<article class="uk-article">
	<h1 class="uk-article-title">Environment Status</h1>
	<?php if(!empty($page_data['unmanaged_nodes']) || !empty($page_data['managed_nodes'])) { ?>
	<?php if(!sizeof($page_data['unmanaged_nodes']) && !sizeof($page_data['managed_nodes'])) { ?>
	<?php if(!sizeof(Auth::user()->nodes)) { ?>
		<div class="advice">
			<p>NoSprawl can&rsquo;t see any of your nodes yet.</p>
			<p>To allow node discovery please create a <a href="/integrations">cloud integration</a>. Node discovery means that NoSprawl will call out to your cloud provider(s) and download basic details about your cloud environments such as what operating system they are running, what base images they depend on, etc. NoSprawl will periodically keep this data up to date through continuous monitoring.</p>
			<p>Creating a cloud integration will not have any effect on your bill.</p>
		</div>
	<?php } else { ?>
		<div class="advice">
			<p>We can see {{ sizeof(Auth::user()->nodes) }} nodes, but none of them are running, so we&rsquo;re not showing them here.</p>
		</div>
	<?php } ?>
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
	    <div class="uk-width-1-6">Auto Manage</div>
	    <div class="uk-width-1-6">Manual Deploy</div>
			<div class="uk-width-1-6">Platform &amp; Provider</div>
			<div class="uk-width-1-6">Physical Location</div>
			<div class="uk-width-2-6">Network Info</div>
		</div>
		
		<?php foreach($page_data['unmanaged_nodes'] as $node) { ?>
			<?php $integration = Integration::find($node->integration_id); ?>
			<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
			<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
			
			<div class="uk-grid uk-grid-collapse nos-row" data-windows-bool="<?= (strtoupper($node->platform) == 'WINDOWS') ? 'true' : 'false' ?>">
				<div class="uk-width-1-6 spinner_holder">
				<?php if($node->service_provider_status != "terminated") { ?>
					<?php if(!$node->limbo && $node->service_provider_status == 'running') { ?>
					<div class="switch switch-yellow">
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="enable" id="toggle-enable-<?= $node->id; ?>">
					  <label data-integration="<?= $integration->id ?>" for="toggle-enable-<?= $node->id; ?>" class="switch-label switch-label-off">Enabled</label>
					  <input type="radio" class="switch-input" name="toggle-enable-<?= $node->id; ?>" value="disable" id="toggle-disable-<?= $node->id; ?>" checked>
					  <label for="toggle-disable-<?= $node->id; ?>" class="switch-label switch-label-on">Disabled</label>
					  <span class="switch-selection"></span>
					</div>
					<?php } else { ?>
						<?php $problems = $node->problems; ?>
						<?php if(sizeof($problems) == 0) { ?>
							<?php if($node->service_provider_status == 'running') { ?>
							<div class="spinner">
							  <div class="bounce1"></div>
							  <div class="bounce2"></div>
							  <div class="bounce3"></div>
							</div>
							<div class="limbo">Activating</div>
							<?php } else { ?>
							<div class="spinner">
							  <div class="bounce1"></div>
							  <div class="bounce2"></div>
							  <div class="bounce3"></div>
							</div>
							<div class="limbo">Not available</div>
							<?php } ?>
						<?php } else { ?>
							<?= (!$problems[0]->long_message) ? '<div class="problem_area hidden">': '<div class="problem_area hidden long">' ?>
								<div class="nubcover"></div>
								<div class="nub"></div>
								<div class="problem_details">
									<div class="inner">
										<?= $problems[0]->reason; ?>
									</div>
								</div>
								<ul>
									<?php foreach($problems[0]->remediations as $remediation) { ?>
										<li><a class="remediate" data-id="<?= $remediation->id; ?>" href="#"><?= $remediation->name ?></a></li>
									<?php } ?>
								</ul>
							</div>
							<a class="problem" data-id="<?= $problems[0]->id; ?>" href="#"><i class="fa fa-bullhorn">&nbsp;&nbsp;</i><span><?= $problems[0]->description; ?></span></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</div>
				<div class="uk-width-1-6" style="position: relative;">
					<?php
					if($node->platform == "Windows") {
						print "<a class=\"win_manual\" href=\"#\">Deploy</a>";
					
					} else {
						print "<a class=\"linux_manual\" href=\"#\">Deploy</a>";
					}
					?>
					<span style='top: 1px; position: relative;'></span>
				</div>
				<div class="uk-width-1-6">
					<?php
					if($node->platform == "Windows") {
						print "<span class='package_man t_platform'><img style='top: 0px; position: relative;' src='/svg/windows.svg' width='18px'></span>";
						
					} else {
						print "<span class='package_man t_platform'><img style='top: 0px; position: relative;' src='/svg/linux.svg' width='18px'></span>";
					}
					
					print "<span class='slash' style='top: 1px; position: relative;'>/</span>";
					
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
				<div class="uk-width-1-6"><div class="shift5"><?= $node->friendly_availability_zone; ?></div></div>
				<div class="uk-width-2-6"><div class="trim_long shift5"><?= ($node->public_dns_name) ? $node->public_dns_name : "Network not initialized." ?></div></div>
			</div>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div id="managed_nodes" class="nos-hidable" style="<?php if((sizeof($page_data['managed_nodes']) == 0)) {echo 'display: none;';} ?>">
		<div class="uk-grid uk-grid-collapse nos-title-row">
	    <div class="uk-width-1-6">Patch Risk</div>
	    <div class="uk-width-1-6">Provider &amp; Platform</div>
			<div class="uk-width-1-6">Physical Location</div>
			<div class="uk-width-1-6">Base Image</div>
			<div class="uk-width-1-6">Host Name</div>
			<div class="uk-width-1-6">Classifications</div>
		</div>
	
	<?php foreach($page_data['managed_nodes'] as $node) { ?>
		<?php $integration = Integration::find($node->integration_id); ?>
		<?php $service_provider_cluster_id = (empty($node->service_provider_cluster_id)) ? 'None' : $node->service_provider_cluster_id; ?>
		<?php $service_provider_description = ($node->description == " ") ? 'None' : $node->description; ?>
		<div class="uk-grid uk-grid-collapse nos-row nos-activatable" rel="<?= $node->id; ?>" class="<?= $service_provider_cluster_id; ?>">
			<div class="uk-width-1-6 node_status activatable">
			<?php
			if($node->vulnerable) {
				print "<span class='stopped'></span><span class='statuslabel'>High Risk</span>";
			} else {
				print "<span class='running'></span><span class='statuslabel'>Healthy</span>";
			}
		
			?>
			</div>
			<div class="uk-width-1-6 node_cloud_provider activatable">
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
			<div class="uk-width-1-6 node_last_updated activatable">
			<div class="trim_long shift5 activatable"><?= $node->friendly_availability_zone ?></div>
			</div>
			<div class="uk-width-1-6 node_base_image_id activatable"><div class="trim shift5"><?= $node->service_provider_base_image_id; ?></div></div>
			<div class="uk-width-1-6 node_hostname activatable"><div class="trim_long shift5"><?= $node->hostname; ?></div></div>
			<div class="uk-width-1-6 node_packages"><div class="shift5">
				<?php foreach(NodeGroupAssociation::where('node_id', '=', $node->id)->get() as $grp) { ?>
				<?php
				$groupInfo = NodeGroup::find($grp->group_id);
				?>
				<div class="nos-deletable" rel="<?= $grp->id; ?>"><span><?= $groupInfo->name; ?></span><a href="#" class="remove">x</a></div>
				<?php } ?>
			</div></div>
		</div>
	<?php } ?>
	
	<?php } ?>
	</div>
	<script type="text/javascript">
	$(function(ev) {
		// organize lines
		$("#groups_panel li .divved").each(function(index, item) {
			var divHeight = item.offsetHeight;
			var lineHeight = parseInt(item.style.lineHeight);
			var lines = divHeight / lineHeight;
			if(!(lines > 0)) {
				$(item).addClass('single-line')
			}
			
		});
		
		// let people delete stuff
		$("body").on("mousedown", ".nos-deletable .remove", function(obj) {
			var o = $(this).parent();
			$.post("/groupAssoc/delete/" + o.attr("rel"), function(response) {
				o.remove();
			});
			
			$("body").addClass("ignoreClick");
			
		});
		
	});
	
	$("#groups_panel li").mousedown(function(ev) {
		var originalX = ev.clientX;
		var originalY = ev.clientY;
		
		var thiss = this;
		
		$(document).off("mousemove", window.generalMouseMoveHandler);
		
		window.draggingTagMouseMovementManagement = function(ev) {
			if(!$("body").hasClass("dragging")) {
				$("body").addClass("dragging");
			}
			
			if(!$("#dragging_tag").length) {
				$('#whole-bird').append("<div rel='" + $(thiss).attr('rel') + "' id='dragging_tag'>" + $(thiss).text() + "</div>");
			}
			
			$("#dragging_tag").css('top', parseInt(ev.pageY) + 59 + "px");
			$("#dragging_tag").css('left', parseInt(ev.pageX) + "px");
		}
		
		window.draggingMouseUpHandler = function(ev) {
			if($("body").hasClass("dragging")) {
				$("body").removeClass("dragging");
			}
			
			// Let's see if a drag happened
			if(ev.clientX != originalX || ev.clientY != originalY) {
				$("#managed_nodes .nos-row").each(function(key, row) {
					if(ev.pageY > $(row).offset().top && ev.pageY < $(row).offset().top + 41) {
						$.post("/group/assoc/" + $(row).attr("rel") + "/" + $("#dragging_tag").attr("rel"), function(result) {
							$(".node_packages .shift5", $(row)).append('<div class="nos-deletable" rel="' + result['new_id'] + '">' + result['new_name'] + '<a href="#" class="remove">x</a></div>');
						});
						
					}
					
				});
				
			}
			
			$(document).off("mousemove", window.draggingTagMouseMovementManagement);
			$(document).off("mouseup", window.draggingMouseUpHandler);
			//$(document).on("mousemove", window.generalMouseMoveHandler);
			$("#dragging_tag").remove();
		}
		
		$(document).on("mouseup", window.draggingMouseUpHandler);
		
		$(document).on("mousemove", window.draggingTagMouseMovementManagement);
	});
	
	$("#managed_nodes .activatable").click(function(ev) {
		$("#groups_panel").removeClass("open");
		
		if($("body").hasClass("ignoreClick")) {
			$("body").removeClass("ignoreClick");
			return false;
		}
		
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
					$("#groups_panel").addClass("open");
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
				vuln_btn.append('<div class="info_bubble"><div class="package-name">' + vuln_btn.data('product') + ' ' + vuln_btn.data('version') + '</div><div class="cve-id">' + result['cve_id'] + '</div><div class="i-description">' + result['vulnerability_summary'] + '</div><div class="risk-factor"><strong>Risk Score:</strong> ' + result['risk_score'] + '</div><div class="access-complexity"><strong>Access Complexity:</strong> ' + result['access_complexity'] + '</div><div class="authentication"></div><div class="confidentiality-impact"><strong>Confidentiality Impact:</strong> ' + result['confidentiality_impact'] + '</div></div>');
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
	
	window.generalMouseMoveHandler = function(ev) {
		if($("#managed_nodes").is(":visible")) {
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
			
		}
		
	}
	
	//$(document).on("mousemove", window.generalMouseMoveHandler);
	$(function(ev) {
		if($("#managed_nodes").is(":visible")) {
			$("#groups_panel").addClass("open");
		}
		
		$(".problem_area").mouseenter(function(ev) {
			if($(this).is(":visible")) {
				window.holdProblem = true;
			}
			
		});
		
		$(".problem_area").mouseleave(function(ev) {
			if($(this).is(":visible")) {
				delete(window.holdProblem);
				var $that = $(this);
				setTimeout(function(ev) {
					if((typeof(window.holdProblem) == "undefined")) {
						$that.addClass("hidden");
					}
				
				}, 1000);
				
			}
			
		});
		
		$(".problem").click(function(ev) {
			window.holdProblem = true;
			$(this).prev().removeClass("hidden");
			ev.preventDefault();
		});
		
		$(".problem").mouseleave(function(ev) {
			delete(window.holdProblem);
			var $that = $(this);
			setTimeout(function(ev) {
				if((typeof(window.holdProblem) == "undefined")) {
					$that.prev().addClass("hidden");
				}
				
			}, 1000);
			
		});
		
		$("a.remediate").click(function(ev) {
			if(!$(this).parent().parent().parent().hasClass("hidden")) {
				$(this).parent().parent().parent().addClass("hidden")
				$.post("/remediation/" + $(this).data("id"), function(result) {
					console.log(result);
				});
				
			}
			
			ev.preventDefault();
			
		});
		
	});
	
	$(function(ev) {
		$("a.problem").each(function(index, item) {
			if(!$("#managed_nodes").is(":visible")) {
				$(this).addClass("highlight");
			}
		
		});
		
	});
	
	// Generate modals
	function nos_modal(innerH) {
		$("#groups_panel").removeClass("open");
	
		/*if($("body").hasClass("ignoreClick")) {
			$("body").removeClass("ignoreClick");
			return false;
		}*/
		
		$("body").addClass('nos-overlay');
		$("#whole-bird").after("<div id='nos_modal_container'><div id='nos_modal'><div class='modal-inner'>" + innerH + "</div></div></div>");
		
		setTimeout(function(ev) {
			$("#nos_modal").addClass('active');
		}, 150);
		
	}
	
	$(function(ev) {
		// Generate and display the policy form
		/*$("body").on("click", ".nos-deletable", function(ev) {
			nos_modal("<h4><span class='subject'>" + $("span", this).text() + "</span> Classification Policies</h4><div class='uk-grid'><div class='uk-grid-1-3'><label>Assets With This Classification</label></div><div class='uk-grid-2-3'><select><option value='CAN'>Can</option><option value='CAN'>Can't</option></select></div></div>");
			ev.stopImmediatePropagation();
			ev.stopPropagation();
			ev.preventDefault();
		});*/
		
		$("body").on("click", ".modal-inner", function(ev) {
			ev.stopImmediatePropagation();
		});
		
		$("body").on("click", "#nos_modal_container, .nos-modal-close", function(ev) {
			if($("#managed_nodes").is(":visible")) {
				$("#groups_panel").addClass("open");
			}
			
			$("body").removeClass('nos-overlay');
			setTimeout(function(ev) {
				$("#nos_modal_container").remove();
			}, 250);
			
		});
		
	});
	</script>
</article>
<script type="text/javascript" src="/js/nos.toggle.js"></script>
<script type="text/javascript" src="/js/nos.tabs.js"></script>
@stop