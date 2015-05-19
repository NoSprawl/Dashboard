@section('content')
<article class="uk-article">
	<h1>Hi <?= $user->name; ?></h1>
	<h2>You&rsquo;ve been invited to join the <?= $parent_user->company_name; ?> patch enforcement application. The below form will allow you to complete your registration.</h2><br />
	{{ Form::open(['url' => 'onboard', 'class' => 'uk-form-stacked uk-form']) }}
	<?php echo Form::hidden('user_confirmation_token', $user->user_confirmation_token); ?>
	<div class="uk-form-row">	
		{{ Form::label('full_name', 'Full Name', ['class' => 'uk-form-label'] ) }}
		{{ $errors->first('full_name', '<span class="error">:message</span>') }}
		<?php echo Form::text('full_name', $user->name); ?>
	</div>
	<div class="uk-form-row">
	  {{ Form::label('company', 'Company Name', ['class' => 'uk-form-label'] ) }}
		{{ $errors->first('company', '<span class="error">:message</span>') }}
		<?php echo Form::text('company', $parent_user->company_name, ['readonly' => 'readonly']); ?>
	</div>
	<div class="uk-form-row">
		{{ Form::label('email', 'Email Address', ['class' => 'uk-form-label'] ) }}
		{{ $errors->first('email', '<span class="error">:message</span>') }}
		<?php echo Form::text('email', $user->email, ['readonly' => 'readonly']); ?>
	</div>
	<div class="uk-form-row">
		<div class="uk-grid uk-grid-preserve">
			<div class="uk-width-1-2">
				{{ Form::label('password', 'Password', ['class' => 'uk-form-label'] ) }}
				{{ $errors->first('password', '<span class="error">:message</span>') }}
				{{ Form::password('password') }}
			</div>
			<div class="uk-width-1-2">
				{{ Form::label('confirm_password', 'Confirm Password', ['class' => 'uk-form-label'] ) }}
				{{ $errors->first('confirm_password', '<span class="error">:message</span>') }}
				{{ Form::password('confirm_password') }}
			</div>
		</div><!-- /uk-width-1-2 -->
	</div><br /><br />
		{{ Form::submit('Complete Registration', ['class' => 'submit uk-button uk-button-success uk-button-large']) }}
	{{ Form::close() }}
</article>
@stop