$(function(load_event) {
	$(".uk-tab li a").click(function(click_event) {
		if($(this).parent().hasClass('uk-disabled') || $(this).parent().hasClass('uk-active')) {
			return false;
		}
		
		$('li.uk-active', $(this).parent().parent()).removeClass('uk-active');
		$(this).parent().addClass('uk-active');
		$("table").hide();
		$("#" + $(this).attr('rel')).show();
	});
	
});