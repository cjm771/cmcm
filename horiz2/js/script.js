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
			"overflow-y" : "scroll",
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