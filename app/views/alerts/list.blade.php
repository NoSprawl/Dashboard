@section('alerts_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Alerting</h1>
	<a id="new_alert" href="#">New Alert</a>
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
<script type="text/javascript" src="/js/nos.toggle.js"></script>
@stop