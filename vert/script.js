vertTemplate = {
	timer : null,
	init : function(){
		var that = this;
		$(".img_wpr img").imagesLoaded(function(elems){			
			$(elems).each(function(){
				that.resizeImg(elems);
	
			});
			$(window).trigger("resize");
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

		 //determine size..
    	wprWidth = wpr.width();
    	wprHeight = wpr.height();
    	console.log("wpr: "+wprWidth+" "+wprHeight);
	   ratioX = wprWidth / img.width;
	   ratioY = wprHeight /img.height;
	   //fill box = max, within box = min
	   ratio = Math.max(ratioX, ratioY);

	   newWidth = (img.width * ratio);
	   newHeight = (img.height * ratio);
	   
	   $(img).css({
    	   width : newWidth+"px",
    	   height : newHeight+"px"
	   })
	}
}

$.fn.imagesLoaded = function(callback){
  var elems = this.filter('img'),
      len   = elems.length,
      blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
      
  elems.bind('load.imgloaded',function(){
      if (--len <= 0 && this.src !== blank){ 
        elems.unbind('load.imgloaded');
        callback.call(elems,this); 
      }
  }).each(function(){
     // cached images don't fire load sometimes, so we reset src.
     if (this.complete || this.complete === undefined){
        var src = this.src;
        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
        // data uri bypasses webkit log warning (thx doug jones)
        this.src = blank;
        this.src = src;
     }  
  }); 
 
  return this;
};

$(document).ready(function(){
	vertTemplate.init();
});

