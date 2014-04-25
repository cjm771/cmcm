vertTemplate = {
	timer : null,
	prettyScroll : function(){
		$(".frunt-list").niceScroll({
	    	touchbehavior: true,
	    	cursorcolor:"#c0c0c0",
	    	cursorborder : "0px",
	    	cursoropacitymax:0.7,
	    	cursorwidth:3,
	    	//background:"#ccc",
	    	autohidemode:true
    	});
	    
	    $(".verticalMenu").css({
			"overflow" : "hidden",
			"overflow-y" : "hidden",
			"height": "100%",
			"position" : "relative"
		});
	    
	   $(".verticalMenu").perfectScrollbar({
		  wheelSpeed: 2,
		  wheelPropagation: 0,
		  suppressScrollX : true
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
	fadeInImages : function(){
	
		$("#container .frunt-preview-wpr img").closest(".frunt-preview-wpr").hide();
		$("#container .frunt-preview-wpr img").imagesLoaded(function(img){
			$(this).closest(".frunt-preview-wpr").fadeIn(400);
		});
	},
	init : function(){
		//initialize scrollbars
		this.prettyScroll();
		//initialize click events;
		this.buttonEvents();
		//fade in those images on load boiiiii
		this.fadeInImages();
		
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
	vertTemplate.init();

});


