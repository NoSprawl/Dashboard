@section('users_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Account Users</h1>
	<table class="uk-table">
		<thead>
			<tr>
				<td>User</td>
				<td>Email Address</td>
				<td>Phone Number</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong>You</strong> (Admin)</td>
				<td><strong><?= Auth::user()->email; ?></strong></td>
				<td><strong><?= Auth::user()->phone_number; ?></strong></td>
				<td><a href="#">Edit</a></td>
			</tr>
			<?php if(!$subusers->isEmpty()) { ?>
			<?php foreach($subusers as $subuser) { ?>
			<tr>
				<td><?= $subuser->name; ?></td>
				<td><?= $subuser->email; ?></td>
				<td><?= $subuser->phone_number; ?></td>
				<td><a href="#">Delete</a> | <a href="#">Edit</a></td>
				<td></td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
	</table>
	<button class="uk-button" data-uk-modal="{target:'#new-user-form'}">Add User</button>
</article>
<div id="new-user-form" class="uk-modal">
  <div class="uk-modal-dialog">
  	<a class="uk-modal-close uk-close"></a>
		{{ Form::open(['url' => 'subuser', 'class' => 'uk-form-stacked uk-form']) }}
	    <fieldset>
        <legend>Add a User to This Account</legend>
				<div class="uk-width-2-3">
					<div class="uk-form-row">	
						{{ Form::label('full_name', 'Full Name', ['class' => 'uk-form-label'] ) }}
						{{ $errors->first('full_name', '<span class="error">:message</span>') }}
						{{ Form::text('full_name') }}
					</div>
					<div class="uk-form-row">
					  {{ Form::label('company', 'Company Name', ['class' => 'uk-form-label'] ) }}
						{{ $errors->first('company', '<span class="error">:message</span>') }}
						{{ Form::text('company') }}
					</div>
					<div class="uk-form-row">
						{{ Form::label('email', 'Email Address', ['class' => 'uk-form-label'] ) }}
						{{ $errors->first('email', '<span class="error">:message</span>') }}
						{{ Form::text('email') }}
					</div>
					<div class="uk-form-row">
						{{ Form::label('phone_number', 'Phone Number', ['class' => 'uk-form-label'] ) }}
						{{ $errors->first('phone_number', '<span class="error">:message</span>') }}
						{{ Form::text('phone_number') }}
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
					</div><!-- /uk-form-row -->
				</div><!-- /uk-width-2-3 -->
				<br /><br />
				{{ Form::submit('Create User', ['class' => 'submit uk-button uk-button-success uk-button-large']) }}
	    </fieldset>
		{{ Form::close() }}
  </div>
</div>
@stop