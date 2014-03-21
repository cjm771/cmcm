 var cmcm = (cmcm!=undefined) ? cmcm : {}; 
  
 $.extend(cmcm, {
	fruntWidget : {
		init : function(){
			this.menuWidget();
		},
		menuWidget : function(){
			//vertical menu collapsed setup
		
			if ($(".verticalMenu.collapsed").length){
				//set group header click events
				$(".verticalMenu.collapsed").find(".group_header").each(function(){
					
					group = $(this).closest(".group_list");
					group.find(".group_list .group_header").hide();
					group.addClass("closed");
					$(this).on("click", function(){
						$(this).addClass("selected");
						header = $(this);
						group = $(this).closest(".group_list");
						menu  = $(this).closest(".verticalMenu");
						//no multiple
						if (menu.hasClass("noMulti")){
								//turn off all things..
								menu.find(".group_header").not(header).removeClass("selected");
								group.parent().find(".group_list").not(group).each(function(){
									
									//groups not 
									$(this).removeClass("open");
									$(this).addClass("closed");
									$(this).find(".group_list > .group_header").css("display", "none");
									$(this).find("> a").css("display", "none");
							});
							//console.log("blam");
						}
						
						if (group.hasClass("closed")){
							//SHOW FAN
							group.find("> .group_list > .group_header").show();
							group.find("> a").css("display", "block");
							group.removeClass("closed");
							group.addClass("open");
							group.show();
						}else{
							//HIDE FAN
							group.find("> .group_list > .group_header").hide();
							group.find("> a").css("display", "none");
							group.attr("data-toggle", 0);
							group.removeClass("open");
							group.addClass("closed");
						}
					});
					
				});
				//set current page
				
				$(".verticalMenu.collapsed").find(".active").parentsUntil(".group_index_0", ".group_list").each(function(){
						$(this).find("> .group_list >.group_header").show();
						//$(this).removeClass("closed");
						//$(this).addClass("open");
						$(this).find("> a").show();
				});
				
				$(".verticalMenu.collapsed").find(".active").closest(".group_list").find(".group_header").trigger("click");
				//pick default open fan..
				if (!$(".verticalMenu.collapsed").find(".active").length){
					currentFan = $(".verticalMenu.collapsed").attr("data-current");
					if (currentFan){
						//if the current fan is a string then attempt to find it
						if ($(".verticalMenu.collapsed").find(".group_header[data-name='"+currentFan+"']").length){
							$(".verticalMenu.collapsed").find(".group_header[data-name='"+currentFan+"']").first().trigger("click");
						}else{
							//else just show first
							$(".verticalMenu.collapsed").find(".group_header").first().trigger("click");
						}
					}
				}
			}
		}
	}
 });

$(document).ready(function(){
	cmcm.fruntWidget.init();
});$