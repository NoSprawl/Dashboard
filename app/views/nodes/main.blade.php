@extends('layouts.front')

@section('content')

	<h1 class="uk-article-title">Nodes</h1>

	@if(Session::has('message'))
		<div class="uk-form-success">{{ Session::get('message') }}</div>
	@endif

	<div class="uk-grid" data-uk-grid-margin="">
	
		@foreach($nodes as $n)
			<div class="node uk-width-medium-1-4" data-node_id="{{$n->id}}">
				<div class="uk-panel uk-panel-box">
					<h3 class="uk-panel-title">{{ $n->name }}</h3>
					<ul>
						<li>{{ $n->description }}</li>
						@if($n->integration)
							<li>{{ $n->integration->service_provider }}</li>
						@else
							<li>No integration available</li>
						@endif
					</ul>
					<a class="uk-button" href="{{ route('nodes.edit', $n->id) }}">Edit Node</a>
					{{ Form::open(['route' => ['nodes.destroy', $n->id], 'method' => 'delete']) }}
        				<button type="submit" class="uk-button">Delete Node</button>
    				{{ Form::close() }}
				</div>
			</div>	
		@endforeach
	</div>

@stop