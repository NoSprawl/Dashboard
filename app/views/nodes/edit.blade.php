@extends('layouts.front')

@section('content')

	{{ Form::model($node, ['route' => ['node.create']]) }}
		
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', $node->name, ['placeholder' => 'node name']) }}

		{{ Form::label('description', 'Description') }}
		{{ Form::textarea('name', $node->description, ['placeholder' => 'Something descriptive about this node']) }}

	{{ Form::close() }}

@stop