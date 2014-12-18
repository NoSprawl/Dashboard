@extends('layouts.front')

@section('signup_link') uk-active @stop

@section('content')
<style type="text/css">
select {
	display: none;
}
</style>
<article class="uk-article">
	<h1>Sign up</h1>
	{{ Form::open(['url' => 'register', 'class' => 'uk-form-stacked uk-form']) }}
		<input type="hidden" name="temp_expmonthyear" id="temp_expmonthyear">
		<fieldset>
			<legend>Account Information</legend>
			<div class="uk-grid uk-grid-preserve">
			<div class="uk-width-1-3">
				<p>Our patch management solutions are designed to give you and your team increased visibility into where your infrastructure stands from a patching perspective.</p>
				<p>If you have any questions about NoSprawl then just <a target="_blank" href="http://nosprawl.com/contact-us.html">contact us</a>. We can create custom plans if you have 100 or more nodes that you want to manage.</p>
			</div>
			<div class="uk-width-2-3">
			<div class="uk-form-row">
			
				<label class="uk-form-label">Full Name</label>
				{{ $errors->first('full_name', '<span class="error">:message</span>') }}
				{{ Form::text('full_name') }}
			</div>
			<div class="uk-form-row">
			  <label class="uk-form-label">Company Name</label>
				{{ $errors->first('company', '<span class="error">:message</span>') }}
				{{ Form::text('company', '') }}
			</div>
			<div class="uk-form-row">
				<label class="uk-form-label">Email Address</label>
				{{ $errors->first('email', '<span class="error">:message</span>') }}
				{{ Form::text('email') }}
			</div>
			<div class="uk-form-row">
			<div class="uk-grid uk-grid-preserve">
				<div class="uk-width-1-2">
					<label class="uk-form-label">Password</label>
					{{ $errors->first('password', '<span class="error">:message</span>') }}
					{{ Form::password('password') }}
				</div>
				<div class="uk-width-1-2">
					<label class="uk-form-label">Confirm Password</label>
					{{ $errors->first('confirm_password', '<span class="error">:message</span>') }}
					{{ Form::password('confirm_password') }}
				</div>
			</div>
			</div>
			</div>
			</div>
		</fieldset>
		<br />
		<fieldset>
			{{ Form::select('plan', array('nosprawl-test-business' => 'Business', 'nosprawl-test-starter' => 'Starter')) }}
			<legend>Pick a Plan</legend>
			<div class="pricing uk-grid uk-grid-preserve">
				<div class="uk-width-1-3">
					<div class="plan">
						<h3>Starter</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>1</strong> User</li>
							<li><strong>1</strong> Node</li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li><strike>Patch Risk Ranking</strike></li>
							<li><strike>Real-time Notifications</strike></li>
							<li><strike>Real-time Risk Alerts</strike></li>
							<li><strike>Managed Patch Zones</strike></li>
							<li><strike>Target Asset Patching</strike></li>
							<li><strike>Patch Rollback</strike></li>
							<li><button id="select_starter" class="uk-button uk-button-large">Free</button></li>
						</ul>
					</div>
				</div>
				<div class="uk-width-1-3">
					<div class="plan feature">
						<h3>Business</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>5</strong> Users</li>
							<li><strong>30</strong> Nodes <span class="muted">($1 per additional node)</span></li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li>Patch Risk Ranking</li>
							<li>Real-time Notifications</strike></li>
							<li>Real-time Risk Alerts</li>
							<li>Managed Patched Zones</li>
							<li>Target Asset Patching</li>
							<li>Patch Rollback</li>
							<li><button id="select_business" class="uk-button-primary uk-button uk-button-large">$100/month</button></li>
						</ul>
					</div>
				</div>
				<div class="uk-width-1-3">
					<div class="plan">
						<h3>Enterprise</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>Unlimited</strong> Users</li>
							<li><strong>Unlimited</strong> Nodes</li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li>Patch Risk Ranking</li>
							<li>Real-time Notifications</strike></li>
							<li>Real-time Risk Alerts</li>
							<li>Managed Patched Zones</li>
							<li>Target Asset Patching</li>
							<li>Patch Rollback</li>
							<li><button id="select_enterprise" class="uk-button uk-button-large">Contact Us</button></li>
						</ul>
					</div>
				</div>
			</div>
		</fieldset>
		<br />
		<fieldset class="billing">
			<legend>Billing Information</legend>
			<div class="uk-grid uk-grid-preserve">
			<div class="uk-width-1-3 card-wrapper">
				<div class="uk-form-row">
					<label class="uk-form-label">Card Number</label>
			    <input type="text" name="billing_cc_number" placeholder="XXXX XXXX XXXX XXXX" data-stripe="number">
				</div>
				<div class="uk-form-row">
					<label class="uk-form-label">Name on Card</label>
			    <input type="text" name="billing_cc_name" placeholder="Thurman Thomas">
				</div>
				<div class="uk-form-row uk-grid uk-grid-preserve">
					<div class="uk-width-1-3">
						<label class="uk-form-label">Exp. Month</label>
				    <input type="text" id="billing_cc_expiry_month" name="billing_cc_expiry_month" placeholder="MM" style="width: 100px;" data-stripe="exp-month">
					</div>
					<div class="uk-width-1-3">
						<label class="uk-form-label">Exp. Year</label>
				    <input type="text" id="billing_cc_expiry_year" name="billing_cc_expiry_year" placeholder="YYYY" style="width: 100px;" data-stripe="exp-year">
					</div>
				</div>
				<div class="uk-form-row">
					<label class="uk-form-label">CVC</label>
			    <input type="text" name="billing_cc_cvc" style="width: 100px;" data-stripe="cvc">
				</div>
			</div>
			
			<div id="card_area_preview" class="uk-width-1-3">
			<!-- This is where the card will go. Don't delete this div. -->
			</div>
			
			<div class="uk-width-1-3">
				<div class="uk-form-row">
				<label class="uk-form-label">&nbsp;</label>
				Due Today: <strong id="total_due_today">$100</strong>
				</div>
				<div class="uk-form-row">
				Next Billing Date: <strong>1/1/2016</strong>
				</div>
				<div class="uk-form-row">
					<input style="display: inline; width: auto; position: relative; top: -1px;" type="checkbox" name="agree_to_terms">&nbsp;
					<label style="display: inline; width: auto;" class="uk-form-label">I agree to the <a href="#">licensing agreement</a>.</label>
				</div>
				<div class="uk-form-row">
					<input style="display: inline; width: auto; position: relative; top: -1px;" type="checkbox" name="subscribe_to_newsletter">&nbsp;
					<label style="display: inline; width: auto;" class="uk-form-label">I would like product &amp; company updates.</label>
				</div>
				<div class="uk-form-row">
					<br />
					{{ Form::submit('Register', ['class' => 'submit uk-button uk-button-success uk-button-large']) }}
				</div>
			</div>
			</div>
			<script type="text/javascript" src="/js/card.js"></script>
			<script type="text/javascript">
			$("#billing_cc_expiry_month, #billing_cc_expiry_year").change(function(ev) {
				$("#temp_expmonthyear").val($("#billing_cc_expiry_month").val() + "/" + $("#billing_cc_expiry_year").val());
			});
			
			$('form').card({
			    container: '#card_area_preview',
			    nameInput: 'input[name="billing_cc_name"]',
				numberInput: 'input[name="billing_cc_number"]',
				expiryInput: 'input[name="temp_expmonthyear"]',
				   cvcInput: 'input[name="billing_cc_cvc"]'
			});
			</script>
		</fieldset>
		
		<br /><br /><br />
		
	{{ Form::close() }}
</article>
@stop

@section('scripts')
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

	<script type="text/javascript" src="{{ url('js/stripe-integration.js') }}"></script>

	<script type="text/javascript">
	$("#select_starter").click(function(ev) {
		$(".plan.feature").removeClass('feature');
		$(".uk-button-primary").removeClass('uk-button-primary');
		$("select[name='plan']").val("nosprawl-test-starter");
		$(".plan").first().addClass('feature');
		$("#select_starter").addClass('uk-button-primary');
		$("#total_due_today").html("<strong>$0.00</strong>");
		return false;
	});
	
	$("#select_business").click(function(ev) {
		$(".plan.feature").removeClass('feature');
		$(".uk-button-primary").removeClass('uk-button-primary');
		$("select[name='plan']").val("nosprawl-test-business");
		plans = $(".plan");
		$(plans[1]).addClass('feature');
		$("#select_business").addClass('uk-button-primary');
		$("#total_due_today").html("<strong>$100.00</strong>");
		return false;
	});
	
	$("#select_enterprise").click(function(ev) {
		window.location = "http://nosprawl.com/contact.html"
		return false;
	});
	</script>
@stop