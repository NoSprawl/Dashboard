@section('check_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Status</h1>
	<ul class="uk-tab" data-uk-tab>
	    <li class="uk-disabled"><a href="#">Managed Nodes</a></li>
	    <li class="uk-active"><a href="#">Unmanaged Nodes</a></li>
	</ul>
	<table class="uk-table">
		<thead>
	  	<tr>
	    	<th>Node Status</th>
				<th>Service Provider</th>
				<th>Base Image</th>
				<th>Base Image</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($nodes as $node) { ?>
				<tr>
					<td>Status</td>
					<td>Mike</td>
					<td>Help</td>
					<td>Help</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</article>
@stop