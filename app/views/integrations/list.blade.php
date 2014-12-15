@section('integrations_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Integrations</h1>
	<table class="uk-table">
		<thead>
	  	<tr>
	    	<th>Access Status</th>
				<th>Service Provider</th>
				<th>Details</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Good</td>
				<td>AWS</td>
				<td><em>Something something something</em></td>
			</tr>
		</tbody>
	</table>
</article>
<button class="uk-button" data-uk-modal="{target:'#my-id'}">Add Integration</button>
<div id="my-id" class="uk-modal">
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
		$service_provider_field.removeClass('uk-form-danger');
		selected_service_provider = $service_provider_field.val();
		if(selected_service_provider == -1) {
			$service_provider_field.addClass('uk-form-danger');
		} else {
			$("#custom_integration_fields").html("");
			$("#submit_new_integration").css('display', 'none');
			$.post('/integrations/fields', {service_provider_name: selected_service_provider}, function(response) {
				fields = eval("(" + response.service_provider_authorization_fields + ")");
				for(var i = 0; i < fields.length; i++) {
					field = fields[i];
					htmlField = '<div class="uk-form-row"><label class="uk-form-label">' + field[1] + '</label><input type="text" name="' + field[0] + '"></div>';
					$("#custom_integration_fields").append(htmlField);
					$("#submit_new_integration").css('display', 'block');
				}
				
			});
			
		}
		
	})($field);
});
</script>
@stop