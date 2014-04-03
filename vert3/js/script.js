vertTemplate = {
	timer : null,
	init : function(){
	
			
		
		/*
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
		*/
		
		$(".frunt-list, .verticalMenu").each(function(){
			
			$(this).perfectScrollbar({
			  wheelSpeed: 2,
			  wheelPropagation: 1,
			  minScrollbarLength: 20,
			  includePadding: false,
			  suppressScrollX : true
		  })
		  
		});

	
	},
	resizeImg : function(img){
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

