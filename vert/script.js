vertTemplate = {
	timer : null,
	init : function(){
		var that = this;
		$(".img_wpr img").each(function(){
			$(this).on("load", function(){
				that.resizeImg(this);
			});
		});
		//set up event
		window.onresize = function(){
			clearTimeout(that.timer);
			that.timer = setTimeout(function(){
				$(".img_wpr img").each(function(){
				that.resizeImg(this);
				});	
			}, 50);
		};
	},
	resizeImg : function(img){
		//100% height, auto width is default
			wpr = $(img).closest(".img_wpr");
			wpr.height($(window).height());
			wpr.width($(window).width()-wpr.position().left);
		if ($(img).width()<($(img).closest(".img_wpr").width())){
			//flip css
			$(img).stop();
			$(img).clearQueue();
			$(img).css({
				width: $(img).closest(".img_wpr").css("width"),
				height : "auto"
			},50);
		}else{
			//restore orig css
			$(img).stop();
			$(img).clearQueue();
			$(img).css({
				width: "auto",
				height : $(img).closest(".img_wpr").css("height")
			},50);
		}
	}
}

$(document).ready(function(){
	vertTemplate.init();
});