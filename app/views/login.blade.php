@extends('layouts.front')

@section('header')
	@parent
@stop

@section('content')
<article class="uk-article">
	{{ Form::open(['url' => 'login', 'class' => 'uk-form']) }}
		<fieldset>
			<legend>Sign in</legend>
			<div class="uk-form-row">
				{{ $errors->first('email', '<span class="error">:message</span>') }}
				{{ Form::text('email', '', ['placeholder' => 'E-Mail Address']) }}
			</div>
			<div class="uk-form-row">
				{{ $errors->first('password', '<span class="error">:message</span>') }}
				{{ Form::text('password', '', ['placeholder' => 'Password']) }}
			</div>
			<div class="uk-form-row">
				{{ Form::submit('Sign in', ['class' => 'uk-button uk-button-success']) }}
			</div>
		</fieldset>
	{{ Form::close() }}
</article>
@stop
