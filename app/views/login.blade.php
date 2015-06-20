@extends('layouts.front')

@section('signin_link') uk-active @stop

@section('content')
<article class="uk-article">
	<div class="uk-grid">
		<div class="uk-width-medium-2-3">
		{{ Form::open(['url' => 'login', 'class' => 'uk-form']) }}
			<fieldset>
				<legend>Member Sign in</legend>
				<div class="uk-form-row">
					{{ $errors->first('email', '<span class="error">:message</span>') }}
					{{ Form::text('email', '', ['placeholder' => 'E-Mail Address']) }}
				</div>
				<div class="uk-form-row">
					{{ $errors->first('password', '<span class="error">:message</span>') }}
					{{ Form::password('password', '', ['placeholder' => 'Password']) }}
				</div>
				<div class="uk-form-row">
					{{ Form::submit('Sign in', ['class' => 'uk-button']) }}
				</div>
			</fieldset>
		{{ Form::close() }}
		<br />
		</div>
		<div class="uk-width-medium-1-3">
			<form class="uk-form">
				<fieldset>
					<legend style="margin: 0; padding: 0;">Don&rsquo;t have an account yet?</legend>
					<p>It&rsquo;s easy to get started. Sign up for a free trial or <a href="#">contact us</a> to see the full power of NoSprawl&rsquo;s patch management solution.</p>
					<a class="uk-button uk-button-success nos-blue" href="/register">Sign up for free</a>
				</fieldset>
			</form>
		</div>
	</div>
</article>
@stop
