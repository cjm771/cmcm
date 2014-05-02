slideshowTmpl = {
	timer : null,
	prettyScroll : function(){
		
		//pretty scrollbar on menu
		$(".frunt-menu").css({
			"position" : "relative",
			"overflow" : "hidden",
			"height" : "100%"
		});
		
		$(".frunt-menu").each(function(){
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
		$("#mobileIcon").on("click", function(){
			if ($("#menu .verticalMenu").is(":visible")){
				$("#menu .verticalMenu").slideUp();
				$("#mobileIcon").removeClass("hover");
			}else{
				$("#menu .verticalMenu").slideDown();
				$("#mobileIcon").addClass("hover");
			}
		});
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
			    $("#menu .verticalMenu").hide();
		    },  
		    unmatch : function() {
		         $("#menu .verticalMenu").show();
		    }
		});

	}
}


$(document).ready(function(){
	slideshowTmpl.init();
});

