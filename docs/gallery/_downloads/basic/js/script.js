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
    	
	    $(".verticalMenu").niceScroll({
	    	touchbehavior: true,
	    	cursorcolor:"#c0c0c0",
	    	cursorborder : "0px",
	    	cursoropacitymax:0.7,
	    	cursorwidth:3,
	    	//background:"#ccc",
	    	autohidemode:true,
	    	oneaxismousemode : true
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
	init : function(){
	
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
	vertTemplate.init();
});

