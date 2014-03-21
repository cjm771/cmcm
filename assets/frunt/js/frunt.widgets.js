 var cmcm = (cmcm!=undefined) ? cmcm : {}; 
  
 $.extend(cmcm, {
	fruntWidget : {
		init : function(){
			this.menuWidget();
			this.previewWidget();
		},
		//embeds stuff
		previewWidget : function(){
			var that = this;
			$(".frunt-widget.frunt-widget-preview").each(function(){
				mediaUrl = $(this).attr("href");
				mediaThumb = $(this).attr("data-thumb");
				mediaType = $(this).attr("data-type");
				propBias = $(this).attr("data-proportion-bias");
				parent = $(this).parent();
				//assume both dimensions provided
				if (!propBias){
					$(this).attr("data-width", parent.width())
					$(this).attr("data-height", parent.height());
				//bias the width
				}else if (propBias=="width"){
					$(this).attr("data-width", parent.width());
				//bias the height
				}else if (propBias=="height"){
					$(this).attr("data-height", parent.height());
				}
				$(this).replaceWith(that.mediaTypes[mediaType].preview({
					src : mediaUrl,
					thumb : mediaThumb,
					type : mediaType,
					opts : $(this).data() //extra options
				}))		
			});
			
			window.addEventListener("resize", function(){
					//sound growth
					console.log("resize!");
					$(".frunt-preview-sound.responsive, .frunt-preview-video.responsive").each(function(){
						ratio = $.parseJSON($(this).attr("data-ratio"));
						propBias = $(this).attr("data-bias");
					
					//assume both dimensions provided
					if (!propBias){
						$(this).attr("data-width", parent.width())
						$(this).attr("data-height", parent.height());
					//bias the width
					}else if (propBias=="width"){
						$(this).attr("data-width", parent.width());
						$(this).attr("data-height", 0);
					//bias the height
					}else if (propBias=="height"){
						$(this).attr("data-height", parent.height());
						$(this).attr("data-width", 0);
					}
					newSize  = cmcm.fruntWidget.getResizeImageDimensions($(this).attr("data-width"), $(this).attr("data-height"), ratio[0], ratio[1], "fill");
					$(this).prop("width", newSize.width);
					$(this).prop("height", newSize.height);
					});
				})
			
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
						
						header = $(this);
						group = $(this).closest(".group_list");
						menu  = $(this).closest(".verticalMenu");
						menu.find(".group_header").removeClass("selected");
						
						//no multiple
						if (menu.hasClass("noMulti")){
								//turn off all things..
								group.parent().find(".group_list").not(group).each(function(){
									
									//groups not 
									$(this).removeClass("open");
									$(this).addClass("closed");
									$(this).find(".group_list > .group_header").hide("slow");
									$(this).find("a").hide("slow");
							});
						}
						
						if (group.hasClass("closed")){
							//SHOW FAN
							group.find("> .group_list > .group_header").show("slow"); 
							console.log
							group.find((menu.hasClass("noMulti")) ? "> a" : "> a").fadeIn().css("display", "block");
							group.removeClass("closed");
							group.addClass("open");
							header.addClass("selected");
							//group.show();
						}else{
							//HIDE FAN
							group.find("> .group_list > .group_header").hide("slow");
							group.find((menu.hasClass("noMulti")) ? "> a" : "> a").hide("slow");
							group.attr("data-toggle", 0);
							group.removeClass("open");
							header.removeClass("selected");
							group.addClass("closed");
						}
					});
					
				});
				//set current page
				$(".verticalMenu.collapsed").find(".active").closest(".group_list").find(".group_header").addClass("selected");
				$(".verticalMenu.collapsed").find(".active").parentsUntil(".group_index_0", ".group_list").each(function(){
						
						$(this).find("> .group_list >.group_header").show();
						$(this).removeClass("closed");
						$(this).addClass("open");
						$(this).find("> a").css("display", "block");
					//	console.log($(this).attr("class")+"\n");
				});
				
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
		}, //<--end menu widget
		 //<--------- UTILS -------------->//
		getResizeImageDimensions : function(pw,ph, w, h, fillOrWithin){
			console.log(pw+" "+ph);
			//determine size..
	    	wprWidth = (pw!=undefined) ? pw : 0;
	    	wprHeight = (ph!=undefined) ? ph : 0;
	    	if (wprWidth == 0 || wprHeight==0){
		    	if (wprWidth==0){
			    	return {
			    		height: wprHeight,
			    		width : wprHeight*(w/h)
			    	};
		    	}else{
			    		return {
			    		height: wprWidth,
			    		width : wprWidth*(h/w)
			    	};
		    	}
	    	}
		   ratioX = wprWidth / w;
		   ratioY = wprHeight / h;
		   //fill box = max, within box = min
		   if (fillOrWithin=="fill")
		   	ratio = Math.max(ratioX, ratioY);
		   else{
			ratio = Math.min(ratioX, ratioY);  
		   }
	
		   newWidth = (w * ratio);
		   newHeight = (h * ratio);
		   
		   return {
			   width: newWidth,
			   height : newHeight
		   }
		},
		trim : function(str){
			str = (typeof str == "string") ? str.trim() : str;
			return str;
		},
	 
		 //<--------- CMCM IMPORT -------------->//
		 
		  mediaTypes : {
				image : {
					preview : function(mediaObj){
						contents = $("<div class='media_crop' style='cursor:pointer'></div>");
				     	if (mediaObj.thumb && mediaObj.thumb!="false"){
					     contents.append("<img>").find('img').attr("src", mediaObj.thumb);  	
					    }else if (mediaObj.src){
						   contents.append("<img>").find('img').attr("src", mediaObj.src);  	 
					    }else
					    	contents.addClass('noImage').html("No Image");
					    
					    
					    if (mediaObj.thumb || mediaObj.src){  
						  
					    }
					    return contents;
					},
					remove : function(){}
				},
				video : {
					preview : function(mediaObj){
						//check url
						ret = "";
						$.each(cmcm.fruntWidget.externalMediaTypes, function(type, mediaType){
							if (mediaObj.src.match(mediaType.regex)){	
								ret =  mediaType.preview(mediaObj);
							}
						});
						
						if (!ret) 
							return $("<div class='noImage'>"+mediaObj.type+"</div>");
						else
							return ret;
					}
			
				},
				sound : {
					preview : function(mediaObj){
						
						//check url
						ret = "";
						$.each(cmcm.fruntWidget.externalMediaTypes, function(type, mediaType){			
							if (mediaObj.src.match(mediaType.regex)){
								ret =  mediaType.preview(mediaObj);
							}
						});
						
						if (!ret) 
							return $("<div class='noImage'>"+mediaObj.type+"</div>");
						else
							return ret;
					}
				}
			},
		  externalMediaTypes : {
		     	"youtube" : {
		     		ratio : [9,6],
		     		type : "video",
			     	regex :  /\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i, //matches[1] will be id,
			     	embed : function(mediaObj){
			     		v = cmcm.fruntWidget.trim(mediaObj.src.match(this.regex)[1]);
			     		newSize  = cmcm.fruntWidget.getResizeImageDimensions(mediaObj.opts.width, mediaObj.opts.height, this.ratio[0], this.ratio[1], "fill");
				     	console.log(newSize);	
				     	console.log(mediaObj.opts);
				     	emb = $('<iframe class="frunt-preview-video responsive" width="'+newSize.width+'" height="'+newSize.height+'" style="display: block" src="http://www.youtube.com/embed/'+v+'?showinfo=0"  data-ratio="'+JSON.stringify(this.ratio)+'" data-bias="'+mediaObj.opts.proportionBias+'" frameborder="0" allowfullscreen></iframe>');
				     	return emb;
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				       	iframe = this.embed(mediaObj);
				     	//contents.append(iframe);
				     	return iframe;
			     	}
		     	},
		     	"vimeo" : {
		     		ratio : [9,6],
		     		type : "video",
			     	regex : /\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i, //matches[1] will be id
			     	embed : function(mediaObj){
			     		v = cmcm.fruntWidget.trim(mediaObj.src.match(this.regex)[1]);
			     		newSize  = cmcm.fruntWidget.getResizeImageDimensions(mediaObj.opts.width, mediaObj.opts.height, this.ratio[0], this.ratio[1], "fill");
				     	console.log(newSize);	
				     	console.log(mediaObj.opts);
				     	emb = $('<iframe  class="frunt-preview-video responsive" src="//player.vimeo.com/video/'+v+'?portrait=0" width="'+newSize.width+'" height="'+newSize.height+'"  data-ratio="'+JSON.stringify(this.ratio)+'" data-bias="'+mediaObj.opts.proportionBias+'" frameborder="0" style="display: block" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
				     	return emb;
				     	
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				       	iframe = this.embed(mediaObj);
				     	//contents.append(iframe);
				     	return iframe;
			     	}
		     	},
		     	"soundcloud" : {
		     		ratio : [1,1],
		     		type : "sound",
			     	regex : /\/\/(?:www\.)?(?:api.soundcloud.com|soundcloud.com|snd.sc)\/(.*)$/i,
			     	embed : function(mediaObj){
			     			var that = this;
			     		//options defaults
			     		mediaObj.opts.visual = (mediaObj.opts.visual!=undefined) ? mediaObj.opts.visual : "true";
			     		
			     		newSize  = cmcm.fruntWidget.getResizeImageDimensions(mediaObj.opts.width, mediaObj.opts.height, this.ratio[0], this.ratio[1], "fill");
				     	console.log(newSize);	
				     	console.log(mediaObj.opts);
				     	emb = $('<iframe class="frunt-preview-sound responsive" width="'+newSize.width+'" height="'+newSize.height+'" data-ratio="'+JSON.stringify(this.ratio)+'" data-bias="'+mediaObj.opts.proportionBias+'" style="display: block" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='+encodeURI(mediaObj.src)+'&amp;auto_play=false&amp;hide_related=true&amp;visual='+mediaObj.opts.visual+'"></iframe>');
				     	
				     
				     	
				     	
				     	return emb;
			     	},
			     	preview : function(mediaObj){
			     		 //contents = $("");
				     	iframe = this.embed(mediaObj);
				     	//contents.append(iframe);
				     	return iframe;
			     	}
		     	}
	     	}
	     
	     //<------END CMCM IMPORT ------>//
	} //<--end frunt widget
 });

$(document).ready(function(){
	cmcm.fruntWidget.init();
});$