slideshowTmpl = {
	timer : null,
	prettyScroll : function(){
		
		//pretty scrollbar on info
		$(".frunt-list").css({
			"position" : "relative",
			"overflow-y" : "hidden",
			"height" : "100%",
			"z-index" : 50,
			"padding-bottom" : "50px"
		});
		
		$(".frunt-list").each(function(){
			$(this).perfectScrollbar({
			  wheelSpeed: 2,
			  wheelPropagation: 0,
			  minScrollbarLength: 20,
			  includePadding: true,
			  suppressScrollX : true
		  })
		});
	},
	buttonEvents : function(){
		
		//mobile icon setup
		$("#aboutLink").on("click", function(){
			if ($("#about").is(":visible")){
				$("#about").slideUp();
				//$(".news").fadeIn();
				$("#aboutLink").removeClass("hover");
			}else{
				$("#about").slideDown();
				//$(".news").fadeOut();
				$("#aboutLink").addClass("hover");
			}
		});
		//mobile icon setup
		$("#infoLink").on("click", function(){
			if ($(".project_info").is(":visible")){
				$(".project_info").animate({
					marginRight: (-1*$(".project_info").width())+"px"
				}, 400, function(){
					$(this).hide();
					$(window).trigger("resize");
				});
				//$(".news").fadeIn();
				$("#infoLink").removeClass("hover");
			}else{
				$(".project_info").css({
					marginRight: (-1*$(".project_info").width())+"px"
				}).show().animate({
					marginRight: "0px"
				}, 400, function(){
					$(window).trigger("resize");
				});
				
				
				//$(".news").fadeOut();
				$("#infoLink").addClass("hover");
			}
		});
	},
	splashRollover : function(){
		$(".thumb_wpr").on("mouseover", function(){
			$(this).find(".title_wpr").stop().clearQueue().slideDown(100);
		});
		$(".thumb_wpr").on("mouseout", function(){
			$(this).find(".title_wpr").stop().clearQueue().slideUp(100);
		});
	},
	splashScale : function(){
		$(window).on("resize", function(){
			$(".thumb_wpr").height($(".thumb_wpr").width());
		});
		$(".thumb_wpr").height($(".thumb_wpr").width());
	},
	projectPage : function(){
		//make slideshow responsive with a 9 x 8 ration
		$(".frunt-slider").attr({
			"data-bias" : "width",
			"data-ratio" : "[9,6]"	
		});
		$(".frunt-slider").addClass("frunt-responsive");
	},
	init : function(){
		var that = this;
		//initialize scrollbars
		this.prettyScroll();
		//initialize click events;
		this.buttonEvents();
		

		
		//Media query events
		enquire.register("screen and (min-width : 320px) and (max-width : 800px)", {
		    match : function() {
			    $("#about").hide();
		    },  
		    unmatch : function() {
		        $("#about").hide();
		    }
		});

	}
}


$(document).ready(function(){
	slideshowTmpl.init();
});

