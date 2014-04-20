horizScript = {
	infoVisible : 0,
	infoRevealed : 0,
	buttonText : "Text",
	init : function(){
		var that=this;
		
		
		
		//pretty scrollbar on menu
		$(" .frunt-menu, .column").css({
			"height" : "100%"
		});
		
		$("#menu .col_content").css({
			"position" : "relative",
			"overflow" : "hidden",
			"overflow-y" : "hidden",
			"height" : "100%"
		});
		
		
		
		$("#menu .col_content").each(function(){
			
			$(this).perfectScrollbar({
			  wheelSpeed: 2,
			  wheelPropagation: 1,
			  minScrollbarLength: 20,
			  includePadding: true,
			  suppressScrollX : true
		  })
		  	
		  
		});
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
		
		
		$(".frunt-list").niceScroll({
	    	touchbehavior: true,
	    	cursorcolor:"#c0c0c0",
	    	cursorborder : "0px",
	    	cursoropacitymax:0.7,
	    	cursorwidth:3,
	    	//background:"#ccc",
	    	autohidemode:true
    	});
    	
    	
			$(".frunt-menu").niceScroll({
	    	touchbehavior: true,
	    	cursorcolor:"#c0c0c0",
	    	cursorborder : "0px",
	    	cursoropacitymax:0.7,
	    	cursorwidth:3,
	    	//background:"#ccc",
	    	autohidemode:true,
	    	oneaxismousemode : false
    	});
		
		http://cmcm.chris-malcolm.com/media/thumbnail/(1)artworks-000056829441-we8g5u-t500x500.jpg//mobile icon setup
		$("#mobileIcon").on("click", function(){
			if ($(".project_info").is(":visible"))
				$("#header #info_toggle").trigger("click");
			if ($("#menu").is(":visible")){
				$("#menu").slideUp();
				$("#mobileIcon").removeClass("hover");
			}else{
				$("#menu").slideDown();
				console.log($(".frunt-menu")[0].scrollWidth);
				$("#mobileIcon").addClass("hover");
			}
		});
		
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
		
		
		window.onresize = function(){
			$(".img_wpr > img").each(function(){
				$(this).closest(".img_wpr").width($(this).width());
			});
		}
		
		
	}
}

$(document).ready(function(){
	horizScript.init();
});