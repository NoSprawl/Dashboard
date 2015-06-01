@section('users_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Account Users</h1>
	<table class="uk-table">
		<thead>
			<tr>
				<td>User</td>
				<td>Email Address</td>
				<td>Last Login</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
			<?php if(Auth::user()->parent_user_id == null) { ?>
			<tr>
				<td><strong>You</strong> (Admin)</td>
				<td><strong><?= Auth::user()->email; ?></strong></td>
				<td><strong><?= Auth::user()->last_login; ?></strong></td>
				<td><a href="#">Edit</a></td>
			</tr>
			<?php } else { ?>
			<tr>
				<td><strong><?= User::find(Auth::user()->parent_user_id)->name; ?></strong> (Admin)</td>
				<td><strong><?= User::find(Auth::user()->parent_user_id)->email; ?></strong></td>
				<td><strong><?= User::find(Auth::user()->parent_user_id)->last_login; ?></strong></td>
				<td><a href="#">Edit</a></td>
			</tr>
			<tr>
				<td><strong><?= Auth::user()->name; ?></strong> (You)</td>
				<td><strong><?= Auth::user()->email; ?></strong></td>
				<td><strong><?= Auth::user()->last_login; ?></strong></td>
				<td><a href="#">Edit</a></td>
			</tr>
			<?php } ?>
			<?php foreach($active_subusers as $subuser) { ?>
			<tr>
				<td><?= $subuser->name; ?></td>
				<td><?= $subuser->email; ?></td>
				<td><?= $subuser->last_login; ?></td>
				<td><a data-method="post" href="/users/delete/<?= $subuser->id; ?>">Delete</a> | <a href="#">Edit</a></td>
				<td></td>
			</tr>
			<?php } ?>
			<?php foreach($limbo as $limbo_user) { ?>
			<tr>
				<td><?= $limbo_user->name; ?></td>
				<td><?= $limbo_user->email; ?></td>
				<td>Hasn&rsquo;t logged in yet</td>
				<td><a href="#">Delete</a> | <a href="#">Edit</a></td>
				<td></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if(Auth::user()->parent_user_id == null) { ?><button class="uk-button" data-uk-modal="{target:'#new-user-form'}">Add User</button><?php } ?>
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
						{{ Form::label('email', 'Email Address', ['class' => 'uk-form-label'] ) }}
						{{ $errors->first('email', '<span class="error">:message</span>') }}
						{{ Form::text('email') }}
					</div>
				</div><!-- /uk-width-2-3 -->
				<br /><br />
				{{ Form::submit('Create User', ['class' => 'submit uk-button uk-button-success uk-button-large']) }}
	    </fieldset>
		{{ Form::close() }}
  </div>
</div>
<script type="text/javascript">
// Rails style delete links
$(function(){
	$('[data-method]').append(function(){
		return "\n"+
		"<form action='"+$(this).attr('href')+"' method='post' style='display:none;'>\n"+
		"	<input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
		"</form>\n"
  })
  .removeAttr('href')
  .attr('style','cursor:pointer;')
  .attr('onclick','$(this).find("form").submit();');
});
</script>
@stop