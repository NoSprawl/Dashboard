$('.switch label').click(function(ev) {
	if($(this).hasClass('switch-label-off') && !$(this).parent().hasClass('switch-yellow')) {ev.preventDefault(); ev.stopPropagation(); return false;}
	$(this).parent().toggleClass('switch-yellow');
	$("#" + $(this).attr('for')).trigger('click');
	$('body').addClass('overlay');
	$('body').append('<div class="disabler"></div>');
	$('.disabler').append('<div class="nos-modal">Hello</div>');
	setTimeout(function(ev) {
		$(".nos-modal").addClass('final');
	}, 1000);
	
	ev.stopPropagation();
	ev.preventDefault();
});
