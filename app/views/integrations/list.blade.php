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
		<form class="uk-form">
	    <fieldset>
        <legend>Add a New Integration</legend>
        <div class="uk-form-row">
					<label>Integration Provider</label>
        	<select id="integration_type">
						<option value="-1">Select a Provider</option>
						<option value="aws">Amazon Web Services</option>
						<option value="rspc">Rackspace Cloud</option>
					</select>
        </div>
	    </fieldset>
		</form>
  </div>
</div>
@stop