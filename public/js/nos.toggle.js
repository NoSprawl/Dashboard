window.tempDisableModals = false;

$('.switch label').click(function(ev) {
	if($(this).hasClass('switch-label-off') && !$(this).parent().hasClass('switch-yellow')) {ev.preventDefault(); ev.stopPropagation(); return false;}
	$(this).parent().toggleClass('switch-yellow');
	if(!window.tempDisableModals) {
		$("#" + $(this).attr('for')).trigger('click');
	}
	var t = $(this);

	setTimeout(function(t) {
		t.parent().parent().html('<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><div class="limbo">Activating</div>');
	}, 300);
	
	var $that = $(this);
	
	if($(this).data('integration') > -1) {
		$.post("/node/placeInLimbo/" + $(this).attr('for').substring(14,1000) + "/" + $(this).data('integration'), function(response) {
			// Delay for switch animation to complete.
			setTimeout(function(e) {
				$that.parent().addClass('fade');
				// Wait for fade to finish, then destroy the switch
				setTimeout(function(e) {
					$target = $that.parent().parent();
					$that.parent().remove();
					$target.append('<div class="spinner out"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><div class="limbo out">Activating</div>');
					// Short delay
					setTimeout(function(e) {
						$(".spinner, .limbo", $target).removeClass('out');
					});
					
				}, 501);
				
			}, 250);
		
			/*$('body').addClass('overlay');
			$('body').append('<div class="disabler"></div>');
	
	
			$('.disabler').append('<div class="nos-modal"><h1>Enable Monitoring</h1><p>To enable monitoring on this node, please run the following command on the target node:</p><code>curl http://agent.nosprawl.software/`curl \\ http://agent.nosprawl.software/latest` > nosprawl-installer.rb && \\ sudo ruby nosprawl-installer.rb</code><p>Once run, this node will appear in the &rdquo;managed nodes&rdquo; tab shortly.</p><p>To see the complete, and fully annotated source code of the installer script, just <a href="#">click here</a>. The agent is ultra light-weight.</p><a class="uk-button uk-button-large uk-button-primary modal-out" href="#">Back to List</a></div>');
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
			}, 1);*/
		
		
		});
	}
	
	
	
	ev.stopPropagation();
	ev.preventDefault();
});

$(window.document).on('.nos-modal', 'click', function(click_event) {
	return false;
});