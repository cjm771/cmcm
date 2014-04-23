horizScript = {
	timer : {},
	infoVisible : 0,
	scrolling : false,
	infoRevealed : 0,
	buttonText : "Text",
	prettyScroll : function(){
		//prep elemnts for pretty scroll
		$(".column").css({
			"height" : "100%"
		});
		
		$("#menu .col_content").css({
			"position" : "relative",
			"overflow" : "hidden",
			"overflow-y" : "hidden",
			"height" : "100%"
		});
		
		$(".frunt-list").css({
		"position" : "relative",
		"overflow" : "hidden",
		"overflow-y" : "hidden"
		});
			
			
		$(".frunt-menu").css({
			"overflow" : "hidden",
			"overflow-y" : "hidden",
			width : "100%"
		});
			  
		 $(".genericContainer").css({
			"position" : "relative",
			"overflow" : "hidden",
		});
	
		//initialize scroll bars
		
		$("#menu .col_content").each(function(){
			
			$(this).perfectScrollbar({
			  wheelSpeed: 2,
			  wheelPropagation: 0,
			  minScrollbarLength: 20,
			  includePadding: true,
			  suppressScrollX : true
		  })
		  	
		  
		});

		$(".frunt-list").perfectScrollbar({
		  wheelSpeed: 2,
		  wheelPropagation: 0,
		  minScrollbarLength: 20,
		  includePadding: true,
		  suppressScrollX : true
		 });
		  
	  	$(".frunt-menu").perfectScrollbar({
		  wheelSpeed: 2,
		  wheelPropagation: 0,
		  suppressScrollY : true
		 });
		 
			
		  $(".genericContainer").perfectScrollbar({
			  wheelSpeed: 2,
			  wheelPropagation: 0,
			  minScrollbarLength: 20,
			  includePadding: true,
			  suppressScrollX : true
		  })
	},
	buttonEvents : function(){
		var that=this;
		
		 $("#menu #info_toggle").click(function(){
			that.infoVisible=(!that.infoVisible);
			if (that.infoVisible){
				$(".project_info").show('slow');	
				$(this).html("&minus; Hide Text");
			}else{
				$(".project_info").hide('slow');
				$(this).html("&#43; Show Text");
			}
		});
		
		$("#mobileIcon").on("click", function(){
			if ($(".project_info").is(":visible"))
				$("#header #info_toggle").trigger("click");
			if ($("#menu").is(":visible")){
				$("#menu").slideUp();
				$("#mobileIcon").removeClass("hover");
			}else{
				$("#menu").slideDown();
				//console.log($(".frunt-menu")[0].scrollWidth);
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
		enquire.register("screen and (min-width : 320px) and (max-width : 640px)", {
		    match : function() {
			    $("#menu").hide();
		    },  
		    unmatch : function() {
		         $("#menu").show();
		    }
		});
		
		enquire.register("screen and (min-width : 320px) and (max-width : 800px)", {
		    match : function() {
			    $("#header").append($("#menu #info_toggle").clone(true)); 
		    },  
		    unmatch : function() {
		         $("#header #info_toggle").remove();
		    }
		});		
	}
}

$(document).ready(function(){
	horizScript.init();
});