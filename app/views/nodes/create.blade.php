@extends('layouts.front')

@section('content')
	
	{{ Form::open(['url' => 'nodes', 'class' => 'uk-form']) }}
		
		<fieldset class="data-uk-margin">
			<legend>Create Node</legend>

			@if(Session::has('message'))
				<div class="uk-form-success">{{ Session::get('message') }}</div>
			@endif
			
			<div class="uk-form-row">
				{{ $errors->first('name', '<div class="uk-form-danger">:message</div>') }}
				{{ Form::text('name', null, ['placeholder' => 'Node Name']) }}
			</div>
			
			<div class="uk-form-row">
				{{ $errors->first('description', '<div class="uk-form-danger">:message</div>') }}
				{{ Form::textarea('description', null, ['placeholder' => 'Node Description']) }}
			</div>

			<div class="uk-form-row">
				{{ Form::submit('Create Node', ['class' => 'uk-button']) }}
			</div>
		
		</fieldset>

	{{ Form::close() }}

@stop