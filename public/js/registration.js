jQuery(document).ready(function () { 

	var nsRegistration = {

		init: function() {

			this.stripeStuff();
			this.planSelection();

		},

		//@TODO consider breaking these methods up

		stripeStuff: function() {
			
			// This identifies your website in the createToken call below
			  //Stripe.setPublishableKey('{{ Config::get("stripe.stripe.public") }}');
			  Stripe.setPublishableKey('pk_test_z4u0woYIurJ5INFmYYW2NXNr');
				$(function() {
				  $('form').submit(function(event) {
						$("input").removeClass('uk-form-danger')
						$("span.error").remove();
				    var $form = $(this);
				    $form.find('input.submit').prop('disabled', true);
				    Stripe.card.createToken($form, function(status, response) {
				    	$('form').append('<input type="hidden" name="stripe_token" value="' + response['id'] + '">');
							if("error" in response) {
								$form.find('input.submit').prop('disabled', false);
								if(response.error.code == 'invalid_number') {
									$("input[name='billing_cc_number']").addClass('uk-form-danger')
									$("input[name='billing_cc_number']").before('<span class="error">' + response.error.message + '</span>')
								}
								
								if(response.error.code == 'invalid_expiry_year') {
									$("input[name='billing_cc_expiry_year']").addClass('uk-form-danger')
									$("input[name='billing_cc_expiry_year']").before('<span class="error">' + response.error.message + '</span>')
								}
								
								if(response.error.code == 'invalid_expiry_month') {
									$("input[name='billing_cc_expiry_month']").addClass('uk-form-danger')
									$("input[name='billing_cc_expiry_month']").before('<span class="error">' + response.error.message + '</span>')
								}
								
								if(response.error.code == 'invalid_cvc') {
									$("input[name='billing_cc_cvc']").addClass('uk-form-danger')
									$("input[name='billing_cc_cvc']").before('<span class="error" style="display: block;">' + response.error.message + '</span>')
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

		planSelection : function() {

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
		}	

	} // end nsStripe

	nsRegistration.init();

});
