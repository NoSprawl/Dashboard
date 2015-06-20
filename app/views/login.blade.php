@extends('layouts.front')

@section('signin_link') uk-active @stop

@section('content')
<article class="uk-article">
	<div class="uk-grid">
		<div class="uk-width-1-3">
		<form class="uk-form">
		<fieldset>
			<legend>Don&rsquo;t have an account?</legend>
			<p>Get started for free. </p>
		</fieldset>
		</form>
		</div>
		<div class="uk-width-2-3">
		{{ Form::open(['url' => 'login', 'class' => 'uk-form']) }}
			<fieldset>
				<legend>Account Holder: Sign in</legend>
				<div class="uk-form-row">
					{{ $errors->first('email', '<span class="error">:message</span>') }}
					{{ Form::text('email', '', ['placeholder' => 'E-Mail Address']) }}
				</div>
				<div class="uk-form-row">
					{{ $errors->first('password', '<span class="error">:message</span>') }}
					{{ Form::password('password', '', ['placeholder' => 'Password']) }}
				</div>
				<div class="uk-form-row">
					{{ Form::submit('Sign in', ['class' => 'uk-button uk-button-success']) }}
				</div>
			</fieldset>
		{{ Form::close() }}
		</div>
	</div>
</article>
@stop
