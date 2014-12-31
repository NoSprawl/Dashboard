@extends('layouts.front')

@section('content')

	@foreach($nodes as $n)
		<div class="node" data-node_id="{{$n->id}}">
			<ul>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>

			</ul>
		</div>	
	@endforeach

@stop