jQuery(document).ready(function () { 

	var nsStripe = {

		init: function() {

			this.stripeStuff();

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
		}	

	} // end nsStripe

	nsStripe.init();

});
