horizScript = {
	pastInfo : 0,
	infoRevealed : 0,
	buttonText : "Text",
	init : function(){
		var that=this;
		
		//pretty scrollbar on menu
		//$("#menu").css("position", "relative");
		$("#menu").perfectScrollbar({
		  wheelSpeed: 2,
		  wheelPropagation: false,
		  minScrollbarLength: 20,
		  includePadding: true
		})
		
		window.onresize = function(){
			$(".img_wpr > img").each(function(){
				$(this).closest(".img_wpr").width($(this).width());
			});
		}
		//hide text into button on scroll
		$(document).on("scroll", function(){
			now = $(this).scrollLeft();
			endProj_info = $(".project_info").outerWidth();
			if (now>=endProj_info){
				if (that.pastInfo==0){
					that.pastInfo = 1;
					$("<div id='projInfoIcon'>"+that.buttonText+"</div>").css({
						position : "fixed",
						top: 0,
						zIndex : 20,
						//left : $(".project_info").offset().left+"px",
						right: 0,
						display: "none"
					}).on("click", function(){
						if (that.infoRevealed==0){
							that.infoRevealed=1;
							$(this).animate({ 
								height : "100%",
								width:  $(".project_info").outerWidth()+"px",
								padding: $(".project_info").css("padding"),
								//left : ($(".project_info").offset().left)+"px",
								right : 0,
								"white-space" : "normal"
							},200).html($(".project_info").html()).addClass("project_info");
						}else{
							that.infoRevealed=0;
							$(this).animate({
								width : "inherit",
								height : "inherit",
								padding : "inherit",
								"white-space" : "nowrap"				
							},200).removeClass("project_info").html(that.buttonText);
						}
					
					}).appendTo("#content").show(200);				
				}
			}else{
				if (that.pastInfo==1){
					that.pastInfo = 0;
					$("#projInfoIcon").hide(200, function(){$(this).remove()});
				}
			}
			
		});
	}
}

$(document).ready(function(){
	horizScript.init();
});