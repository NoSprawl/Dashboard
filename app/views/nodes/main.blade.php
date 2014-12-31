@extends('layouts.front')

@section('content')

	<h1 class="uk-article-title">Nodes</h1>

	<div class="uk-grid" data-uk-grid-margin="">
		@foreach($nodes as $n)
			<div class="node uk-width-medium-1-4" data-node_id="{{$n->id}}">
				<div class="uk-panel uk-panel-box">
					<h3 class="uk-panel-title">{{ $n->name }}</h3>
					<ul>
						<li>{{ $n->description }}</li>
						<li>{{ $n->integration->service_provider }}</li>
					</ul>
				</div>
			</div>	
		@endforeach
	</div>

@stop