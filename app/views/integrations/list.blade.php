@section('integrations_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Integrations</h1>
	<table class="uk-table">
		<thead>
	  	<tr>
	    	<th>Access Status</th>
				<th>Service Provider</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($integrations as $integration) { ?>
				<tr>
					<td>Confirmed</td>
					<td><?php echo $integration['service_provider_id']; ?></td>
					<td><a data-method="post" href="/integration/delete/<?php echo $integration['id'] ?>">Delete</a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
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
																										'RackspaceCloud' => 'Rackspace Cloud')) }}
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
				$("#custom_integration_fields").append(eval("(" + response.service_provider_description + ")"));
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

$(window.document).on('submit', "#new-integration-form", function(click_event) {
	$form = $("#new-integration-form form");
	$.post($form.attr("action"), $form.serialize(), function(post_response) {
		if(post_response['status'] == 'created') {
			$(".uk-modal-close:visible").trigger('click');
		} else {
			$(".ajax-error").remove();
			$(".uk-modal:visible form:visible .uk-form-row").first().prepend("<div class='ajax-error uk-alert uk-alert-danger'>Invalid credentials or insuficient permissions. Integration not added.</div>");
			$(".uk-modal:visible form:visible input[type='text']").addClass('uk-form-danger');
		}
		
	});
	
	return false;
});

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