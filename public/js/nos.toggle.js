window.tempDisableModals = false;

$('.switch label').click(function(ev) {
	if($(this).hasClass('switch-label-off') && !$(this).parent().hasClass('switch-yellow')) {ev.preventDefault(); ev.stopPropagation(); return false;}
	$(this).parent().toggleClass('switch-yellow');
	if(!window.tempDisableModals) {
		$("#" + $(this).attr('for')).trigger('click');
	}
	
	$('body').addClass('overlay');
	$('body').append('<div class="disabler"></div>');
	$('.disabler').append('<div class="nos-modal"><h1>Enable Monitoring</h1><p>To enable monitoring on this node, please run the following command on the target node:</p><code>sh | wget https://res.nosprawl.com/linux-agent-cronjob.sh</code><p>Once run, this node will appear in the &rdquo;managed nodes&rdquo; tab shortly.</p><p>To see the complete, and fully annotated source code of the installer script, just <a href="#">click here</a>. The agent is ultra light-weight.</p><a class="uk-button uk-button-large uk-button-primary modal-out" href="#">Back to List</a></div>');
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

$('#new_alert').click(function(ev) {
	$('body').addClass('overlay');
	$('body').append('<div class="disabler"></div>');
	$('.disabler').append('<div class="nos-modal"><h1>New Alert</h1><p></p><a class="uk-button uk-button-large uk-button-primary modal-out" href="#">Back to List</a><form><label></label></form></div>');
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

$(window.document).on('.nos-modal', 'click', function(click_event) {
	return false;
});