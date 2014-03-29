 var cmcm = (cmcm!=undefined) ? cmcm : {}; 
  
 $.extend(cmcm, {
	fruntWidget : {
		resizeTimer : null,
		resizeTime : 100,
		init : function(){
			var that = this;
			//setup menuWidget
			this.menuWidget();
			//setup previewWidget
			this.previewWidget();
			//setup modalWidget
			this.modalWidget();
			//setup layoutWidget
			this.layoutWidget();
			//init responsive things.
			this.onResize();
			//EVENTS
			window.addEventListener("resize", function(){
				that.onResize();
			});
		},
		toggler : function(el, opts, sel){
			$.each(opts, function(k,v){
				el.removeClass(v);
			});
			el.addClass(opts[sel])
		},
		onResize : function(){
			//handle responsive elements
			clearTimeout(this.resizeTimer);
			this.resizeTimer = setTimeout(function(){
				$(".frunt-responsive, .frunt_responsive").each(function(){
					ratio = $.parseJSON($(this).attr("data-ratio"));
					//how to fit the object...is it filled (fill) or contained (within)..
					fit = $(this).attr("data-fit");
					if (fit==undefined || fit==false || fit==""){
						fit="fill";
						console.log($(this).attr('src')+" = "+fit);
					}
					propBias = $(this).attr("data-bias");
					syncParent =  $(this).attr("data-sync-parent");
					parent = $(this).parent();
	
					//prop bias lets us determine where to grab dimensions from..
					//otherwise..we will just make it match its parent
					if (!propBias){
						$(this).attr("data-width", parent.width())
						$(this).attr("data-height", parent.height());
					}else if(propBias=="width"){
						$(this).attr("data-width", $(this).width());
						$(this).attr("data-height", "0");
					}else if(propBias=="height"){
						$(this).attr("data-height", $(this).height());
						$(this).attr("data-width", "0");
					}else if (propBias=="parent-width"){
						$(this).attr("data-width", parent.width());
						$(this).attr("data-height", "0");
					//bias the parent height
					}else if (propBias=="parent-height"){
						$(this).attr("data-height", parent.height());
						$(this).attr("data-width", "0");
					}
					newSize  = cmcm.fruntWidget.getResizeImageDimensions($(this).attr("data-width"), $(this).attr("data-height"), ratio[0], ratio[1], fit);
					if (propBias!="width"){
						$(this).css("width", newSize.width+"px");
						$(this).attr("width", newSize.width);
					}
					if (propBias!="height"){
						$(this).css("height", newSize.height+"px");
						$(this).attr("height", newSize.height);
					}
					if (syncParent){
						if (propBias!='parent-height'){
							parent.css("height", newSize.height+"px");
							parent.attr("height", newSize.height);
						}
						if (propBias!='parent-width'){
							parent.css("width", newSize.width+"px");
							parent.attr("width", newSize.width);
						}
					}
					
				});
			}, this.resizeTime);
		
			//handle modal if exists
			if ($('.frunt-modal-content').length){
				//calculate top
				buffer = -20;
				minimum = 30;
				topCalc = ($(window).height()-$(".frunt-modal-content").height()+buffer)/2;
				topCalc = (topCalc < minimum ) ? minimum : topCalc;		
				$(".frunt-modal-content").stop();
				$(".frunt-modal-content").clearQueue();		
				$(".frunt-modal-content").animate({
					"margin-top" :  topCalc+"px"
				},200);
			}

		},
		//embeds stuff
		previewWidget : function(){
			var that = this;
			$(".frunt-widget.frunt-widget-preview").each(function(){
				mediaUrl = $(this).attr("href");
				mediaThumb = $(this).attr("data-thumb");
				mediaType = $(this).attr("data-type");
				title =  $(this).attr("title");
				propBias = $(this).attr("data-bias");
				propBias = (propBias==undefined) ? $(this).attr("data-proportion-bias") : propBias;
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
				$(this).replaceWith(that.preview({
					src : mediaUrl,
					thumb : mediaThumb,
					caption : title,
					type : mediaType,
					opts : $(this).data() //extra options
				}))		
			});
		},
		modalWidget : function(){
			var that = this;
			if ($("a.frunt-modal").length){
				$("a.frunt-modal").each(function(){
					$(this).on("click", function(e){
						
						e.preventDefault();
						src = $(this).attr("href");
						caption = $(this).attr("title");
						type = $(this).attr("data-type");
						console.log(src);
						description = "";
						switch (type){
							case "video":
							case "sound":
								description = that.mediaTypes[type].preview({
									type : type,
									src : src
								});
								break;
							default:
							case "image":
								description = "<img src='"+src+"'>";
								break;
						}
						that.modal({
							subject : caption,
							description : description
						});
					});
				});
			}
		},
		layoutWidget : function(){
			var that = this;
			if ($(".frunt-layout.frunt-layout-grid").length){
				$(".frunt-layout.frunt-layout-grid").each(function(){
					menu = $(this);
					
					forceCols = menu.attr("data-force-cols");
					if (forceCols){
						menu.css("width", "100%");
						menu.find(".thumb_group").css("width", "100%");
						samp = menu.find(".thumb_wpr").first();
						ratio = samp.height()/samp.width();
						container = samp.parent();
						//parentExtraPadding = (samp.parent().outerWidth(true)-samp.parent().width());
						extraPadding = (samp.outerWidth(true)-samp.width())*forceCols;
						width = (container.width()-extraPadding)/forceCols;
						//convert to percent
						width = (width/container.width())*100;
						height = (width*ratio);
						menu.find(".thumb_wpr").addClass("frunt-responsive");
						menu.find(".thumb_wpr").each(function(){
							//$(this).width(width);
							$(this).css({
								width : width+"%"
							});
							
							$(this).attr("data-bias", "width");
							$(this).attr("data-ratio", JSON.stringify([width,height]));	
						})
						
					}
				});
			}
			
			
		},
		menuWidget : function(){
			var that = this;
			if ($(".frunt-menu.frunt-menu-grid").length){
				$(".frunt-menu.frunt-menu-grid").each(function(){
					menu = $(this);
					
					forceCols = menu.attr("data-force-cols");
					if (forceCols){
						menu.css("width", "100%");
						menu.find(".thumb_group").css("width", "100%");
						samp = menu.find(".thumb_wpr").first();
						ratio = samp.height()/samp.width();
						container = samp.parent();
						//parentExtraPadding = (samp.parent().outerWidth(true)-samp.parent().width());
						extraPadding = (samp.outerWidth(true)-samp.width())*forceCols;
						width = (samp.parent().width()-extraPadding)/forceCols;
						//convert to percent
						width = (width/samp.parent().width())*100;
						height = (width*ratio);
						menu.find(".thumb_wpr").addClass("frunt-responsive");
						menu.find(".thumb_wpr").each(function(){
							//$(this).width(width);
							$(this).css({
								width : width+"%"
							});
							
							$(this).attr("data-bias", "width");
							$(this).attr("data-ratio", JSON.stringify([width,height]));	
						})
						
					}
				});
			}
			
			
			//horiz widget
			if ($(".frunt-menu.frunt-menu-horiz").length){
				$(".frunt-menu.frunt-menu-horiz").each(function(){
					menu = $(this);
					$(this).find(".link").each(function(){
						$(this).on("click", function(){
							if (!$(this).hasClass("disabled")){
								//$(this).closest(".col_content").scrollTop($(this).position().top);						
								//define els						
								column = $(this).closest(".column");
								
								//set link as highlighted
								opts = ['', 'selected'];
								that.toggler(column.find(".link"), opts, 0);
								that.toggler($(this), opts, 1);
								//set current for column
								column.attr("data-current", $(this).attr("data-val"));
								//hide all but this column and its previous
								menu.find(".column").not(column).not(column.prevAll('.column')).each(function(){
									opts = ['disabled', ''];
									//disable all links
									that.toggler($(this), opts, 0);
									opts = ['', 'selected'];
									that.toggler($(this).find(".link"), opts, 0);
									$(this).hide();
								
								});
								//show next column
								column.next('.column').each(function(){
									opts = ['disabled', ''];
									//disable all links
									that.toggler($(this).find(".link"), opts, 0);
									that.toggler($(this), opts, 1);
									//undisable ones
									//assemble reqs obj
									reqs = {};
									menu.find('.column:not(.disabled)').not(this).each(function(){
										reqs[$(this).attr("data-att")]=$(this).attr("data-current");
									});
									//for each link
										//if data-year has 2003, and data-type has architecture
										$(this).find(".link").each(function(){
											//check 
											valid = 1;
											link = this;
											$.each(reqs, function(att, val){
												
												arr = $(link).attr("data-"+att).split(",");
												if ($.inArray(val, arr)==-1)
													valid = 0;
											});
											if (valid){
													that.toggler($(link), opts, 1);
											}
										});
									//fade in
									$(this).css({'display' : 'inline-block'}).hide().fadeIn();
								});
																
							} //<--end if not disabled
						});
					});
					
					//pick default open fans..
					if ($(menu).find(".active").length){
						active = $(menu).find(".active");
						column = active.closest(".column");
						active.closest(".column").find(".col_content").scrollTop(active.position().top);
						//
						$(".column").not(column).each(function(){
							key = $(this).attr("data-att");
							activeField = active.attr("data-"+key);
							$(this).find(".link").each(function(){
								//link = this;
								if (activeField==$(this).attr("data-val")){
									$(this).trigger("click");
									    
									if ($(this).closest(".column").find(".col_content").length){
										el = $(this).closest(".column").find(".col_content");
										console.log(el[0].scrollHeight+" "+$(this).position().top);
										$(this).closest(".column").find(".col_content").scrollTop($(this).position().top);
									}
								}
								
							});
						});
					}

					
				}); //<--end  each horiz menu
				
			}
		
		
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
		   //fill box = max, within box = m
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
		preview : function(mediaObj){
			var that = this;
								
			mediaObj.opts.mode = (mediaObj.opts.mode!=undefined) ? 	mediaObj.opts.mode : "direct_embed";
			mediaType = this.mediaTypes[mediaObj.type];
			
			iconTypes = {
				image : "glyphicon glyphicon-search",
				sound : "glyphicon glyphicon-volume-up",
				video : "glyphicon glyphicon-facetime-video"	
			};
			
			wpr = $("<div class='frunt-preview-wpr'></div");
			switch (mediaObj.opts.mode){
				//MODAL
				case "none":
					mediaObj.opts.noIcons = true;
				case "modal-noIcon":
					mediaObj.opts.noIcons = true;
				case "modal":
				//THUMB
				case "thumb":
					icon = $("<span class='"+iconTypes[mediaObj.type]+" "+mediaObj.type+"_icon frunt-absCenter frunt-32 frunt-iconBox frunt-clickable' title='"+mediaObj.type+"'></span>");
					if (mediaObj.thumb){
						src = (mediaObj.type=='image' && mediaObj.opts.useThumb!=undefined && mediaObj.opts.useThumb==false) ?  mediaObj.src : mediaObj.thumb;
						thumb = $("<img class='frunt-preview-thumb' src='"+src+"'>");
						
						if (mediaObj.opts.thumb)
							thumb.attr("data-thumb", mediaObj.opts.thumb);
						if (mediaObj.opts.src)
							thumb.attr("data-src", mediaObj.opts.src);
						if (mediaObj.opts.mediaKey)
							thumb.attr("data-media-key", mediaObj.opts.mediaKey);
						if (mediaObj.caption)
							thumb.attr("title", mediaObj.caption);
						if (mediaObj.opts.fit)
							thumb.attr("data-fit", mediaObj.opts.fit);
						if (mediaObj.opts.bias)
						thumb.attr("data-bias", mediaObj.opts.bias);
						
						
						//responsive?
						if (mediaObj.opts.responsive){
							thumb.imagesLoaded(function(img){
								//console.log("loaded");
								 ratio = [img.width, img.height];
								 $(img).attr("data-ratio", JSON.stringify(ratio));
								 $(img).addClass("frunt-responsive");
								 //$(img).attr("data-=", true);
							});
						}
					}else{
						thumb = $("<div class='frunt-preview-thumb noImage'></div>");
						if (mediaObj.opts.responsive){
							thumb.attr("data-ratio", "[1,1]");
							thumb.attr("data-bias", mediaObj.opts.bias);
							thumb.addClass("frunt-responsive");
						}
					}
					if (mediaObj.opts.mode=="thumb"){
						icon.on("click", function(){
							thumb = $(this).closest(".frunt-preview-wpr").find('.frunt-preview-thumb');
	
							ret =  that.mediaTypes[mediaObj.type].preview(mediaObj);
							ret.hide();
							//responsive?
							if (mediaObj.opts.responsive){
								if (mediaObj.opts.realFit){
									ret.attr("data-fit", mediaObj.opts.realFit);
								}else if (mediaObj.opts.fit){
									ret.attr("data-fit", mediaObj.opts.fit);
								}
					     		ret.attr("data-bias", mediaObj.opts.bias);
					     		ret.addClass("frunt-responsive");
					     		if (mediaObj.opts.syncParent){
					     			ret.attr("data-sync-parent", true);
					     		}
					     	}
					     	//ret.on("load", function(){
					     		ret.show();
					     		thumb.replaceWith(ret);
					     		$(this).hide();
					     	//});
					     	that.onResize();
					     
						});
					}else if(mediaObj.opts.mode=="modal"){
						icon.on("click", function(){
							thumb = $(this).closest(".frunt-preview-wpr").find('.frunt-preview-thumb');
							modalContent = that.mediaTypes[mediaObj.type].preview(mediaObj);
							that.modal({
								subject : thumb.attr("title"),
								description : modalContent
							});
						});
					}else if (mediaObj.opts.mode=="modal-noIcon"){
						thumb.on("click", function(){
							thumb = $(this);
							modalContent = that.mediaTypes[mediaObj.type].preview(mediaObj);
							that.modal({
								subject : thumb.attr("title"),
								description : modalContent
							});
						});	
					}
					if (mediaObj.opts.responsive){
						if (mediaObj.opts.syncParent)
							thumb.attr("data-sync-parent", true);
					}
					if (mediaObj.opts.noIcons==undefined || mediaObj.opts.noIcons==false ){
						wpr.append(icon);
					}
					wpr.append(thumb);
					break;
				//DIRECT EMBED
				case "direct_embed":
				default:
					ret =  that.mediaTypes[mediaObj.type].preview(mediaObj);
					ret.hide();
					//responsive?
					if (mediaObj.opts.responsive){
			     		ret.attr("data-bias", mediaObj.opts.bias);
			     		ret.addClass("frunt-responsive");
			     		
			     	}
			     	ret.show();
			     	
			     	wpr.append(ret);
					break;
			}
		
			if (wpr.is(':empty'))
				wpr.append("<div class='noImage'>"+mediaObj.type+"</div>");
				
			
			
			return wpr;

		},
		 //<--------- CMCM ADAPTATIONS -------------->//
	 		modal : function(opts){
		 	var that = this;
			var modal_bg = $("<div class='frunt-modal-bg'></div>");
			var modal_content = $("<div class='frunt-modal-content'></div>");
			var close = function(){
				modal_bg.remove();
			}
			
			modal_content.on("click", function(e){
				e.stopPropagation();
				//e.preventDefault();
			});
			var subject = (opts.subject) ? opts.subject : "";
			var description = (opts.description) ? opts.description : "";
			
			description = (typeof opts.description=="string") ? $(description) : description;
			closeButton = $("<span class='glyphicon glyphicon-remove icon frunt-modal-close' title='info'>");
			closeButton.on("click", close);
			modal_content.append(closeButton);
			modal_content.append($("<span class='frunt-modal-subject'>"+subject+"</span>"));
			
			
			//top_orig = 20;
			modal_content.css({
				"margin-top" : "0%",
				"opacity" :0
			});
			
			modal_content.append(description);
			modal_bg.append(modal_content);
			modal_bg.on("click", close);
			$("body").append(modal_bg);
			
			//img = modal_content.find("img").first();
			//ifr = modal_content.find("iframe").first();
			//IMG MODAL			
			HEIGHTBUFFER = 100;
			if (description.is("img")){
				description.imagesLoaded(function(img){
				dims = that.getResizeImageDimensions(modal_content.width(),$(window).height()-HEIGHTBUFFER, img.width, img.height, "within");
			
				//if (opts.boxDimensions){
				
					description.css({
						border: modal_content.css("border"),
						padding: modal_content.css("padding")
					});
					modal_content.css({
						width : dims.width+"px",
						height : dims.height+"px",
						border : "none"
					});
				
				});
			//IFRAME MODAL
			}else if (description.is("iframe")){
				console.log(modal_content.width());
				dims = that.getResizeImageDimensions(modal_content.width(),$(window).height()-HEIGHTBUFFER, 9,6, "within");
				modal_content.css({
						width : dims.width+"px",
						height : dims.height+"px",
					});
			}
		
			
			
			
			//calculate top
			buffer = -20;
			minimum = 30;
			topCalc = ($(window).height()-modal_content.height()+buffer)/2;
			topCalc = (topCalc < minimum ) ? minimum : topCalc;
			
			$(".frunt-modal-content").animate({
				"opacity" : 1,
				"margin-top" :  topCalc+"px"
			},200);
			
		},
		 mediaTypes : {
				image : {
					preview : function(mediaObj){
						mediaObj.opts = (mediaObj.opts==undefined) ? {} : mediaObj.opts;
				     	contents =  $("<img>");
				     	if (mediaObj)
				     	if (mediaObj.src){    
						   contents.attr("src", mediaObj.src);  	 
					    }else
					    	contents.addClass('noImage').html("No Image");
					    return contents;
					}
				},
				video : {
					preview : function(mediaObj){
						mediaObj.opts = (mediaObj.opts==undefined) ? {} : mediaObj.opts;
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
						//console.log('weeeeeeeeeeee');
						mediaObj.opts = (mediaObj.opts==undefined) ? {} : mediaObj.opts;
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
			     		//options defaults
			     		console.log(mediaObj.opts.autoplay);
			     		mediaObj.opts.autoplay = (mediaObj.opts.autoplay!=undefined && mediaObj.opts.autoplay==true) ?  1 : 0 ;
			     		console.log(mediaObj.opts.autoplay);
				     	emb = $('<iframe class="frunt-preview-video" width="100%" height="100%" style="display: block" src="http://www.youtube.com/embed/'+v+'?showinfo=0&autoplay='+mediaObj.opts.autoplay+'" frameborder="0" allowfullscreen></iframe>');
				     	return emb;
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				       	iframe = this.embed(mediaObj);
				       	iframe.attr("data-ratio", JSON.stringify(this.ratio));
				     	return iframe;
			     	}
		     	},
		     	"vimeo" : {
		     		ratio : [9,6],
		     		type : "video",
			     	regex : /\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i, //matches[1] will be id
			     	embed : function(mediaObj){
			     		v = cmcm.fruntWidget.trim(mediaObj.src.match(this.regex)[1]);
			     		//options defaults
			     		mediaObj.opts.autoplay = (mediaObj.opts.autoplay!=undefined  && mediaObj.opts.autoplay==true) ?  1 : 0 ;
			     		console.log(mediaObj.opts.autoplay);
				     	emb = $('<iframe  class="frunt-preview-video" src="//player.vimeo.com/video/'+v+'?portrait=0&autoplay='+mediaObj.opts.autoplay+'" width="100%" height="100%" frameborder="0" style="display: block" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
				     	return emb;
				     	
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				       	iframe = this.embed(mediaObj);
				       	iframe.attr("data-ratio", JSON.stringify(this.ratio));
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
			     		mediaObj.opts.autoplay = (mediaObj.opts.autoplay!=undefined  && mediaObj.opts.autoplay==true) ? mediaObj.opts.autoplay : "false";
				     	emb = $('<iframe class="frunt-preview-sound" width="100%" height="100%" style="display: block" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='+encodeURI(mediaObj.src)+'&amp;auto_play='+mediaObj.opts.autoplay+'&amp;hide_related=true&amp;visual='+mediaObj.opts.visual+'"></iframe>');	
				     	return emb;
			     	},
			     	preview : function(mediaObj){
				     	iframe = this.embed(mediaObj);
				     	iframe.attr("data-ratio", JSON.stringify(this.ratio));
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