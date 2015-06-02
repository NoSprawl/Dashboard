$(function(load_event) {
	$(".uk-tab li a").click(function(click_event) {
		if($(this).parent().hasClass('uk-disabled') || $(this).parent().hasClass('uk-active')) {
			return false;
		}
		
		$('li.uk-active', $(this).parent().parent()).removeClass('uk-active');
		$(this).parent().addClass('uk-active');
		$(".nos-hidable").hide();
		$("#" + $(this).attr('rel')).show();
		
		if($("#managed_nodes").is(":visible")) {
			$("#groups_panel").addClass("open");
		} else {
			$("#groups_panel").removeClass("open");
		}
		
	});

	$(document).on("click", "#groups_panel li.add-new .divved", function(ev) {
		var that = $(this);
		that.addClass("pop-out");
		$("body").addClass('overlay3');
		setTimeout(function(ev) {
			that.addClass("popped-out");
		}, 150);
		
		return false;
	});
	
	$(document).on("click", ".overlay3", function(ev) {
		$("body").removeClass('overlay3');
		$(".popped-out").removeClass('popped-out');
		setTimeout(function(ev) {
			$(".pop-out").removeClass('pop-out');
		}, 150);
	});
	
	$(document).on("click", "#groups_panel li.add-new .divved i.fa-check-circle", function(ev) {
		$(this).replaceWith('<i class="fa fa-spinner fa-spin"></i>');
		$("#groups_panel li.add-new .divved input").prop('readonly', true);
		$.post("/group/create", {name: $("#groups_panel li.add-new .divved input").val()}, function(response) {
			// Lil delay for effect
			setTimeout(function(ev) {
				$(".overlay3").trigger("click");
				// Another delay
				setTimeout(function(ev) {
					$('i.fa-spinner').replaceWith('<i class="fa fa-check-circle"></i>');
					$("#groups_panel li.add-new .divved input").val("");
					$("#groups_panel li.add-new .divved input").prop('readonly', false);
				}, 500);
				
			}, 250);
			
		});
		
	});
	
});

