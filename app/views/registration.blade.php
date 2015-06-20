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
		{{ Form::hidden('temp_expmonthyear', null, ['id' => 'temp_expmonthyear']) }}
		<fieldset>
			<div class="uk-grid">
				<div class="uk-width-large-1-3">
					<div class="uk-row">
						<p>All of our plans start with a 30 day free trial and your personal account manager is always one phone call away.</p>
						<p>This is an instant-access registration process. Once you complete this form, you&rsquo;ll be logged into your NoSprawl dashboard immediately.</p>
					</div>
					<div class="uk-panel">
						<div class="uk-row">
							<blockquote>
							    <p>After the shellshock vulnerability, we said &ldquo;Never again.&rdquo; and meant it. Word.</p>
							    <small>Ben Walker - CTO at Mobiquity</small>
							</blockquote>
						</div>
						<div class="uk-row">
							<blockquote>
							    <p>NoSprawl is an indispensible tool for any IT auditor who takes their job seriously.</p>
							    <small>Tim Smith - IRA at PNC Bank</small>
							</blockquote>
						</div>
					</div>
				</div>
				<div class="uk-width-large-2-3">
					<legend>Account Information</legend>
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
			</div><!-- /uk-grid uk-grid-preserve -->
		</fieldset>
		<br />{{-- @TODO style this so a br isn't necessary --}}
		<fieldset class="uk-row">
			<?= Form::select('plan', ['nosprawl-' . (App::isLocal() ? 'test' : 'live') . '-business' => 'Business', 'nosprawl-' . (App::isLocal() ? 'test' : 'live') . '-starter' => 'Starter']) ?>
			<legend style="padding-bottom: 1px !important;">Choose a Plan</legend>
			<div class="pricing uk-grid uk-grid-preserve">
				<div class="uk-width-medium-1-3">
					<div class="plan uk-row">
						<h3>Starter</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>1</strong> User</li>
							<li><strong>1</strong> Managed Node</li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li>Asset Risk Ranking</li>
							<li>Real-time Notifications</li>
							<li>Real-time Risk Alerts</li>
							<li>Base Image Accord</li>
							<li>Detailed Reporting</li>
							<li><button id="select_starter" class="uk-button uk-button-large">Free</button></li>
						</ul>
					</div>
				</div>
				<div class="uk-width-medium-1-3">
					<div class="plan feature uk-row">
						<h3>Business</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>5</strong> Users</li>
							<li><strong>10</strong> Managed Nodes</li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li>Asset Risk Ranking</li>
							<li>Real-time Notifications</li>
							<li>Real-time Risk Alerts</li>
							<li>Base Image Accord</li>
							<li>Detailed Reporting</li>
							<li><button id="select_business" class="uk-button-primary uk-button uk-button-large">$375/month</button></li>
						</ul>
					</div>
				</div>
				<div class="uk-width-medium-1-3" style="margin-bottom: 0 !important;">
					<div class="plan uk-row">
						<h3>Enterprise</h3>
						<ul class="uk-list uk-list-line">
							<li><strong>Unlimited</strong> Users</li>
							<li><strong>Unlimited</strong> Managed Nodes</li>
							<li>Cloud Integration</li>
							<li>Base Image Patching</li>
							<li>Asset Risk Ranking</li>
							<li>Real-time Notifications</li>
							<li>Real-time Risk Alerts</li>
							<li>Base Image Accord</li>
							<li>Detailed Reporting</li>
							<li><button id="select_enterprise" class="uk-button uk-button-large">Contact Us</button></li>
						</ul>
					</div>
				</div>
			</div>
		</fieldset>
		<span class="muted">($35 per additional node)</span>
		<fieldset class="uk-row billing">
			<legend style="padding-bottom: 20px !important;">Billing Information</legend>
			<div class="uk-grid uk-grid-preserve">
				<div class="uk-width-medium-1-3" id="billing_cc_info">
					<div class="uk-form-row">
						{{ Form::label('billing_cc_number', 'Card Number', ['class' => 'uk-form-label'] ) }}
						{{ Form::text('billing_cc_number', null, ['placeholder' => 'XXXX XXXX XXXX XXXX', 'data-stripe' => 'number'])}}
					</div>
					<div class="uk-form-row">
						{{ Form::label('billing_cc_name', 'Name on Card', ['class' => 'uk-form-label'] ) }}
						{{ Form::text('billing_cc_name', null, ['placeholder' => 'Thurman Thomas']) }}
					</div>
					<div class="uk-form-row">
						<div class="uk-grid uk-grid-preserve">
							<div class="uk-width-1-2">
								{{ Form::label('billing_cc_expiry_month', 'Exp. Month', ['class' => 'uk-form-label'] ) }}
								{{ Form::text('billing_cc_expiry_month', null, ['placeholder' => 'MM', 'data-stripe' => 'exp-month']) }}
							</div>
							<div class="uk-width-1-2">
								{{ Form::label('billing_cc_expiry_year', 'Exp. Year', ['class' => 'uk-form-label'] ) }}
								{{ Form::text('billing_cc_expiry_year', null, ['id' => 'billing_cc_expiry_year', 'placeholder' => 'YYYY', 'data-stripe' => 'exp-year']) }}
							</div>
						</div>
					</div>
					<div class="uk-form-row">
						<div class="uk-width-large-3-3">
							<label class="uk-form-label">CVC</label>
					    <input type="text" name="billing_cc_cvc" data-stripe="cvc">
						</div>
					</div>
				</div>
			
				<div id="card_area_preview" class="uk-width-medium-1-3">
				<!-- This is where the card will go. Don't delete this div. -->
				</div>
			
				<div class="uk-width-medium-1-3" id="confirmation_area">
					<div class="uk-form-row">
						<label class="uk-form-label">&nbsp;</label>
						Due Today: <strong id="total_due_today"><strike class="lght">$375.00</strike> $0.00</strong>
					</div>
					<div class="uk-form-row">
						Next Billing Date: <strong>1/1/2016</strong>
					</div>
					<div class="uk-form-row">
						<label for="terms_check" style="display: inline; width: auto;" class="uk-form-label">I agree to the <a href="#">licensing agreement</a>.</label>&nbsp;
						<input id="terms_check" style="display: inline; width: auto; position: relative; top: -1px;" type="checkbox" name="agree_to_terms">
					</div>
					<div class="uk-form-row">
						<label for="newsletter_check" style="display: inline; width: auto;" class="uk-form-label">I would like product &amp; company updates.</label>&nbsp;
						<input id="newsletter_check" style="display: inline; width: auto; position: relative; top: -1px;" type="checkbox" name="subscribe_to_newsletter">
					</div>
					<div class="uk-form-row nos-reg-submit-row">
						<br />
						{{ Form::submit('Create My Account', ['class' => 'nos-reg-submit submit uk-button uk-button-success uk-button-large']) }}
					</div>
				</div>
			</div>
			<script type="text/javascript" src="/js/card.js"></script>
		</fieldset>
		
		<br /><br /><br />{{-- @TODO style this so a br isn't necessary --}}
		
	{{ Form::close() }}
</article>
<script type="text/javascript">
$("input").focus(function(event) {
	if($(this).prev().hasClass("error")) {
		$(this).prev().addClass("out");
	}
	
});
</script>
@stop

@section('scripts')

	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script>
	$(function (ev) { 

		var nsRegistration = {

			init: function() {

				this.stripeStuff();
				this.planSelection();

			},

			//@TODO consider breaking these methods up

			stripeStuff: function() {
			
				// This identifies your website in the createToken call below
				
				  Stripe.setPublishableKey('{{ (App::isLocal() ? Config::get("stripe.development.public") : Config::get("stripe.production.public")) }}');
					$(function() {
					  $('form').submit(function(event) {
							$("input").removeClass('uk-form-danger')
							$("span.error").remove();
					    var $form = $(this);
					    $form.find('input.submit').prop('disabled', true);
					    Stripe.card.createToken($form, function(status, response) {
					    	$('form').append('<input type="hidden" name="stripe_token" value="' + response["id"] + '">');
								if("error" in response) {
									$form.find('input.submit').prop('disabled', false);
									if(response.error.code == 'invalid_number') {
										$("input[name='billing_cc_number']").addClass('uk-form-danger')
										$("input[name='billing_cc_number']").before('<span class="error">' + response.error.message + '</span>')
									}
								
									if(response.error.code == 'invalid_expiry_year') {
										$("input[name='billing_cc_expiry_year']").addClass('uk-form-danger')
										$("input[name='billing_cc_expiry_year']").before('<span class="error">Invalid Exp. Year</span>')
									}
								
									if(response.error.code == 'invalid_expiry_month') {
										$("input[name='billing_cc_expiry_month']").addClass('uk-form-danger')
										$("input[name='billing_cc_expiry_month']").before('<span class="error">' + response.error.message + '</span>')
									}
								
									if(response.error.code == 'invalid_cvc') {
										$("input[name='billing_cc_cvc']").addClass('uk-form-danger')
										$("input[name='billing_cc_cvc']").before('<span class="error" style="display: block;">' + response.error.message + '</span>')
									}
									
									if(response.error.code == 'incorrect_number') {
										$("input[name='billing_cc_number']").addClass('uk-form-danger')
										$("input[name='billing_cc_number']").before('<span class="error" style="display: block;">' + response.error.message + '</span>')
									}
								
								} else {
									$('fieldset.billing').remove(); // No need to send the billing info to the server
									$('form').unbind('submit');
									$('form').submit();
								}
							
					    });
						
							return false;
					  });
					});		
			},

			planSelection: function() {
				$("#select_starter").click(function(ev) {
					$(".plan.feature").removeClass('feature');
					$(".uk-button-primary").removeClass('uk-button-primary');
					$("select[name='plan']").val("nosprawl-<?php if(App::isLocal()) {echo 'test';} else {echo 'live';} ?>-starter");
					$(".plan").first().addClass('feature');
					$("#select_starter").addClass('uk-button-primary');
					$("#total_due_today").html("<strong id=\"total_due_today\">$0.00</strong>");
					return false;
				});
			
				$("#select_business").click(function(ev) {
					$(".plan.feature").removeClass('feature');
					$(".uk-button-primary").removeClass('uk-button-primary');
					$("select[name='plan']").val("nosprawl-<?php if(App::isLocal()) {echo 'test';} else {echo 'live';} ?>-business");
					plans = $(".plan");
					$(plans[1]).addClass('feature');
					$("#select_business").addClass('uk-button-primary');
					$("#total_due_today").html('<strong id="total_due_today"><strike class="lght">$375.00</strike> $0.00</strong>');
					return false;
				});
			
				$("#select_enterprise").click(function(ev) {
					window.location = "http://nosprawl.com/";
					return false;
				});
			}	

		} // end nsStripe

		nsRegistration.init();
	});
	
	$(function(ev) {
		window.recalcNoSReg = function(ev) {
			if(window.innerWidth <= 1220) {
				$("#billing_cc_info").removeClass("uk-width-medium-1-3").addClass("uk-width-medium-2-3");
				if(window.innerWidth <= 768) {
					$(".pricing .uk-width-medium-1-3").css("margin-bottom", "25px");
				}
				
			} else {
				$("#billing_cc_info").removeClass("uk-width-medium-2-3").addClass("uk-width-medium-1-3");
			}
			
		}
		
		window.recalcNoSReg(1);
		
		$(window).resize(window.recalcNoSReg);
		
	});
	
	</script>

@stop