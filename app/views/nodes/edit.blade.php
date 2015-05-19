@extends('layouts.front')

@section('content')
 
	{{ Form::model($node, ['route' => ['nodes.update', $node->id], 'method' => 'PUT', 'class' => 'uk-form']) }}
		
		<fieldset class="data-uk-margin">
			<legend>Edit Node {{ $node->name }}</legend>
			
			<div class="uk-form-row">
				{{ Form::label('name', 'Name') }}
			</div>
			<div class="uk-form-row">
				{{ $errors->first('name', '<div class="uk-form-danger">:message</div>') }}
				{{ Form::text('name') }}
			</div>

			<div class="uk-form-row">
				{{ $errors->first('description', '<div class="uk-form-danger">:message</div>') }}
				{{ Form::label('description', 'Description') }}
			</div>
			<div class="uk-form-row">
				{{ Form::textarea('description') }}
			</div>

			<div class="uk-form-row">
				{{ Form::submit('Update Node', ['class' => 'uk-button']) }}
			</div>
		</fieldset>

	{{ Form::close() }}

@stop