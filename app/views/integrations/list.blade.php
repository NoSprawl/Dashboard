@section('integrations_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Integrations</h1>
	<?php if(!$integrations->isEmpty()) { ?>
	<table class="uk-table">
		<thead>
	  	<tr>
				<th width="70">Cloud</th>
				<th width="110">Access Status</th>
				<th width="190">Automatically Manage All</th>
				<th>PEM Keys</th>
				<th>Associated Nodes</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($integrations as $integration) { ?>
				<tr data-integration-id="<?= $integration->id; ?>">
					<td>
						<?php
						switch($integration['service_provider']) {
							case "AmazonWebServicesIntegration":
								print "<img style='top: 0px; position: relative;' src='/svg/aws.svg' width='53px'>";
							break;
							case "RackspaceCloudIntegration":
								print "<img style='top: 3px; position: relative;' src='/svg/rackspace.svg' width='61px'>";
							break;
						}
						?>
					</td>
					<td>
					<div style="position: relative; top: -1px;">
					<?php
					switch($integration['status']) {
						case "Confirmed":
							print "<span class='running'></span><span class='statuslabel'>Confirmed</span>";
						break;
						
						default:
							print "<span class='stopped'></span><span class='statuslabel'>Unauthorized</span>";
					}
					?>
					</div>
					</td>
					<td>
						<div class="switch switch-yellow">
						  <input type="radio" class="switch-input" name="toggle-enable-<?= $integration['id']; ?>" value="enable" id="toggle-enable-<?= $integration['id']; ?>">
						  <label for="toggle-enable-<?= $integration['id']; ?>" class="switch-label switch-label-off">On</label>
						  <input type="radio" class="switch-input" name="toggle-enable-<?= $integration['id']; ?>" value="disable" id="toggle-disable-<?= $integration['id']; ?>" checked>
						  <label for="toggle-disable-<?= $integration['id']; ?>" class="switch-label switch-label-on">Off</label>
						  <span class="switch-selection"></span>
						</div>
					</td>
					
					
					<td><a style="postion: relative; padding-top: 2px; display: block; font-size: .95em;" rel="<?= $integration['id']; ?>" class="key_manage" href="#"><i class="fa fa-key">&nbsp;&nbsp;</i>Manage Keys</a></td>
					<td style="text-align: center;"><div style="position: relative; padding-top: 2px;"><?php echo $integration->node_count(); ?></div></td>
					<td><a data-method="post" href="/integration/delete/<?php echo $integration['id'] ?>">Delete</a> | <a data-method="post" href="/integration/enqueueJobs/<?php echo $integration['id'] ?>">Check Queue</a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } else { ?>
	<div class="advice">
	<p>You haven&rsquo;t created any integrations yet.</p>
	<p>To get started, click the Add Integration button below and choose your cloud provider.</p>
	</div>
	<?php } ?>
	<button class="uk-button" data-uk-modal="{target:'#new-integration-form'}">Add Integration</button>
</article>
<div id="new-integration-form" class="uk-modal">
  <div class="uk-modal-dialog">
  	<a class="uk-modal-close uk-close"></a>
		{{ Form::open(['url' => 'integration', 'class' => 'uk-form-stacked uk-form']) }}
	    <fieldset>
        <legend>Add a New Integration</legend>
        <div class="uk-form-row">
					<label class='uk-form-label'>Integration Provider</label>
					{{ Form::select('integration_type', array('-1' => 'Select a provider',
																										'AmazonWebServices' => 'Amazon Web Services',
																										'RackspaceCloud' => 'Rackspace Cloud',
																										'OpenStack' => 'OpenStack')) }}
        </div><br />
				<div id="custom_integration_fields">
				
				</div><br />
				<div id="submit_new_integration" class="uk-form-row" style="display: none;">
					{{ Form::submit('Authenticate', ['class' => 'submit uk-button uk-button-success uk-button-large']) }}
				</div>
	    </fieldset>
		{{ Form::close() }}
  </div>
</div>
<style type="text/css">
.sk-spinner-wave.sk-spinner {
  margin: 0 auto;
  width: 50px;
  height: 30px;
  text-align: center;
  font-size: 10px; }
.sk-spinner-wave div {
  background-color: #333;
  height: 100%;
  width: 6px;
  display: inline-block;
  -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
          animation: sk-waveStretchDelay 1.2s infinite ease-in-out; }
.sk-spinner-wave .sk-rect2 {
  -webkit-animation-delay: -1.1s;
          animation-delay: -1.1s; }
.sk-spinner-wave .sk-rect3 {
  -webkit-animation-delay: -1s;
          animation-delay: -1s; }
.sk-spinner-wave .sk-rect4 {
  -webkit-animation-delay: -0.9s;
          animation-delay: -0.9s; }
.sk-spinner-wave .sk-rect5 {
  -webkit-animation-delay: -0.8s;
          animation-delay: -0.8s; }

@-webkit-keyframes sk-waveStretchDelay {
  0%, 40%, 100% {
    -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4); }

  20% {
    -webkit-transform: scaleY(1);
            transform: scaleY(1); } }

@keyframes sk-waveStretchDelay {
  0%, 40%, 100% {
    -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4); }

  20% {
    -webkit-transform: scaleY(1);
            transform: scaleY(1); } }
