@section('alerts_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Alerts</h1>
	<p>When the patch status of a managed environment changes, the people configured below will be notifified if the specified conditions are met.</p>
	<table class="uk-table">
		<thead>
			<tr>
				<th>Recipient</th>
				<th>Package(s)</th>
				<th>Node(s)</th>
				<th>Trigger</th>
				<th>Modify Alert</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($alerts as $alert) { ?>
			<tr>
				<td><?= User::find($alert->user_id)->name; ?></td>
				<td>All Packages</td>
				<td><?php if($alert->condition == -1) { ?>
				All Managed Nodes
				<?php } else { ?>
				<?= Node::find($alert->condition)->hostname; ?>
				<?php } ?>
				</td>
				<td><?php if($alert->value == 1) { ?>
				New vulnerabilities are discovered.
				<?php } elseif($alert->value == 2) { ?>
				Existing vulnerabilties are remedied.
				<?php } ?></td>
				<td><a href="#">Edit</a> | <a href="#">Delete</a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<button id="new_alert" class="uk-button" data-uk-modal="{target:'#new-alert-form'}">Create an Alert</button>
</article>
<style type="text/css">
.nos-modal .uk-form-row {
	margin-top: 0px;
}
.nos-modal select {
	margin-bottom: 7px;
}

.nos-modal label {
	margin-bottom: 4px;
}
</style>
<script type="text/javascript" type="text/javascriot">
$('#new_alert').click(function(ev) {
	$('body').addClass('overlay');
	$('body').append('<div class="disabler"></div>');
	$('.disabler').append('<div class="nos-modal"><form id="new_alert_form" class="uk-form" action="alert" method="post"><fieldset><legend>New Alert</legend><div class="uk-form-row"><label class="uk-form-label">Who should be notified?</label><div class="uk-form-row"><select name="user"><option value="<?= Auth::user()->id; ?>"><?= Auth::user()->name; ?> (Me)</option><?php foreach($users as $user) { ?><option value="<?= $user->id; ?>"><?= $user->name; ?></option><?php } ?></select></div></div><div class="uk-form-row"><label>When</label><div class="uk-form-row"><select name="value"><option value="1">New vulnerabilities are discovered</option><option value="2">Existing vulnerabilities are remedied</option></select></div><div class="uk-form-row"><label>On</label></div><select name="condition"><option value="-1">Any Managed Node</option><?php foreach($managed_nodes as $node) { ?><option value="<?= $node->id; ?>"><?= $node->hostname; ?></option><?php } ?> ?></select></div></fieldset><span style="display: block; height: 2px;"></span><a class="uk-button uk-button-large uk-button modal-out" href="#">Back to List</a><a onclick=\'$("#new_alert_form").submit()\' class="uk-button uk-button-large uk-button-success modal-out" href="#">Create Alert</a></form></div>');
	var that = this;
	$('.disabler').click(function(click_event) {
		$('.disabler').remove();
		$('.nos-modal').removeClass('final');
		$('body').removeClass('overlay');
	});
	
	$('.nos-modal').click(function(click_event) {
		return false;
	});
	
	$('.modal-out').click(function(click_event) {
		$('.disabler').click();
		return false;
	});
	
	setTimeout(function(ev) {
		$(".nos-modal").addClass('final');
	}, 1);
	
	ev.stopPropagation();
	ev.preventDefault();
});
</script>
@stop