</style>
<div id="new-key-form" class="uk-modal">
  <div class="uk-modal-dialog">
  	<a class="uk-modal-close uk-close"></a>
		<div class="uk-grid">
			<div class="uk-width-1-2"><div class="uk-panel uk-panel-box">
				{{ Form::open(['url' => 'keys', 'class' => 'uk-form-stacked uk-form', 'id' => 'upload_key', 'files' => 'true']) }}
			    <fieldset>
		        <legend>Add Credentials</legend>
		        <div class="uk-form-row">
							<label class='uk-form-label'>Username (optional)</label>
							{{ Form::text('username'); }}
							<br />
		        </div>
		        <div class="uk-form-row">
							<label class='uk-form-label'>Password (optional)</label>
							{{ Form::text('password'); }}
							<br />
		        </div>
		        <div class="uk-form-row">
							<label class='uk-form-label'>Upload PEM</label>
							{{ Form::file('key'); }}
							<br />
		        </div>
						<div id="submit_new_integration" class="uk-form-row">
							<?php echo Form::hidden('integration_id', ""); ?>
							{{ Form::submit('Securely Upload Key', ['class' => 'submit uk-button', 'style' => 'width: 150px;' ]) }}
						</div>
			    </fieldset>
				{{ Form::close() }}
			</div>
		</div>
		<div class="uk-width-1-2">
				<ul class="uk-list uk-list uk-width-medium-1-3" id="keys_area">
					<li class="sk-spinner sk-spinner-wave">
						<div class="sk-rect1"></div>
					  <div class="sk-rect2"></div>
					  <div class="sk-rect3"></div>
					  <div class="sk-rect4"></div>
					  <div class="sk-rect5"></div>
					 </li>
				</ul>
			</div>
		</div>
		
  </div>
</div>
<script type="text/javascript">
$(window.document).on('change', "select[name='integration_type']", function(change_event) {
	$field = $(this);
	(function getValidationFields($service_provider_field) {
		$(".ajax-error").remove();
		$service_provider_field.removeClass('uk-form-danger');
		selected_service_provider = $service_provider_field.val();
		$("#custom_integration_fields").html("");
		$("#submit_new_integration").css('display', 'none');
		if(selected_service_provider == -1) {
			$service_provider_field.addClass('uk-form-danger');
		} else {
			$.post('/integrations/fields', {service_provider_name: selected_service_provider}, function(response) {
				fields = eval("(" + response.service_provider_authorization_fields + ")");
				description = eval("(" + response.service_provider_description + ")");
				$("#custom_integration_fields").append(description);
				for(var i = 0; i < fields.length; i++) {
					field = fields[i];
					htmlField = '<div class="uk-form-row"><label class="uk-form-label">' + field[1] + '</label><input type="text" name="authorization_field_' + (i + 1) + '"></div>';
					$("#custom_integration_fields").append(htmlField);
					$("#submit_new_integration").css('display', 'block');
				}
				
			});
			
		}
		
	})($field);
});

$(function() {
	$('.key_manage').click(function(ev) {
		$.post("/keyNamesFor/" + $(this).parent().parent().data("integration-id"), function(response) {
			$("#keys_area").html("");
			$("#keys_area").append("<li>" + response + "</li>");
		});
		
	});
	
})

$(window.document).on('submit', "#new-integration-form", function(click_event) {
	$form = $("#new-integration-form form");
	$.post($form.attr("action"), $form.serialize(), function(post_response) {
		if(post_response['status'] == 'created') {
			$(".uk-modal-close:visible").trigger('click');
		} else {
			$(".ajax-error").remove();
			if(post_response['status'] == "api_error") {
				$(".uk-modal:visible form:visible .uk-form-row").first().prepend("<div class='ajax-error uk-alert uk-alert-danger'>Invalid credentials or insuficient permissions. Integration not added.</div>");
			} else if(post_response['status'] == "form_error") {
				$(".uk-modal:visible form:visible .uk-form-row").first().prepend("<div class='ajax-error uk-alert uk-alert-danger'>All fields are required and duplicate Integrations are not allowed. Integration not added.</div>");
			}
			
			$(".uk-modal:visible form:visible input[type='text']").addClass('uk-form-danger');
			
		}
		
	});
	
	return false;
});

$(".key_manage").click(function(click_event) {
	var modal = UIkit.modal("#new-key-form");
	if (modal.isActive()) {
	 	modal.hide();
	} else {
		$("input[name='integration_id']").val($(this).attr('rel'));
		modal.show();
	}
	
})

// Rails style delete links
$(function(){
	$('[data-method]').append(function(){
		return "\n"+
		"<form action='"+$(this).attr('href')+"' method='post' style='display:none;'>\n"+
		"	<input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
		"</form>\n"
  })
  .removeAttr('href')
  .attr('style','cursor:pointer;')
  .attr('onclick','$(this).find("form").submit();');
});
</script>
@stop