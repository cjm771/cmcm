
		var cmcm = {
			editMode : 0,
			unsavedChanges : 0,
			debug : 0,
			src : '',
			endpoint : 'php/ajax.php',
			filesAllowed : ["image/jpeg", "image/jpg", "image/gif", "image/png"],
			jqXHR : {},
			jqXHR_count : 0,
			debugInterval : null,
			timer : null,
			configDraft : null,
			orignal : {},
			draft : {},
			data : {},
			regex : {
				filename : { 
					regex : /^[A-Za-z0-9_.]+$/gi, 
					error : "Filename Must be made up of only underscores, dots, and alphanumerics" 
				},
				key :  { 
					regex : /^[A-Za-z0-9\s_]+$/gi, 
					error : "Attribute names must be made up of only underscores, and alphanumerics" 
				}
			},
			attributeTypes : {
				bool : {
					edit : function(obj){
						i =  $("<input type='checkbox' class='attr-input' >");
						i.attr("checked", (obj.value==1) ? true : false);
						//change event
						if (obj.onChange){
							i.on("change", function(e){
									var o = {
									k : obj.key,
									v : ($(this).is(":checked")) ? 1 : 0,
									el : this
									
								};
								obj.onChange(o);
							});
						}
						return i;
					},
					def : function(){
						return 0;
					},
					validate : function(v){
						
						resp = {success : 1};
						if (v!=0 && v!=1)
							resp = { error : "Boolean types can only be a 0 or 1." };
						return resp;
						
						
					}
				},
				string : {
					edit : function(obj){
						i =  $("<input type='text' class='form-control attr-input'>");
						//value
						i.val(obj.value);
						//change event
						if (obj.onChange){
							i.on("keyup change mouseup focusout", function(e){
								var o = {
									k : obj.key,
									v : $(this).val(),
									el : this
									
								};
								obj.onChange(o);
							});
						}
						return i;
					},
					validate : function(){
						return {success : 1};
					}
				},
				int : {
					edit : function(obj){
						i =  $("<input type='text' class='form-control attr-input'>");
						//change event
						i.val(obj.value);
						if (obj.onChange){
							i.on("keyup change mouseup", function(e){
								var o = {
									k : obj.key,
									v : $(this).val(),
									el : this
									
								};
								obj.onChange(o);
							});
						}
						return i;
					},
					validate : function(v){
						var reg = /^-?\d+$/;
						resp = {success : 1};
						if (!(v+"").match(reg))
							resp = { error : "Not an Integer." };
						return resp;
						
						
					}
				},
				timestamp : {
					edit : function(obj){
					
					
						dateFormat = "dd MM yyyy - HH:ii p";
						//current time
						var d = moment(new Date());
						//init value etc.js dependent
						var dateString = d.format("DD MMMM YYYY - hh:mm a");
						//alert(dateString);
					//	cmcm.edit("timestamp", dateString, cmcm.draft, "v");
						
					 	timepicker = $('<div class="input-group date form_datetime"'+
					 	' data-date-format="'+dateFormat+'" data-date="'+dateString+'" data-link-field="dtp_input1"> </div>');		
					 	input = $('<input class="form-control date_input attr-input" type="text" value="" readonly>');
					 	addon = $('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>'+
					 	'<input type="hidden" id="dtp_input1" value="2014-02-01 15:45:39">');
               
						
					
						if (obj.value!="")
							input.val(obj.value);
						else
							input.val(dateString);
							
					 	if (obj.onChange){
							input.on("change", function(e){
								var o = {
									k : obj.key,
									v : $(this).val(),
									el : this
									
								};
								obj.onChange(o);
							});
						}
					    timepicker.append(input);
					    timepicker.append(addon);
					    //well.append(timepicker);
					    //load date timer thing
					    timepicker.datetimepicker({
						    weekStart: 1,
					        todayBtn:  1,
							autoclose: 1,
							todayHighlight: 1,
							forceParse: 0,
					        showMeridian: 1
						}); 
						
					    return timepicker;
					},
					def : function(){
						//current time
						var d = moment(new Date());
						//init value etc.js dependent
						var dateString = d.format("DD MMMM YYYY - hh:mm a");
						return dateString;
					}
				},
				text : {
					
					edit : function(obj){
						/*
						recieves 
						obj.key, - key
						obj.value, - value
						obj.tmpl, - template obj
						obj.el - future jquery el
						*/
						ta =  $("<textarea class='form-control attr-input hundredHeight'></textarea>");
						ta.val(obj.value);
						if (obj.onChange){
							ta.on("keyup change", function(e){
								var o = {
									k : obj.key,
									v : $(this).val(),
									el : this
									
								};
								obj.onChange(o);
							});
						}
						
						return ta;
					}
				},
				choice : {
					edit : function(obj){
						/*
						recieves 
						obj.key, - key
						obj.value, - value
						obj.tmpl, - template obj
						obj.el - future jquery el
						*/
						i =  $("<select class='form-control attr-input'></select>");
						options = obj.tmpl.choices.split(",");
						
						i.append("<option value=''>--</option>");
						$.each(options, function(index, v){
							opt = $("<option>"+v+"</option>");
							opt.attr("checked", (cmcm.trim(v)==cmcm.trim(obj.value)) ? true : false);
							opt.attr("value", v.trim());
							i.append(opt);
						});
						//value
						i.val(obj.value);
						//change event
						if (obj.onChange){
							i.on("change", function(e){
								var o = {
									k : obj.key,
									v : $(this).val(),
									el : this
									
								};
								obj.onChange(o);
							});
						}
						return i;
					},
					extras : function(tmpl){
						//generate dom
						wpr =  $("<div class='extrasBox'></div>");
						wpr.append("<div class='header'>Choices (Comma Seperated)</div>");
						input =  $("<input type='text' data-extra-attr='choices' data-extra-val=''>");
						
						input.val((tmpl.choices) ? cmcm.unHtmlEntities(tmpl.choices) : "");
						input.attr("data-extra-val", tmpl.choices);
						input.on("keyup", function(e){
							$(this).attr("data-extra-val", cmcm.htmlEntities($(this).val()));
						});
						
						wpr.append(input);
						
						//extras should return obj with DOM @dom and array of attributenames @data
						ret = {
							dom : wpr,
							data : ["choices"]
						};
						return ret;
					},
					
				} //<--end choice type
			}, //<-- end attribute types
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
						    contents.on("click", function(e){
						    	//only work on project page
						    	if ($(this).closest(".media_wpr").length){
									  //restraints
								    image = $("<img>");
								    image.attr("src", mediaObj.src);
								    image.on("load", function(){
								    	//determine size..
								    	maxWidth = 750;
								    	maxHeight = $(window).height()-100;
							    	   ratioX = maxWidth / this.width;
							    	   ratioY = maxHeight /this.height;
							    	   ratio = Math.min(ratioX, ratioY);
								
							    	   newWidth = (this.width * ratio);
							    	   newHeight = (this.height * ratio);
							    	   
							    	   $(image).css({
								    	   width : newWidth+"px",
								    	   height : newHeight+"px"
							    	   })
							    	   
							    	   cmcm.modal({
										    subject : "Image Preview",
										    boxDimensions : {width: newWidth, height: newHeight},
										    description : image,
										    
									    });
								   
								    });
							   }
						    
						    });
						  
					    }
					    return contents;
					},
					remove : function(){}
				},
				video : {
					preview : function(mediaObj){
						//check url
						ret = "";
						$.each(cmcm.externalMediaTypes, function(type, mediaType){
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
						$.each(cmcm.externalMediaTypes, function(type, mediaType){			
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
		     		type : "video",
			     	regex :  /\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i, //matches[1] will be id,
			     	embed : function(mediaObj){
			     		v = cmcm.trim(mediaObj.src.match(this.regex)[1]);
				     	emb = $('<iframe width="640" height="480" style="display: block" src="http://www.youtube.com/embed/'+v+'?showinfo=0" frameborder="0" allowfullscreen></iframe>');
				     	return emb;
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				     	//return what would be in img_wpr
				     	contents = $("<div class='media_crop'></div>");
				     	if (mediaObj.thumb && mediaObj.thumb!="false"){
					     	contents.append("<img>").find('img').attr("src", mediaObj.thumb);  	
					    }else
					    	contents.addClass('noImage');
				     	
					    videoButton = $("<span class='glyphicon glyphicon-facetime-video overlayIcon has_tooltip' title='Youtube Video'></span>");
				     	videoButton.on("click", function(e){
				     		e.preventDefault();
				     		e.stopPropagation();
					     	cmcm.modal({
							    subject : "Video Preview",
							    description : that.embed(mediaObj),
							    boxDimensions : {width: 640, height: 480}
						    });
				     	});
				     	contents.append(videoButton);
				     	return contents;
			     	}
		     	},
		     	"vimeo" : {
		     		type : "video",
			     	regex : /\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i, //matches[1] will be id
			     	embed : function(mediaObj){
			     		v = cmcm.trim(mediaObj.src.match(this.regex)[1]);
				     	emb = $('<iframe src="//player.vimeo.com/video/'+v+'?portrait=0" width="640" height="480" frameborder="0" style="display: block" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
				     	return emb;
				     	
			     	},
			     	preview : function(mediaObj){
			     		var that = this;
				     	//return what would be in img_wpr
				     	contents = $("<div class='media_crop'></div>");
				     	if (mediaObj.thumb && mediaObj.thumb!="false"){
					     	contents.append("<img>").find('img').attr("src", mediaObj.thumb);  	
					    }else
					    	contents.addClass('noImage');
					    videoButton = $("<span class='glyphicon glyphicon-facetime-video overlayIcon has_tooltip' title='Vimeo Video'></span>");
				     	videoButton.on("click", function(e){
				     		e.preventDefault();
				     		e.stopPropagation();
					     	cmcm.modal({
							    subject : "Video Preview",
							    description : that.embed(mediaObj),
							    boxDimensions : {width: 640, height: 480}
						    });
				     	});
				     	contents.append(videoButton);
				     	return contents;
			     	}
		     	},
		     	"soundcloud" : {
		     		type : "sound",
			     	regex : /\/\/(?:www\.)?(?:api.soundcloud.com|soundcloud.com|snd.sc)\/(.*)$/i,
			     	embed : function(mediaObj){
				     	emb = $('<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='+encodeURI(mediaObj.src)+'&amp;auto_play=false&amp;hide_related=false&amp;visual=false"></iframe>');
				     	return emb;
			     	},
			     	preview : function(mediaObj){
			     		
				     	//return what would be in img_wpr
				     	contents = $("<div class='media_crop'></div>");
				     	iframe = this.embed(mediaObj);
				     	iframe.css("display", "none");
				     	contents.append(iframe);
				     	if (mediaObj.thumb && mediaObj.thumb!="false"){
					     	contents.append("<img>").find('img').attr("src", mediaObj.thumb);  	
					    }else
					    	contents.addClass('noImage');
				     	
				     	playButton = $("<span class='playButton glyphicon glyphicon-play overlayIcon has_tooltip' title='Soundcloud Sound'></span>");
				     	//<---PLAY FEATURE
				     	//first load soundcloud js api
				     	
				     	if (!$("#soundcloud_api").length){
					     	scapi = $("<script src='' id='soundcloud_api'></script>");
				     		scapi.attr("src", "js/api.soundcloud.js");
				     		$("body").append(scapi);
				     	}
				     	
			     		//now add controls
			     		playButton.on("click", function(e){
			     			var button = this;
			     			e.preventDefault();
			     			e.stopPropagation();
			     			//reset things..
			     			$(".playButton.glyphicon-pause").not(this).removeClass("glyphicon-pause").addClass("glyphicon-play");
			     			wpr = $(this).closest(".media_wpr");
			     			mediaId = wpr.attr("data-id");
			     			//if none must be on the front page
			     			if (!wpr.length){
			     				wpr = $(this).closest(".img_wpr");
			     				mediaId = wpr.attr("data-coverImage");
			     			}
			     			//add it
			     			if (!wpr.find(".canvasPlayer").length){
				           		  progWpr = $("<canvas class='canvasPlayer' width='"+wpr.find(".media_crop").width()+"' height='"+wpr.find(".media_crop").height()+"'></canvas>");
				           		  atto = (wpr.find("a").length) ? wpr.find(".media_crop") : wpr;
				           		  atto.append(progWpr);
			           		 }
			           		  
			     			iframeId = "sc-embed-"+mediaId;
			     			wpr.find("iframe").attr("id", iframeId );
				     		 widget = SC.Widget(iframeId);
				     		//on play progress
				     		
				     		 widget.bind(SC.Widget.Events.PLAY_PROGRESS, function(e){
				     		 	newPercent = Math.round(e.relativePosition*100);
				     		 	 wpr = $(button).closest(".media_wpr");
				     			//if none must be on the front page
				     			if (!wpr.length){
				     				wpr = $(button).closest(".img_wpr");
				     			}
				     			if (newPercent!=-1){
					     			/*
					     			wpr.find(".progress_bar").css({
						     			width: newPercent+"%"
					     			});
					     			*/
					     			radius = 20;
					     			var c=wpr.find(".canvasPlayer").get(0);
					     			canWidth = wpr.find(".canvasPlayer").width();
					     			canHeight = wpr.find(".canvasPlayer").height();
									var ctx=c.getContext("2d");
									ctx.clearRect(0, 0, c.width, c.height);
									//container
									ctx.strokeStyle =  "rgba(0, 0, 0, 0.5)";
									ctx.lineWidth = 3;
									ctx.beginPath();
									ctx.arc(canWidth/2,canHeight/2,radius,0,2*Math.PI);
									ctx.stroke();
									//loading prog
									ctx.strokeStyle = "rgba(255,255,255,0.8)";
									ctx.beginPath();
									ctx.arc(canWidth/2, canHeight/2,radius,cmcm.toRad(0+270),cmcm.toRad(e.loadProgress * 360+270));
									ctx.stroke();
									//play prog
									ctx.strokeStyle = '#ffffff';
									ctx.beginPath();
									ctx.arc(canWidth/2, canHeight/2,radius,cmcm.toRad(0+270),cmcm.toRad(newPercent/100 * 360+270));
									ctx.stroke();
				     			}
				     		
				     		 });
				     		 
				     		 
				     		//on stop, remove pause
				     		 widget.bind(SC.Widget.Events.FINISH, function(){
				     		 	//RESET
					     		wpr = $(button).closest(".media_wpr");
				     			//if none must be on the front page
				     			if (!wpr.length){
				     				wpr = $(button).closest(".img_wpr");
				     			}
				     			wpr.find(".canvasPlayer").remove();
					     		 $(".playButton.glyphicon-pause").not(this).removeClass("glyphicon-pause").addClass("glyphicon-play");
					     		 
					     		 
				     		 });
				     		 
				     		
				     		 
				     		 //start playing
				     		 if ($(this).hasClass("glyphicon-play")){
				     		 	$(this).addClass("glyphicon-pause");
				     		 	$(this).removeClass("glyphicon-play");
					     		 widget.play();
				     		 }else{ //pause playing
				     		 	$(this).removeClass("glyphicon-pause");
				     		 	$(this).addClass("glyphicon-play");
					     		widget.pause();
				     		 }
				     	});
				     	
				     	//<--END PLAY FEATURE
				     	contents.append(playButton);
				     	return contents;
			     	}
		     	}
	     	},
	     	toRad : function(deg){
		     		return (deg*Math.PI/180);
		 	},
		 	updateKelly : function(k){
		 			if (k != undefined) cmcm.kelly = k;
			 		window.localStorage.kelly = cmcm.kelly;
					setInterval(function(){
						if (cmcm.kelly!=window.localStorage.kelly){
							cmcm.kelly = window.localStorage.kelly;
						}
						//exd
					},100);
		 	},
			init : function(afterLoad){
				//
				var that= this;
				this.load(function(){
					//page specific init
					afterLoad();
					//debugging
				//	if (that.$_GET("debug")!="off")
						//that.renderDebugBox();
					that.updateKelly();
					//ie placeholder fix
					$("input, textarea").placeholder();
					//tooltips
					that.updateTooltip();
					//save/loading dialog
					  $('#loading_wpr').affix({
					    offset: {
					      top: function(){return $("#header").offset().top+$("#header").height()}
					      }
					  });
				});
				
				window.onbeforeunload = function(){
					if (that.unsavedChanges==1){
						return "You have made changes on this page that you have not yet saved."+
					" If you navigate away from this page you will lose your unsaved changes.";
					
					}
				};
				window.onunload =  function(){
					//this doesnt really work in most browsers but worth a shot
					if (that.unsavedChanges==1){
						return that.handleUnload();
					}
				};
				
			},
			
			//<----------DEBUG FUNCS----------------->//	
			//regular debug box refreshes every 500 ms
			renderDebugBox : function(){
				if (this.debug!=0){
					var that = this;
					box = $("<pre class='debug' id='debugBox'></pre>");
					box.append(JSON.stringify(that.draft, null, 4));
					$("body").append(box);
					this.debugInterval = setInterval(function(){
						$("#debugBox").html(JSON.stringify(that.draft,null, 4));
					}, 500);
				}
			},
			//special debug box @id= id attr and @css = css for box
			setDebugBox : function(obj, id, css){
				if (this.debug!=0){
					if (!$("#"+id).length){
						box = $("<pre class='debug'></pre>");
						box.attr("id", id);
						box.css(css);
					}else{
						box = $("#"+id);
					}
					//clearInterval(this.debugInterval);
					box.html(JSON.stringify(obj,null, 4));
					$("body").append(box);
				}
			},
			//<----------RENDER FUNCS----------------->//	
			renderTemplateEditor : function(){
				var that= this;
				this.formatTemplateTable(this.data.template.project, "#projects_tmpl");
				this.formatTemplateTable(this.data.template.media, "#media_tmpl");
				
				
				$("#proj_tmpl_add").on("click", function(){
					that.addToTemplate("#projects_tmpl");
				});
				$("#media_tmpl_add").on("click", function(){
					that.addToTemplate("#media_tmpl");
				});
			},
			configValidator : function(){
				var that = this;
				data = $.extend({}, that.configDraft);
				errors = [];
				$(".validate-error").removeClass("validate-error");
				//set up basic validatons
				var validators = {
					skip : { config : ["users", "src"], data : []},
					required :  { config : [], data : ["mediaFolder"]},
					regex : { 
						data : {
							"thumb['max_width']" :  { 
								regex : /^\d+$|^\*{1}$/gi,
								msg : 'Must be a number or a wildcard (*)'
							},
							"thumb['max_height']" :  { 
								regex : /^\d+$|^\*{1}$/gi,
								msg : 'Must be a number or a wildcard (*)'
							},
							"mediaFolder" : { 
								regex :  /^[^\\?%*:|\"<>]*\/$/gi,
								msg : 'Invalid directory name (Invalid characters or did not end with /)'
							}
						}
					}
				};
				//skip parameters..these get handled on the spot
				$.each(validators.skip, function(k,v){
					$.each(v, function(index, attr){
						delete data[k][attr];
					});
				});
				//required
				$.each(validators.required, function(k,v){
					$.each(v, function(index, attr){
						if (that.trim(data[k][attr])==""){
							errors.push("<b>"+attr+"</b>: Field is Required.");
							rel = that.configRelations[k];
							$("["+rel.attr+"=\""+attr+"\"]").addClass("validate-error");
							$("["+rel.attr+"=\""+attr+"\"]").closest(".panel").find("h4.panel-title").addClass("validate-error");
						}
					});
				});
				//regex
				if (errors.length==0){
					$.each(validators.regex, function(k,v){
						$.each(v, function(attr, obj){
							if (!that.trim(eval("data."+k+"."+attr)+"").match(obj.regex)){
								errors.push("<b>"+attr+"</b>: "+obj.msg);
								rel = that.configRelations[k];
								$("["+rel.attr+"=\""+attr+"\"]").addClass("validate-error");
								//alert($("["+rel.attr+"=\""+attr+"\"]").closest("h4.panel-title")[0].length);
								$("["+rel.attr+"=\""+attr+"\"]").closest(".panel").find("h4.panel-title").addClass("validate-error");
							}
						});
					});	
				}
				//thumbnail: make sure not both are not wildcards
				if (that.trim(data.data.thumb.max_width)=="*" && that.trim(data.data.thumb.max_width)==that.trim(data.data.thumb.max_height)){
					errors.push("<b>Thumb dimensions</b>: Dimensions cannot both be wildcards.");
					rel = that.configRelations["data"];
					$("["+rel.attr+"=\"thumb['max_width']\"], ["+rel.attr+"=\"thumb['max_height']\"]").addClass("validate-error");
					$("["+rel.attr+"=\"thumb['max_width']\"], ["+rel.attr+"=\"thumb['max_height']\"]").closest(".panel").find("h4.panel-title").addClass("validate-error");
					
				}
				//thumbnail: if crop is set..you must provide both dimensions
				if ((that.trim(data.data.thumb.max_width)=="*" || that.trim(data.data.thumb.max_height)=="*") && data.data.thumb.crop==1){
					errors.push("<b>Thumb Crop</b>: Max_width and Max_height must both be filled, if you want a cropped thumbnail.");
					rel = that.configRelations["data"];
					$("["+rel.attr+"=\"thumb['max_width']\"], ["+rel.attr+"=\"thumb['max_height']\"]").addClass("validate-error");
					$("["+rel.attr+"=\"thumb['max_width']\"], ["+rel.attr+"=\"thumb['max_height']\"]").closest(".panel").find("h4.panel-title").addClass("validate-error");
				}
					
				//display errors..
				errorBox = $("#config_errorBox");
				successBox = $("#config_successBox");
				errorBox.hide();
				successBox.hide();
				if (errors.length>0){
					errorBox.empty().append("<b>The Following "+errors.length+" Errors Occurred:</b>");
					ul = $("<ul></ul>");
					$.each(errors, function(index, val){
						ul.append("<li>"+val+"</li>");
					});
					errorBox.append(ul).show();
				}else{
					//success
					that.toggleButton("#config_save", 0, "Saving..");
					that.saveFile(that.src, {
						action : "editConfig",
						data : data,
						onError : function(msg){
							errorBox = $("#config_errorBox");
							errorBox.empty().html(msg).show();
							that.toggleButton("#config_save", 1, "Save");
						},
						onSuccess : function(respObj){
							successBox = $("#config_successBox");
							successBox.empty().html("Settings saved.").show();
							setTimeout(function(){
								successBox.hide("slow");
							}, 2000);
							that.data.thumb = respObj.data.thumb;
							that.toggleButton("#config_save", 1, "Save");
							that.original = $.extend(true, {}, that.configDraft);
						}
					});
				}
			},
			loadConfig : function(){
				var that = this;
				//load config stuff..and then format
				this.saveFile("xxx", {
					action : "loadConfig",
					noSuccessIndicator : 1,
					onError : function(msg){
						
					},
					onSuccess : function(respObj){
						that.config = respObj.data;
						that.formatConfig();
					}
				});
			},
			handleUnload : function(){
				var that = this;
				
				if (!$.isEmptyObject(that.draft)){
					
					//lets send media objs to server...see which ones can be purged...
					data = {};
					if (that.$_GET('id')){
						//not new
						data[that.$_GET('id')] = that.draft.media;
					}else{
						data["new"] = that.draft.media;
					}
					
					//last attempt to purge unlinked media
					that.saveFile(that.src, {
						action : "handleUnsavedMedia",
						data : data,
						onSuccess : function(respObj){
						}
					});
					
				}
				return true;				
			},
			configRelations : {
					data : {
						attr: "data-attr",
						obj : "that.data",
					},
					config : {
						attr: "data-config-attr",
						obj : "that.config"
					}
			},
			getConfigInfo : function(el){
				if ($(el).is("["+this.configRelations.data.attr+"]")){
					attType = this.configRelations.data;
					k = "data";
				}else{
					attType = this.configRelations.config;
					k = "config";	
				}
				att = $(el).attr(attType.attr);	
				return {
					k : k,
					att : att,
					attType : attType
				};
			},
			toggleUserEdit : function(el){
				var that = this;
			
				this.editMode=1;
				tr =  $(el).closest(".userRow");
				tr.addClass("editMode");
				that.trBackup = $(tr).clone(true,true);
				
				//edit mode for name and pw
				nameEdit = $("<input type='text' id='username_new'>");
				nameEdit.css({
					width: "100%"
				});
				nameEdit.val(tr.attr("data-id"));
				tr.find("td.name").html(" ").append(nameEdit);

				//pw edit mode
				pwEdit = $("<div class='extrasBox'></div>");
				pwEdit.append("<div class='errorBox'></div>");
				pwEdit.append("<div class='header'>New Password</div>");
				pwEdit.append("<input type='password' class='confirm_pw form_control' id='confirmPw_new'>");
				pwEdit.append("<div class='header'>Confirm Password</div>");
				pwEdit.append("<input type='password' class='confirm_pw form_control' id='confirmPw_confirm'>");
				tr.find("td.val").html(" ").append(pwEdit);
				
				//append cancel and ok to actionPanel
				panel = tr.find(".actionPanel");
				panel.show();
				
				tr.off("mouseover");
				tr.off("mouseout");
	
				cancelButton =  $("<span class='glyphicon glyphicon-ban-circle icon user_cancelEdit' title='cancel'></span>");
				okButton =  $("<span class='glyphicon glyphicon-ok icon' title='ok'></span>");
				okButton.on("click", function(){
					tr =  $(this).closest(".userRow");
					error = false;
					
					//collect args
					args = {
						newPw : tr.find("#confirmPw_new"),
						confirmPw : tr.find("#confirmPw_confirm"),
						name : tr.find("#username_new")
					}
					
					//pre validate: check all filled in fields..
					tr.find(".errorBox").hide();
					$(".validate-error").removeClass("validate-error");
					$.each(args, function(k,v){
						if (that.trim(v.val())==""){
							v.addClass("validate-error");
							tr.find(".errorBox").html("No fields can be blank").show();
							error = true;
						}
					});
					
					//pre validate: check passwords match..
					if (!error && args.newPw.val()!=args.confirmPw.val()){
							args.confirmPw.addClass("validate-error");
							args.newPw.addClass("validate-error");
							tr.find(".errorBox").html("Passwords dont match").show();
							error = true;
					}
					
					//validate: send ajax req
					if (!error){
						that.saveFile("xxx", {
							action : "addUser",
							data : {
								username : that.trim(args.name.val()),
								new_pw :  args.newPw.val(),
								confirm_pw : args.confirmPw.val(),
								existingUser : (tr.hasClass("newUser")) ? false : that.trBackup.attr("data-id")
							},
							onError : function(msg){
								$("tr.userRow.editMode").find(".errorBox").html(msg).show();	
							},
							onSuccess : function(respObj){
								//success return to normal
								tr = $("tr.userRow.editMode");
								name = that.trim(tr.find(".name input").val())
								that.editMode = 0;
								tr.removeClass("editMode");
								tr.removeClass("newUser");
								//if its you...
								if (that.trBackup.attr("data-id")==$("#my_username").html()){
									$("#my_username").html(name);
								}
								//update row and return to normal
								that.trBackup.attr("data-id", name);
								that.trBackup.find(".name").html(name);
								that.trBackup.find(".val").html("*******");
								that.trBackup.removeClass("editMode");
								that.trBackup.removeClass("newUser");
								tr.replaceWith(that.trBackup);
								
								
							}
						});
					}
					
				});
				
				cancelButton.on("click", function(){
					tr =  $(this).closest(".userRow");
					if (tr.hasClass("newUser")){
						tr.remove();
					}
					else{
						tr.replaceWith(that.trBackup);
					}
					that.editMode = 0;
				});
				
				panel.html("");
				panel.append(cancelButton).append(okButton);
				
			

			},
			trim : function(str){
				str = (typeof str == "string") ? str.trim() : str;
				return str;
			},
			_userRow : function(user,pw){
				var that = this;
				tr = $("<tr class='userRow'></tr>");
				tr.attr("data-id",user);
				td = "<td class='name'>"+user+"</td><td class='val'>"+pw+"</td>";
				panel = $("<div class='actionPanel'></div>");
				deleteButton = $("<span class='glyphicon glyphicon-remove icon' title='remove'></span>");
				deleteButton.click(function(){
				tr = $(this).closest(".userRow");
				user = tr.attr("data-id");
				 that.modal({
					 subject : "Confirm Deletion",
					 description : "<div id='confirm_delete_errorBox' class='errorBox'></div>Are you sure you want to delete <b>"+user+"</b>?",
					 buttons : {
						 delete : {
							 type : "custom",
							 clicked : function(me){
							 	$('#confirm_delete_errorBox').hide();
							 	that.saveFile("xxx",{
								 	action : "deleteUser",
								 	data : {user: user},
								 	onError : function(msg){
									 	$('#confirm_delete_errorBox').html(msg).show();
								 	},
								 	onSuccess : function(respObj){
								 		//remove from dom
									 	$(".userRow[data-id='"+user+"']").remove();
									 	 me.close();
								 	}
							 	})
								
							 },
						 },
						 cancel : {
							 type : "cancel"
						 }
					 }
					 
				 });
		
				});
				editButton = $("<span class='glyphicon glyphicon-wrench icon user_toggleEdit' title='edit'></span>");
				editButton.click(function(){
				
				 that.toggleUserEdit(this);
		
				});
				
				panel.append(deleteButton);
				panel.append(editButton);
				
				tr.append(td);
				tr.append(panel);
				$("<td></td>").append(panel).appendTo(tr);
				
				tr.on("mouseover", function(){
					if (that.editMode!=1)
						$(this).find(".actionPanel").show();
				});
				
				tr.on("mouseout", function(){
					$(this).find(".actionPanel").hide();
				});
			
				return tr;
				
			},
			formatSetup : function(settings){
				var that = this;
				//we get settings...
				//fill in auto fields
				$("[data-id]").each(function(){
					attr = $(this).attr("data-id");
					type = $(this).attr("data-type");
					if (settings[type][attr]!=undefined){
						$(this).val(that.unHtmlEntities(settings[type][attr]));
					}
				});
				
				that._inputValidator("#setup_username", function(val){
					resp = {};
					val = that.trim(val);
					if (val==""){
						//resp.error = "Field is required";
						//clear errors
						$("#setup_pw, #setup_pw_confirm").removeClass("error");
					}else{
						
						if (val.length<settings.loginSettings.username_min){
							resp.error = "Username must be at "+settings.loginSettings.username_min+" least Characters";
						}
						else if (!val.match(new RegExp(settings.loginSettings.username_regex.substring(1, settings.loginSettings.username_regex.length-1), 'gi'))){
						
							resp.error = settings.loginSettings.username_regex_error_message;
						}
						else if (settings.config.users[val]!=undefined){
							resp.error = "Username already exists";
						}
						else
							resp.success = 1;
					}
					return resp;
				}, function(){
						cmcm_slider.checkForErrors();
						$("#setup_pw, #setup_pw_confirm").trigger("change");
				});
				that._inputValidator("#setup_pw", function(val){
					if (that.trim($("#setup_username").val())==""){ 
						resp.success = 1; 
						return resp;
					 }
					$("#setup_pw_confirm").trigger("change");	
					resp = {};
					if (that.trim(val)=="")
						resp.error = "Field is required";
					else if (val.length<settings.loginSettings.password_min){
						resp.error = "Password must be at "+settings.loginSettings.password_min+" least Characters";
					}else
						resp.success = 1;
					return resp;
					
				}, function(){
						cmcm_slider.checkForErrors();
				});
				
				that._inputValidator("#setup_pw_confirm", function(val){
					if (that.trim($("#setup_username").val())==""){ 
						resp.success = 1; 
						return resp;
					 }
					resp = {};
					if (that.trim(val)=="")
						resp.error = "Field is required";
					else if (val!=$("#setup_pw").val()){
						resp.error = "Password and Confirm Password do not match";
					}
					else
						resp.success = 1;
					return resp;
				
				}, function(){
						cmcm_slider.checkForErrors();
				});
								
				that._inputValidator("#setup_filename_input", function(val){
					resp = {};
					val = that.trim(val);
					if (that.trim(val)=="")
						resp.error = "Field is required";
					else if (!val.match(that.regex.filename.regex)){
						resp.error = that.regex.filename.error;
					}else if ($.inArray(val, settings.files)!=-1)
							resp.error = "Filename already exists";						
					else
						resp.success = 1;
					return resp;
				
				}, function(){
						cmcm_slider.checkForErrors();
				});
				
				
				
				
				$("#setup_numberOfUsers").html(Object.keys(settings.config.users).length);
				$("#setup_srcName").html(settings.config.src);
				
				window.onresize = function(){
					that.verticalCenter("#container");
				};
				
			},
			verticalCenter : function(el){
				//calculate top
				buffer = -5;
				topCalc = ($(window).height()-$(el).height()+buffer)/2;
				topCalc = (topCalc < 0 ) ? 0 : topCalc;		
				$(el).stop();
				$(el).clearQueue();		
				$(el).animate({
					"margin-top" :  topCalc+"px"
				},200);
				
				
			},
			_inputValidator : function(el, onChange, onDone){
				
				var that = this;
				
				$(el).wrap("<div class='inputValidator'></div>")
				$(el).parent().append("<div class='indicator'></div>");
				$(el).on("change paste keyup", function(){
					$(el).removeClass("error").removeClass("success");
					validate = onChange($(this).val());
					if (validate.error){
						$(el).addClass('error');
						$(el).parent().find(".indicator").empty().append("<span class='glyphicon glyphicon-remove red has_tooltip' title='"+validate.error+"'></span>");
					}else{
						$(el).addClass('success');
						$(el).parent().find(".indicator").empty().append("<span class='glyphicon glyphicon-ok green'></span>");
					}
					if (onDone)
						onDone();
					that.updateTooltip();
				});
			},
			formatConfig : function(){
				var that = this;
				that.configDraft = {
					data : {
						thumb:{},
						sort:{}
					}, 
					config: {}
				};
				
				//these are tooltip for config settings..help automate this :)
				hints = {
					data : {
					
						title : "<b>Title of your overall index/archive/website/etc</b>. Best utilized as header text, title tag.",
						subtitle : "Subtitle of your overall index/archive/website/etc. Could be your name, alternative title. Best utilized as part of header text, title tag.",
						description : "Description of your overall index/archive/website/etc. Generally used for meta description.",
						mediaFolder : "Directory where to store uploaded media (relative to cmcm root). This will not change file source for already uploaded media.",
						"thumb['max_width']" : "Thumbnail Max Dimensions (width and height respectively). Thumbs are located in subdirectory of media folder, called thumbnail/. Dimensions are in px. You can put a wildcard (*) instead of a number, if you only want to specify one of the dimensions. Crop option will cut image to exact dimensions you specify.",
						"sort['by']" : "Default sorting mode of your projects on the main page and have the order stored in the data file. You can obviously resort as much as you like, and can also change the order when designing your front end."
					},
					config : {
						src : "<b>Current Source</b> file (in /data folder of cmcm root). With cmcm, you can have multiple storage files and can switch between them at any time. Click on the button for source options.",
						mediaUtils : "Manage your media with these set of tools. Purge unlinked images, Consolidate files, and regenerate thumbs. Click on the button for media options.",
						loginEnabled : "If off, no login will be required to access the cmcm root",
						setupMode : "If on, setup mode will be reinitialized. No data will change, user will go through the setup process on page reload (if login disabled) or logout (if login enabled)"
					}
				}
				
				//apepend  sort options
				$("[data-attr=\"sort['by']\"]").append(that._sortOptions("by"));
				$("[data-attr=\"sort['direction']\"]").append(that._sortOptions("direction"));
				
				//format directory viewer for mediaFolder
				that.serverBrowser($("[data-attr=\"mediaFolder\"]"), {folderSelectMode : 1});
				
				$("#config_mediaUtils_button").closest("tr").find(".name").append("<span class='hint glyphicon glyphicon-question-sign has_tooltip' title='"+hints.config.mediaUtils+"'></span>");
				//CONFIG EDIT MODE
				//handle basic value stuff
				$.each(this.configRelations, function(k,v){
					$("["+v.attr+"]").each(function(index, el){
						//get attribute key in string form
						att = $(el).attr(v.attr);	
						//enable hints :) 
						if (hints[k][att]!=undefined)
							$(this).closest("tr").find(".name").append("<span class='hint glyphicon glyphicon-question-sign has_tooltip' title='"+hints[k][att]+"'></span>");
						//based if there is a type..handle in different ways
						switch ($(el).attr("data-type")){
							//no edit mode just tags
							case 'plain':
								//define def
								def = (eval(v.obj+"."+att)!=undefined) ? eval(v.obj+"."+att) : "";
								$(el).html(def);
								break;
							//check box
							case 'bool':
								//define def
								def = (eval(v.obj+"."+att)!=undefined) ? eval(v.obj+"."+att) : 0;
								//dom def
								$(el).attr("checked", ((def) ? true : false));
								//listener
								$(el).on("change", function(){
									cinfo = that.getConfigInfo(this);
									def = ($(this).is(":checked")) ? 1 : 0;
									eval("that.configDraft."+cinfo.k+"."+cinfo.att+"= def");
									
								});
								break;
							//input type = text
							default:		
								//define def
								def = (eval(v.obj+"."+att)!=undefined) ? eval(v.obj+"."+att) : "";
								//dom def
								$(el).val(that.unHtmlEntities(def));
								//add listener
								$(el).on("keyup change", function(){
									cinfo = that.getConfigInfo(this);
									def = that.htmlEntities($(this).val());
									eval("that.configDraft."+cinfo.k+"."+cinfo.att+"= def");
									
								});
								break;
						}
						//set obj
						eval("that.configDraft."+k+"."+att+"= def");
					});
					
				});
				
				//setup switcher
				that.configDraft.data.sort.mode = that.data.sort.mode;
				that.configDraft.data.sort.mode = (that.configDraft.data.sort.mode == undefined) ? "grid" : that.configDraft.data.sort.mode ;
				opts = {
					grid : {
						value : "grid",
						text : "<span class='glyphicon glyphicon-th-large has_tooltip' title='Grid'></span>"
					},
					list : {
						value : "list",
						text : "<span class='glyphicon glyphicon-align-justify has_tooltip' title='List'></span>"
					}
				};
				opts[that.configDraft.data.sort.mode].default = true;	
				mode = that.switcher('switcher_mode', {
					options : opts,
					onChange : function(val){
						//change data file
						that.configDraft.data.sort.mode = val;
					}
				});
				$("#config_sortMode").append(mode);
			
				//set up add user button
				$("#config_addUser").on("click", function(){
					userTableEl = "#config_userTable";
					//if in edit mode, cancel it
					if (that.editMode==1){
						$("tr.userRow.editMode").find(".user_cancelEdit").trigger("click");
					}
					//add new user
					tr = that._userRow("new_user","*******");
					tr.addClass("newUser");
					$(userTableEl).append(tr);
					//toggle edit
					$("tr.userRow.newUser").find(".user_toggleEdit").trigger("click");
				});
				
				//handle users panel
				that.configDraft.config.users = $.extend({}, that.config.users);
				userTableEl = "#config_userTable";
				if (that.isEmpty(that.configDraft.config.users))
					$(userTableEl).hide();
				$.each(that.configDraft.config.users, function(k,v){
					tr = that._userRow(k,"*******");
					$(userTableEl).append(tr);
				})
				
				that.original = $.extend(true, {}, that.configDraft);
				//check if file is up to date..
				setInterval(function(){
					if (json_encode(that.configDraft)==json_encode(that.original)){
						that.toggleButton("#config_save", 0, "Up To Date");
						that.unsavedChanges = 0;
					}else{
						that.toggleButton("#config_save", 1, "Save");
						that.unsavedChanges = 1;
					}
				},50);
				
				//options
				optNew = $("<div class='fakeLink'><span class='glyphicon glyphicon-file'></span> New</div>");
				optNew.on("click", function(){
					that.modal({
						subject : "New File",
						description : "<div class='successBox'></div><div class='errorBox'></div>This will create a brand new file with default template settings. You can load your current file back at any time.<p>Ex. <i>new_file.json</i></p><div><input id='config_new_fileInput' type='text' class='form-control halfWidth' placeholder='New Filename'></div> ",
						buttons : {
							_New : {
								type : "custom",
								clicked : function(me){
									$(me.el).find(".errorBox,.successBox").hide();
									filename = $("#config_new_fileInput").val();
									defaultTemplate = "defaults/default.json";
									//preval
									if (that.trim(filename)=="")
										$(me.el).find(".errorBox").html("Filename must be filled.").show();
									else if (!filename.match(/^[A-Za-z0-9_.]+$/gi))
										$(me.el).find(".errorBox").html("Filename must be made up of only alphanumeric, dots, and underscores.").show();
									else
										that.saveFile("xxx", {
											action : "srcManager",
											data : { action : "saveAs", f : defaultTemplate, saveAs : filename },
											onSuccess : function(respObj){
												$(me.el).find(".successBox").html("Success. Now reloading page to make changes.").show();
												window.location.reload();
											},
											onError : function(msg){
												$(me.el).find(".errorBox").html(msg).show();
											}
										});
									//me.close();
								}
							},
							Cancel : {
								type : "cancel"
							}
						}
					});
				});
				
				
				//options
				optDelete = $("<div class='fakeLink'><span class='glyphicon glyphicon-remove'></span> Delete</div>");
				optDelete.on("click", function(){
					that.modal({
						subject : "Delete",
						description : "<div class='successBox'></div><div class='errorBox'></div><p>Are you sure you want to delete <b>"+that.configDraft.config.src+"</b>.</p><b>Fallback file: </b><div id='srcManager_fallbackFile'></div></p> ",
						buttons : {
							_Delete : {
								type : "custom",
								clicked : function(me){
									$(me.el).find(".errorBox,.successBox").hide();
									filename = $("#srcManager_fallbackFile select").val();
									that.saveFile("xxx", {
										action : "srcManager",
										data : { action : "delete", f : that.configDraft.config.src, fallback : filename },
										onSuccess : function(respObj){
											$(me.el).find(".successBox").html("Success. Now reloading page to make changes.").show();
											window.location.reload();
										},
										onError : function(msg){
											$(me.el).find(".errorBox").html(msg).show();
										}
									});
								}
							},
							Cancel : {
								type : "cancel"
							}
						}
					});
					that.saveFile("xxx", {
						action : "getFileList",
						data : {folder : "/"},
						noSuccessIndicator : 1,
						onSuccess : function(respObj){
							selInput = $("<select></select>");
							$.each(respObj.data, function(k,v){
								if (that.configDraft.config.src!=v)
								selInput.append("<option value='"+v+"'>"+v+"</option>");
								
							});
							$("#srcManager_fallbackFile").append(selInput);
						},
						onError : function(msg){
							$("#srcManager_fallbackFile").html("<i>"+msg+"</i>");
						}
						
					});
				});
				

				
				load =  $("<div class='fakeLink' id='srcManager_loadFile'><span class='glyphicon glyphicon-folder-open'></span> Load</div>");
				load.on("click", function(e){
					e.stopPropagation();
					that.trBackup = $(this).clone(true,true);
					$(this).off("click");
					$(this).on("click", function(e){e.stopPropagation()});
					$(this).addClass("noHover");
					that.saveFile("xxx", {
						action : "getFileList",
						data : {folder : "/"},
						noSuccessIndicator : 1,
						onSuccess : function(respObj){
							selInput = $("<select></select>");
							$.each(respObj.data, function(k,v){
								selInput.append("<option value='"+v+"'>"+v+"</option>");
								
							});
							loadButton = $("<div class='button'>Load</div>");
							loadButton.on("click", function(){
								$("#srcManager_loadFile").find(".errorBox,.successBox").hide();
								that.saveFile("xxx", {
											action : "srcManager",
											data : { action : "load", f : $("#srcManager_loadFile").find("select").val() },
											onSuccess : function(respObj){
												$("#srcManager_loadFile").find(".successBox").html("Success. Now reloading page to make changes.").show();
												window.location.reload();
											},
											onError : function(msg){
												$("#srcManager_loadFile").find(".errorBox").html(msg).show();
											}
										});
							});
							$("#srcManager_loadFile").find(".fileList_wpr").append(selInput);
							$("#srcManager_loadFile").find("fieldset .button").before(loadButton);
						},
						onError : function(msg){
							$("#srcManager_loadFile").find(".fileList_wpr").html("<i>"+msg+"</i>");
						}
						
					});

					fieldset = $("<fieldset><legend>Load File</legend><div class='errorBox'></div><div class='successBox'></div><div class='fileList_wpr'></div></fieldset>");
					
					cancelButton = $("<div class='button'>Cancel</div>");
					
					cancelButton.on("click", function(e){
						e.stopPropagation();
						link = $(this).closest(".fakeLink");
						link.hide().replaceWith(that.trBackup).show("slow");
					});
					fieldset.append(cancelButton);
					$(this).empty().hide().append(fieldset).show("slow");
				});
				
				saveAs = $("<div class='fakeLink'><span class='glyphicon glyphicon-floppy-disk'></span> Save As..</div>");
				saveAs.on("click", function(){
					that.modal({
						subject : "Save As..",
						description : "<div class='successBox'></div><div class='errorBox'></div>This will save your current project as a duplicate file, under a new desired name<p>Ex. <i>new_file.json</i></p><div><input id='config_saveas_fileInput' type='text' class='form-control halfWidth' placeholder='New Filename'></div> ",
						buttons : {
							Save_As : {
								type : "custom",
								clicked : function(me){
									$(me.el).find(".errorBox,.successBox").hide();
									filename = $("#config_saveas_fileInput").val();
									//preval
									if (that.trim(filename)=="")
										$(me.el).find(".errorBox").html("Filename must be filled.").show();
									else if (!filename.match(/^[A-Za-z0-9_.]+$/gi))
										$(me.el).find(".errorBox").html("Filename must be made up of only alphanumeric, dots, and underscores.").show();
									else
										that.saveFile("xxx", {
											action : "srcManager",
											data : { action : "saveAs", f : that.src, saveAs : filename },
											onSuccess : function(respObj){
												$(me.el).find(".successBox").html("Success. Now reloading page to make changes.").show();
												window.location.reload();
											},
											onError : function(msg){
												$(me.el).find(".errorBox").html(msg).show();
											}
										});
									//me.close();
								}
							},
							Cancel : {
								type : "cancel"
							}
						}
					});
				});
				backUp =  $("<div class='fakeLink'><span class='glyphicon glyphicon-hdd'></span> Backup</div>");
				backUp.on("click", function(){
						that.saveFile(that.src, {
							action : "srcManager",
							data : { action : "backup", f: that.src}
						});
				});
				
				templateUtils =  $("<div class='fakeLink'><span class='glyphicon glyphicon-sort'></span> Import / Export</div>");
				templateUtils.on("click", function(){
					that.modal({
						subject : "Import / Export Templates",
						description : "<p>Here you will be able to Import / Export  templates that you have made for your manager projects. <b>This feature is not implemented yet, but will be done so in a future release.</b></p>",
						buttons : {
							Ok : {
								type : "cancel"
							}
						}
					});
				});
				
				downloadIt = $("<a><span class='glyphicon glyphicon-download-alt'></span> Download</a>");
				downloadIt.attr("href", "data:"+encodeURI(JSON.stringify(that.data, null, 4)));
				downloadIt.attr("download",that.configDraft.config.src);
				
				this.dropdown("#config_changeSrc", "src_dd", [
				
					  '<li role="presentation" class="dropdown-header">File Management</li>',
						optNew,
						saveAs,
						backUp,
						load,
						optDelete,
					  '<li role="presentation" class="divider"></li>',
					   '<li role="presentation" class="dropdown-header">Template Utils</li>',
					   templateUtils,
					      '<li role="presentation" class="divider"></li>',
					   '<li role="presentation" class="dropdown-header">Local Utils</li>',
					   downloadIt,
				], {
					up : false,
					menuCSS : {
						"margin-left" : ($("#config_changeSrc").width()-30)+"px",
						"top" : "30px"
					}
				});
				purge = $("<div class='fakeLink has_tooltip' title='Use this tool to remove media not linked to any current projects. This might happen if you exit out of a project without saving changes.'><span class='glyphicon glyphicon-flash'></span> Purge</div>");
				purge.on("click", function(){

					el = $('#config_mediaUtils_content');
					el.empty();
					el.append("<h5>"+
								"Purge Files"+
								"<span class='glyphicon glyphicon-question-sign hint has_tooltip' title='Below are all of the files that are not referenced in any of your projects (including backups, defaults, and other data files). To preserve space and clean up your media folder(s), you can purge them.<br><br> You can choose to remove these files individually, or all at once by clicking the <span class=\"glyphicon glyphicon-remove\"></span> icon.'></span>"+
								" <span style='font-size:10px' id='config_purge_analysisInfo'></span>"+
							"</h5>"+
							"<div class='errorBox'></div><div class='successBox'></div>"+
							"<div class='loadingBox' style='display:block'><div class='loading'></div> Analyzing. Please Wait..</div>").slideDown();
					that.saveFile(that.src,{
						"action" : "getUnlinkedFiles",
						noSuccessIndicator: 1,
						onSuccess : function(respObj){
							//update analysis
							$("#config_purge_analysisInfo").html("(Data Files: "+Object.keys(respObj.data.dataFiles).length+
							"<span class='glyphicon glyphicon-question-sign hint has_tooltip' title='Below are files we scanned. "+that._simpleList(respObj.data.dataFiles)+"'></span>"+
							", Media Folders: "+respObj.data.mediaFolders.length+
							"<span class='glyphicon glyphicon-question-sign hint has_tooltip' title='Below are media folders we scanned. "+that._simpleList(respObj.data.mediaFolders)+"'></span>"+
							")");
							//navigate through unlinked files
							if (!$.isEmptyObject(respObj.data.unlinked)){
								table = $("<table class='cmcm_table'></table>");
								table.append("<th colspan=2 style='text-align: right'> Unlinked Files (<span id='config_unlinkCount'>"+Object.keys(respObj.data.unlinked).length+"</span>)</th>");
								
								removeAll = $("<span class='glyphicon glyphicon-remove icon has_tooltip' title='Remove All'></span>");
								removeAll.on("click", function(){
										el.find(".errorBox, .successBox").hide();
										files = [];
										el.find(".unlinked_file").each(function(){
											files.push($(this).attr("data-file"));
										});
										that._removeUnlinkedFiles(el, files);
								});
								
								table.find("th").prepend(removeAll);
								
								$.each(respObj.data.unlinked, function(k,v){
									tr = $("<tr></tr>");
									remove = $("<span class='glyphicon glyphicon-remove icon has_tooltip' title='Remove'></span>");
									remove.on("click", function(){
										el.find(".errorBox, .successBox").hide();
										file = $(this).closest("tr").find(".unlinked_file").attr("data-file");
										that._removeUnlinkedFiles(el, [file]);
									});
									tr.append("<td>"+k+"</td><td class='unlinked_file' data-file='"+v+"'>"+v+"</td><td><div class='actionPanel'></div></td>").find(".actionPanel").append(remove);
									tr.on("mouseover", function(){
										$(this).find(".actionPanel").show();
									});
									tr.on("mouseout", function(){
										$(this).find(".actionPanel").hide();
									});
									table.append(tr);
								});
								el.append(table);
							}else{
								el.append("<div><i>No Unlinked Files :)</i></div>");
							}
							that.updateTooltip();
						},
						onError : function(msg){
							el.find(".errorBox").html(msg).show();
						},
						onDone : function(){
							el.find(".loadingBox").hide();
						}
					});
				});
				consolidate = $( "<div class='fakeLink has_tooltip' title='Move all media into your current media folder that is not there already. This may be desired if the media folder is ever changed.'><span class='glyphicon glyphicon-magnet'></span> Consolidate</div>");
				consolidate.on("click", function(){

					el = $('#config_mediaUtils_content');
					el.empty();
					el.append("<h5>Consolidate Files <span class='glyphicon glyphicon-question-sign hint has_tooltip' title='Below are all of the files that are being referenced in your projects, but not in your current media folder: "+that.data.mediaFolder+". You can choose to move these files individually, or all at once by clicking the <span class=\"glyphicon glyphicon-log-in\"></span> icon.'></span></h5><div class='errorBox'></div><div class='successBox'></div><div class='loadingBox' style='display:block'><div class='loading'></div> Analyzing. Please Wait..</div>").slideDown();
					
					
					that.saveFile(that.src,{
						"action" : "getUnconsolidatedFiles",
						noSuccessIndicator: 1,
						onSuccess : function(respObj){
						//	el.find(".successBox").html("<pre>"+JSON.stringify(respObj,null, 4)+"</pre>").show();
							if (!$.isEmptyObject(respObj.data)){
								table = $("<table class='cmcm_table'></table>");
								table.append("<th colspan=2 style='text-align: right'> UnConsolidated Files (<span id='config_consolCount'>"+Object.keys(respObj.data).length+"</span>)</th>");
								consolAll = $("<span class='glyphicon glyphicon-log-in icon has_tooltip' title='Move All to current media folder: "+that.data.mediaFolder+"'></span>");
								consolAll.on("click", function(){
									el.find(".errorBox, .successBox").hide();
									files = [];
									el.find(".uconsol_file").each(function(){
										files.push($(this).attr("data-file"));
									});
									that._consolFiles(el, that.src);
								});
								table.find("th").prepend(consolAll);
								
								$.each(respObj.data, function(k,v){
									tr = $("<tr></tr>");
									consol = $("<span class='glyphicon glyphicon-log-in icon has_tooltip' title='Move to current Media folder: "+that.data.mediaFolder+"'></span>");
									consol.on("click", function(){
										el.find(".errorBox, .successBox").hide();
										td =  $(this).closest("tr").find(".unconsol_file");
										file = {
											file : td.attr("data-file"),
											projId : td.attr("data-projId"),
											mediaId :  td.attr("data-mediaId"),
											key :  td.attr("data-key")
										};
										that._consolFiles(el, file);
									
									});
									tr.append("<td style='font-size: 10px'>"+v.projId+" [Media #"+v.mediaIndex+", attr: "+v.key+"]</td><td class='unconsol_file'   data-mediaId='"+v.mediaId+"'  data-projId='"+v.projId+"'  data-key='"+v.key+"' data-file='"+v.file+"'>"+v.file+"</td><td><div class='actionPanel'></div></td>").find(".actionPanel").append(consol);
									tr.on("mouseover", function(){
										$(this).find(".actionPanel").show();
									});
									tr.on("mouseout", function(){
										$(this).find(".actionPanel").hide();
									});
									table.append(tr);
								});
								el.append(table);
							}else{
								el.append("<div><i>No Unconsolidated files :)</i></div>");
							}
							that.updateTooltip();
						},
						onError : function(msg){
							el.find(".errorBox").html(msg).show();
						},
						onDone : function(){
							el.find(".loadingBox").hide();
						}
					});
					that.updateTooltip();
				});
				
				 regenerateThumbs = $("<div class='fakeLink has_tooltip' title='If you changed your thumbnail size, use this tool to regenerate existing thumbnails to your new dimensions and settings.'><span class='glyphicon glyphicon-refresh'></span> Regenerate Thumbs</div>");
				 regenerateThumbs.on("click", function(){
					that.modal({
						subject : "Regenerate Thumbnails",
						description :	"<div class='loadingBox'><div class='loading'></div> Regenerating thumbs.."+
											"<div id='config_regenThumbsProgress' style='display:none'>"+
												"<div class='progress_wpr relative'><div class='progress_bar'></div></div>"+
												"<div class='prog_msg'>Please Wait..</div>"+
											"</div>"+
										"</div>"+
										"<div class='errorBox'></div><div class='successBox'></div><p><i style='font-size:10px'>Thumb settings that will be applied:</i></p><p>max width:<b>"+that.data.thumb.max_width+"</b><br>max height:<b>"+that.data.thumb.max_height+"</b><br>crop? <b>"+(that.data.thumb.crop==1 ? "yes" : "no")+"</b></p><p>If you changed your thumbnail size, use this tool to regenerate existing thumbnails to your new dimensions and settings. THis may take a long time depending on how many projects and media files you have. Just be prepared for that.</p>",
						buttons : {
							Regenerate : {
								type : "custom",
								clicked : function(me){
									if (!$(me.button).hasClass("disabled")){
										//reset
										that.toggleButton(me.button, 0, "Working..");
										$(me.el).find(".errorBox,.successBox,.loadingBox").hide();
										$(me.el).find(".loadingBox").show();
										$(me.el).find(".progress_bar").css("width", "0%");
										$(me.el).find(".progress_msg").html("Please Wait..");
										//initiate stream
										
										that.eventStream(that.src, {
											action : "regenerateThumbs",
											onError : function(msg){
												that.es.close();
												$(me.el).find(".loadingBox").hide();
												that.toggleButton(me.button, 1, "Regenerate");
												msg = (msg!=undefined) ? ": ".msg : "";
												$(me.el).find(".errorBox").html("An error Occurred"+msg).show();
											},
											onOpen : function(){
												$(me.el).find(".progress_msg").html("Connected.");
											},
											onMessage : function(respObj){
												$("#config_regenThumbsProgress").show();
												//respObj ; @status=0,1 or,2 / @msg = optional / @data = optional
												switch (parseInt(respObj.status)){
													case 0: //error
														that.es.close();
														$(me.el).find(".loadingBox").hide();
														that.toggleButton(me.button, 1, "Regenerate");
														$(me.el).find(".errorBox").html("An error Occurred: ["+respObj.msg+"]").show();
														break;
													case 1: //progress
														$(me.el).find(".progress_bar").css("width", Math.round((respObj.data.current/respObj.data.total)*100)+"%");
														$(me.el).find(".prog_msg").html(respObj.msg+" ("+respObj.data.current+"/"+respObj.data.total+"): "+respObj.data.file+"..");
														break;
													case 2: //complete
														that.es.close();
														$(me.el).find(".loadingBox").hide();
														that.toggleButton(me.button, 1, "Regenerate");
														$(me.el).find(".successBox").html("Done!").show();
														setTimeout(function(){me.close()}, 1000);
														break;
												}
											}
										});
									} //<--end if not disabled
								}	
							},
							Cancel : {
								type : "cancel"
							}
						} 
					});
				 });
				this.dropdown("#config_mediaUtils", "mediaUtils_dd", [
				
					  '<li role="presentation" class="dropdown-header" >Media Utils</li>',
					  consolidate,
					  purge,
					  regenerateThumbs
			
				], {
					up : false,
					menuCSS : {
						"margin-left" : "63px",
						"width" : "200px",
						"top" : "30px"
					}
				});

				
				that.updateTooltip();
			
				
			},
			formatProject : function(){
				//NEED MORE EFFICIENT
				var tr;
				var that = this;
				var parentEl = "#proj_info";
				
				var mode = (this.$_GET('id')) ? this.$_GET('id') : "new";
				
				if (mode=="new"){
					//<---NEW PROJECT---->
					//lets populate project template first
					$("#project_title").html("Add Project");
					$.each(this.data.template.project, function(k,v){
						if (!v.hidden || v.hidden<.5){
							//default values
							def = "";
							
							def = (that.attributeTypes[v.type].def!=undefined) ? that.attributeTypes[v.type].def() : def;
							def = (that.data.template.project[k].def!=undefined) ? that.data.template.project[k].def : def;
							that.draft[k] = def;
							tr = that._projectRow(k,def);
						
							//default to data obj
							that.edit(k,def,that.draft, 'v');
							$(parentEl).append(tr);
						}
								
					});
					//blank media
					this.draft.media = {};
				}else{
					
					
					//load existing
					tmpProj = this.getById(mode);
					//dont exist? redirect to index
					if (!tmpProj)
						window.location.href = 'index.php';
					
					$("#project_title").html(tmpProj.title);
					//need to go through basic template first
					$.each(this.data.template.project, function(k,v){
						
						if (tmpProj[k]!=undefined){
							that.draft[k] = tmpProj[k];
							delete tmpProj[k];
							
						}else{
							def = "";
							def = (that.attributeTypes[v.type].def!=undefined) ? that.attributeTypes[v.type].def() : def;
							def = (that.data.template.project[k].def!=undefined) ? that.data.template.project[k].def : def;
							that.draft[k] = def;
						}
						//add to dom
						if (!v.hidden || v.hidden<1){
							tr = that._projectRow(k,that.draft[k]);
							$(parentEl).append(tr);
						}
						
					});
					//non template stuff at the end
					$.each(tmpProj, function(k,v){
						if (k!="media"){
							that.draft[k] = v;
							//add to dom
							if (!v.hidden){
								tr = that._projectRow(k,that.draft[k]);
								$(parentEl).append(tr);
							}
						}
					});
					//add media..and add to dom
					//this.draft.media = tmpProj.media;
					that.draft.media = {};
					$.each(tmpProj.media, function(mediaId, media){
						that.draft.media[mediaId] = {};
						$.each(that.data.template.media, function(k,v){
						
							if (media[k]){
								that.draft.media[mediaId][k] = media[k];
								delete tmpProj.media[mediaId][k];
							}else{
								
								that.draft.media[mediaId][k] = (v.def!=undefined) ? v.def : "";
							}
						});
						//add extras

						$.each(tmpProj.media[mediaId], function(k,v){
							that.draft.media[mediaId][k] = v;
						});
					});
					
					//EXISTING MEDIA
					$.each(that.draft.media, function(mediaId,media){
						wpr = $("<div class='media_wpr' data-id=''></div>");
						wpr.attr("data-id", mediaId);
						switch (media.type){
							case "image":
								//wpr.append("<div class='media_crop'><img></div>");
								//wpr.find('img').attr("src", media.src);
								wpr.append(that.mediaTypes.image.preview(media));
								break;
							case "sound":
								wpr.append(that.mediaTypes.sound.preview(media));
								break;
							case "video":
								wpr.append(that.mediaTypes.video.preview(media));
								break;
						}
						
						$("#media_files").append(wpr);
						//add all fancy settings...
						that._mediaFormat(mediaId);
						
						//not visible
						if (media.visible==0)
							wpr.append("<div class='shadow'><span data-toggle='dropdown' class='glyphicon glyphicon-eye-close  white has_tooltip notVisible' title='Not Visible'></span></div>");
					});
					
				}
					that.original = $.extend(true, {}, that.draft);
					//check if file is up to date..
					setInterval(function(){
						if (json_encode(that.draft)==json_encode(that.original)){
							that.toggleButton("#project_save", 0, (mode=='new') ? "Nothing to Save" : "Up To Date");
							that.unsavedChanges = 0;
						}else{
							that.toggleButton("#project_save", 1, "Save");
							that.unsavedChanges = 1;
						}
					},50);
					
				$(document).on("dragover", function(e){
					if (!$("body").find('.black_bg').length){
						backdrop = $("<div class='black_bg'></div>");
						backdrop.hide();
						dropMsg = $("<div class='drop_msg'>Drop files to upload</div>");
						backdrop.append(dropMsg);
						$("body").append(backdrop);
						backdrop.fadeIn(500);
					}
					dropMsg = $("body").find('.black_bg').find(".drop_msg");
					buffer = {
						x : -60,
						y : 30
					}
					dropMsg.css({
						position: "absolute",
						top : window.event.pageY-$(document).scrollTop()+buffer.y+"px",
						left : window.event.pageX-buffer.x+"px"
					});
				});
				$(document).on("dragleave", function(e){
					 if( window.event.pageX == 0 || window.event.pageY == 0 ) {
						if ($("body").find('.black_bg').length)
							 $("body").find(".black_bg").remove();
					}
				}); 
				$(document).on("drop", function(e){
						if ($("body").find('.black_bg').length)
							 $("body").find(".black_bg").remove();
				}); 
	
			},
			toggleButton : function(sel, onOrOff, text){
				if (text!=undefined)
					$(sel).html(text);
				switch (onOrOff){
					case 0:
						if (!$(sel).hasClass("disabled"))
							$(sel).addClass("disabled");
						break;
					case 1:
						if ($(sel).hasClass("disabled"))
							$(sel).removeClass("disabled");
						break;
				}
			},
			formatTemplateTable : function(data, parentEl){
				var tr;
				var that = this;
				headerPanel = $("<tr>"+
					"<th>Name</th>"+
					"<th>Data Type</th>"+
					"<th></th>"+
					"</tr>");
				$(parentEl).append(headerPanel);
				
				//lets populate project template first
				$.each(data, function(k,v){
				
					tr = that._templateRow(parentEl, k,v);
					$(parentEl).append(tr);
							
				});
				$(parentEl).find("tr").parent().sortable({ 
					items: "> tr:has(td):not(.protected)" ,
					update : function(e,ui){
						that.resortTemplate(parentEl);
					}
				});

			},
			sortProjectsBy : function(str,opts){
				if (this.data.sort.mode=="list"){
					$(".img_grid").addClass("listMode");
					//$(".proj_title").show();
				}else{
					$(".img_grid").removeClass("listMode");
					//$(".proj_title").hide();
				}
				opts = (opts!=undefined) ? opts : {};
				ascOrDesc = (this.data.sort.direction=="ascending") ? "desc" : "asc";
				defaultOpts = {
					skipAnimation : false
				};
				$.each(defaultOpts, function(k,v){
					opts[k] =  (opts[k]!=undefined) ? opts[k] : defaultOpts[k];
				});
				
				if ($(".wpr_group").hasClass('ui-sortable'))
					$(".wpr_group").sortable("destroy");
				if (str==undefined || str==""){
					$(".img_grid").html(" ");
					this.renderProjectsGrid();
					return false;
				}
				var that = this;
				arr = {};
				$( ".group_header" ).remove();
				//destory wpr_group
				if ($(".wpr_group").length>0)
					$(".img_grid").find( ".img_wpr" ).unwrap();
				$(".img_grid").find(".img_wpr").each(function(){
					key = $(this).attr("data-key");
					$(this).attr("data-cat", (that.data.projects[key][str]!=undefined) ? that.data.projects[key][str] : "undefined");
					if (arr[$(this).attr("data-cat")]==undefined){
						arr[$(this).attr("data-cat")] = 1;
					}
				});
				
					//old was mixitup , new is 
					$('.img_grid').mixitup({
						layoutMode: that.data.sort.mode,
						transitionSpeed: (opts.skipAnimation) ? opts.skipAnimation : 600,
						onMixLoad : function(){
							//if skipping animation..then use existing order
							sortArr = (opts.skipAnimation) ?  ['custom', $(".img_grid").find(".img_wpr")] : ['data-cat', ascOrDesc];
							$('.img_grid').mixitup('sort',sortArr);
						
						},
						onMixEnd : function(){
								//needs to get current list
								
								$( ".group_header" ).remove();
								//destory wpr_group
								if ($(".wpr_group").length>0)
									$(".img_grid").find( ".img_wpr" ).unwrap();
								$.each(arr, function(k,v){
									//handle header
									header = $("<span class='group_header' style='display:none'></span>");
									header.append(that._getSortHeader(str, k));
									$("[data-cat='"+k+"']:first").before(header);
									//wrap all projects in group divs
									$("[data-cat='"+k+"']").wrapAll("<div class='wpr_group' data-group='"+k+"'></div>");
								});
									$(".wpr_group").sortable(that._sortableInit());
								
									$(".mix_all").removeClass("mix_all");
									$(".group_header").show((opts.skipAnimation) ? "normal" : "slow", function(){
										$(this).css({
											display : "block",
											margin : "20px 0 20px 0",
											borderBottom : "1px solid #c0c0c0"
										});
									}).css("display","inline-block");
								
									//reset on clicks
									$(".img_wpr").each(function(){
										that.setupProject($(this));
										$(this).find("a").append("<div class='proj_title'>"+proj.title+"</div><div class='proj_date'>"+proj.added+"</div>");
									});
									
									that.resortProjects(".img_grid");
						
						}
					});

				
				
			},
			renderProjectsGrid : function(){
				var that = this;
				//set sort if undefined
				if (that.data.sort==undefined)
					that.data.sort = {by : "", direction : "", mode : ""};
				if (that.data.sort.mode=="list")
					$(".img_grid").addClass("listMode");
				//JUST BOXES	
				if (this.trim(this.data.sort.by)==""){
					group_wpr = $("<div class='wpr_group'></div>");
					$(".img_grid").empty().append(group_wpr);
					$.each(this.data.projects, function(k,project){
						wpr = that._projectSquare(k, project);
						group_wpr.append(wpr);
		
					});
				//PRE SORT pre sort if exists
				}else{
					//that.sortProjectsBy(this.data.sort.by, {skipAnimation : 1});
					that.formatSortedProjects(this.data.sort.by);
					
				}
				$(".wpr_group").sortable(that._sortableInit());
				
				//no projects
				if ($.isEmptyObject(this.data.projects))
					$(".img_grid").append("<i>You haven't added any projects yet.</i>");
							
			},
			//returns project square element
			_projectSquare : function(projId, proj){
				wpr = $("<div class='img_wpr mix'><a href='project.php?id="+proj.id+"' class='noLink'></a></div>");
				if (this.data.sort.mode=="grid")
					wpr.addClass("has_tooltip");
				wpr.attr("title", proj.title);
				wpr.attr("id", proj.id);
				wpr.attr("data-key", projId);
				wpr.attr("data-coverImage", proj.coverImage);
				//add toolset,preview, and hoverovers
				this.setupProject(wpr);
				wpr.find("a").append("<div class='proj_title'>"+proj.title+"</div><div class='proj_date'>"+proj.added+"</div>");
				return wpr;
			},
			_getSortHeader : function(sortBy, valueKey){
				///make more descriptive the header
				type = false;
				if (this.data.template.project[sortBy]!=undefined)
					type = this.data.template.project[sortBy].type;
					if (valueKey==undefined || valueKey=="undefined")
						return "undefined";
					switch (type){
						case "bool":
							value = (parseInt(valueKey) == 0) ? "Not "+sortBy : sortBy ;
							break;
						default:
							value = valueKey;
							break;	
					}
					return value;
			},
			formatSortedProjects : function(sortBy){
				var that = this;
				values = this.groupProjectsBy(sortBy, this.data.projects);

				$.each(values, function(valueKey, groupObj){
					valueKey = valueKey.replace(/grouped_/gi, "");
					header = $("<div class='group_header lineUnderneath'></div>");
					header.append(that._getSortHeader(sortBy, valueKey));
					wpr_group = $("<div class='wpr_group'></div>");
					wpr_group.attr("data-group", valueKey);
					$.each(groupObj, function(projId, proj){
						wpr = that._projectSquare(projId, proj);
						wpr_group.append(wpr);
					});
							
					$(".img_grid").append(header).append(wpr_group);
				});
			},
			//obj that contains initial options for jquery sortable..for wpr_group
			_sortableInit : function(){
					var that = this;
					return {
					 connectWith: ".wpr_group",
					 dropOnEmpty : 1,
					 forceHelperSize: true,
					 receive : function(e,ui){
						 //change object value for sortby key
					 	//UPDATE that objects attributes
					 	newValue = $(ui.item).closest(".wpr_group").attr("data-group");
					 	data = {};
						data[$(ui.item).attr("data-key")] = {};
						data[$(ui.item).attr("data-key")][that.data.sort.by] =  newValue;
						that.saveFile(that.src, {
							action : "editAttributes",
							data : data,
							onError : function(msg){
								alert(msg);
							}
						});
						
					 },
					 update : function(e, ui){
						 //resort things..make sure only one resort action is done
						if (ui.sender==undefined){
							that.resortProjects(".img_grid");
						}
					 }
				 };
			},
			//returns dropdowns @which = type 
			_sortOptions : function (which){
				var that = this;
				var res = $("<div></div>");
				//sortable options filter
				sortFilter  = {
					ignoreList : ["id","cleanUrl","coverImage", "title"],
					okTypes : ["string", "timestamp", "int", "choice", "bool"]
				}
				
				if (that.data.sort==undefined)
					that.data.sort = {by : "", direction : ""};
					
				//what kind of options
				switch (which){
					case "by":
						//manual sort option
						opt = $("<option></option>");
						opt.append("--");
						opt.attr("value", "");
						if (that.data.sort.by=="")
							opt.prop("selected", true);
						res.append(opt);
						//other sort options
						$.each(this.data.template.project, function(k,v){
							//ignorelist filter
							if ($.inArray(k, sortFilter.ignoreList)==-1){	
								if ($.inArray(v.type, sortFilter.okTypes)!=-1){
									opt = $("<option></option>");
									opt.append(k);
									opt.attr("value", k);
									if (that.data.sort.by==k)
										opt.attr("selected", true);
		
									res.append(opt);	
								}
							}
							
						});
						break;
					case "direction":
						directionOpts = ["ascending", "descending"];
						//other sort option
						$.each(directionOpts, function(index,v){
							//ignorelist filter
							opt = $("<option></option>");
							opt.append(v);
							opt.attr("value", v);
							if (that.data.sort.direction==v)
								opt.attr("selected", true);
							res.append(opt);	
						});
						break;
						
				}
				return res.html();	
			},
			//setup sortby dropdown
			setupSortBy : function(){
				var that = this;
				select = $("<div class='ddItem'><select style='width: 100%' class='form_control'>"+
					"</select></div>");
				//attach options
				//for firefox
				select.find("select").on("click", function(e){e.stopPropagation()});
				select.find("select").append(this._sortOptions('by'));
				select.find("select").on("change", function(){
						//change data file
						that.data.sort.by = this.value;
						//grab current sort info..
						data = {
							data : {
								sort : $.extend({}, that.data.sort)
							}
						};
						//change by and save
						data.data.sort.by = this.value;
						that.saveFile(that.src, {
							action : "editConfig",
							data : data
						});
						that.sortProjectsBy(this.value);
				});
				
				
				direction = $("<div class='ddItem'>"+
					"<select style='width: 100%' class='form_control' id='index_sort_ascdesc'>"+
					"</select><br><br>"+
				"</div>");
				direction.find("select").append(this._sortOptions('direction'));
				//for firefox
				direction.find("select").on("click", function(e){e.stopPropagation()});
				direction.find("select").on("change", function(){
						//change data file
						that.data.sort.direction = this.value;
						//grab current sort info..
						data = {
							data : {
								sort : $.extend({}, that.data.sort)
							}
						};
						//change by and save
						that.data.sort.direction = this.value;
						that.saveFile(that.src, {
							action : "editConfig",
							data : data
						});
						that.sortProjectsBy(data.data.sort.by);
				});
				
				opts = {
						grid : {
							value : "grid",
							text : "<span class='glyphicon glyphicon-th-large has_tooltip' title='Grid'></span>"
						},
						list : {
							value : "list",
							text : "<span class='glyphicon glyphicon-align-justify has_tooltip' title='List'></span>"
						}
					};
				that.data.sort.mode = (that.data.sort.mode == undefined) ? "grid" : that.data.sort.mode ;
				opts[that.data.sort.mode].default = true;	
				mode = that.switcher('switcher_mode', {
					options : opts,
					onChange : function(val){
						//change data file
						that.data.sort.mode = val;
						//grab current sort info..
						data = {
							data : {
								sort : $.extend({}, that.data.sort)
							}
						};
						that.saveFile(that.src, {
							action : "editConfig",
							data : data
						});
						that.sortProjectsBy();
					}
				});
				//mode.addClass('noCloseOnClick');
				that.dropdown("#index_opts", "dd_index_opts", [
				'<li role="presentation" class="dropdown-header">Mode:</li>',
				mode,
				'<li role="presentation" class="divider"></li>',
				'<li role="presentation" class="dropdown-header">Sort By:</li>',
				select,
				 '<li role="presentation" class="divider"></li>',
				 '<li role="presentation" class="dropdown-header">Direction:</li>',
				 direction
				 
				], {
					up : false,
					menuCSS : {
						"margin-left": "-8px",
						"margin-bottom": "-12px"
					}
				});
	
			},
			setupProject : function(wpr){
					wpr.find("a").empty();
					var that = this;
					proj = that.getById(wpr.attr("id"));
					mediaId = proj.coverImage;
					//reset preview square if a coverImage is set
					if (mediaId!=undefined  && mediaId!=0){
						media = proj.media[mediaId];
						wpr.find("a").append(that.mediaTypes[media.type].preview(media));
					}else{
						//get acronym...
						if (proj.cleanUrl!=undefined){
							words = proj.cleanUrl.split("-");
							len = (words.length<3) ? words.length : 3;
							acro = "";
							for (x=0; x<len; x++){
								acro += words[x][0].toUpperCase();
							}
						}else{
							acro = proj.title.substring(0,3);
						}
						wpr.find("a").append("<div class='noImage'><b style='display:block;margin-top:15px'>"+acro+"</b></div>");
					}
					wpr.find(".img_panel").remove();
					var panel =  $("<div class='img_panel'></div>");
					var deleteButton = $("<i class='glyphicon glyphicon-remove white icon'></i>");
					proj = that.getById(wpr.attr("id"));
					deleteButton.on("click", function(){
						wpr = $(this).closest(".img_wpr");
						proj = that.getById(wpr.attr("id"));
						description = $("<p>Are you sure you want to delete <b class='proj_name'></b> ?</p><p><input type='checkbox' id='projdelete_checkbox'> Delete all associated media Files ("+Object.keys(proj.media).length+")</p>");
						description.find('.proj_name').html(proj.title);
						
					 that.modal({
							 subject : "Confirm Deletion",
							 description : description,
							 buttons : {
								 Delete : {
									 type : "custom",
									 clicked : function(me){
										
										deleteImages = $("#projdelete_checkbox").is(":checked");
										data = {};
										data[wpr.attr("data-key")] = {
											deleteMedia : deleteImages
										};
										that.saveFile(that.src, {
											action : "deleteProjects",
											data : data,
											onSuccess : function(respObj){
												that.uploadHandlerResp.remove(wpr, 100);
											}
										});
										me.close();
									 },
								 },
								 Cancel : {
									 type : "cancel"
								 }
							 }
							 
						 });
			
					});
					var visibleButton = $("<i class='glyphicon glyphicon-eye-close white icon'></i>");
					visibleButton.on("click", function(e, skipSave){
						var pub;
						skipSave = (skipSave == undefined) ? 0 : 1;
						
						wpr = $(this).closest('.img_wpr');
						if ($(this).hasClass("glyphicon-eye-close")){
							wpr.find("a").append("<div class='shadow'><span class='glyphicon glyphicon-eye-close  white has_tooltip notVisible' title='Not Visible'></span></div>");
							$(this).removeClass("glyphicon-eye-close");
							$(this).addClass("glyphicon-eye-open");
							pub = 0;
						}else{
							wpr.find(".shadow").remove();
							$(this).removeClass("glyphicon-eye-open");
							$(this).addClass("glyphicon-eye-close");
							pub = 1;
						}
						
						
						if (skipSave==0){
							data = {};
							data[wpr.attr("data-key")] = { published : pub};
							that.saveFile(that.src, {
								action : "editAttributes",
								data : data,
								onError : function(msg){
									alert(msg);
								}
							});
						}
					});
					panel.append(visibleButton);
					panel.append(deleteButton);
					wpr.append(panel);
					wpr.on("mouseover", function(e){
						$(this).find(".img_panel").show();
					});
					wpr.on("mouseout", function(e){
						$(this).find(".img_panel").hide();
					});
					
				if (proj.published==0)
						visibleButton.trigger("click", 1);
						
				that.updateTooltip();
			},
			//<----------OBJ UTILS---------------------->//
			//add element of object
			add : function(k,v,dataObj){
				dataObj[k] = v;
			},
			//multiedit element of object
			multiedit : function(data, dataObj){
				var that = this;
				$.each(data, function(k,v){
					dataObj[k] = that.htmlEntities(v);
				});
			},
			//edit element of object
			edit : function(k, newVal, dataObj, keyOrVal){
				var that = this;
				switch (keyOrVal){
					case 'k':
						dataObj[ newVal ] = dataObj[ k ];
						delete dataObj[ k ];
						break;
					case 'v':
						dataObj[ k ] =that.htmlEntities(newVal);
						break;
				}
			},
			//delete element of object
			remove : function(k, dataObj){
				delete dataObj[k];
			},
			//filter projects by attributes keys and values @v=null means all
			filterProjects : function(k, v, dataObj, only){
				ret = {};
				$.each(dataObj, function(projId,proj){
					$.each(proj, function(k2,v2){
						if (k2==k){
							//if no specific selected attributes, give hole project
							if (only==undefined)
								filteredProj = proj;
							//else go through array and provide each one
							else{
								filteredProj = {};
								$.each(only, function(index,attr){
									filteredProj[attr] = proj[attr];
								});
							}
							if (v==undefined)
								ret[projId] = filteredProj;
							else if (v2==v)
								ret[projId] = filteredProj;
						}
					});
				});
				return ret;
			},
			//handle sorting of object
			resort  : function(newKeys, dataObj){
			
				var newObj = {};
				$.each(newKeys, function(k,v){
					if (dataObj[v]!=undefined)
						newObj[v] = dataObj[v];
				});
				
				$.each(dataObj, function(k,v){
					
					delete dataObj[k];
				});
				$.each(newObj, function(k,v){
					dataObj[k] = v;
				});
				
				dataObj = newObj;
				return dataObj;
			},
			//grabs all existing values of  a specific key in project..no doubles
			getExistingValues : function(searchKey, dataObj){
				arr = [];
				$.each(dataObj, function(proj_id,proj_obj){
					//each project attr
					$.each(proj_obj, function(k,v){
						//if its got that attr and value is not in arr already
						if (searchKey==k && $.inArray(v, arr)==-1){
							arr.push(v);
						}
					});
				});
				return arr;
			},
			//groups  all existing values of  a specific key in project.
			//same value projects grouped together..returns proj_id
			groupProjectsBy : function(searchKey, dataObj){
				ret = {};
				//order
				orderKeys = this.getExistingValues(searchKey, dataObj);
				//get existing values
				$.each(dataObj, function(projId, proj){
					//we prefix with group..because it will maintain insertion order
					key = "grouped_"+proj[searchKey];
					ret[key] = (ret[key] !=undefined) ? ret[key] : {};
					ret[key][projId] = proj;
				});
				return ret;
				
			},
			//iterates through dataobj, looking for key and val that match
			isUnique : function(key, val, dataObj){
				unique = true;
				$.each(dataObj, function(ek, ev){
					if (dataObj[ek][key]!=undefined)
						if (dataObj[ek][key]==val){
							unique = false;
							return unique;
						}
				});
				return unique;
			},
			getById : function(id){
				found = false;
				$.each(this.data.projects, function(k,v){
					if (parseInt(id)==parseInt(v.id)){
						found = v;
					}
				});
				return found;
			},
			isEmpty : function(obj){
				if (Object.keys(obj).length>0)
					return false;
				else
					return true;
			},
			//<-----------PROJECT UTILS----------------->//
			//validates draft
			validator : function(opts){
				$(".validate-error").removeClass('validate-error');
				var that = this;
				errors = [];
				$.each(this.draft, function(k,v){
					switch (k){
						case "id":
						case "cleanUrl":
							//server Handled
							break;
						case "media":
							mediaCount = 0;
							$.each(that.draft.media, function(mediaId, mediaItem){
								mediaCount++;
								$.each(mediaItem, function(k, v){
									//type check
									if (that.data.template.media[k]){
										tmpl = that.data.template.media[k];
										v = (typeof v == "string")? v.trim() : v;
										if (tmpl.required && v=="" && isNaN(v)){
											errors.push("<b>[Image "+mediaCount+"] "+k+": </b> Field is required.");
											$(".media_wpr[data-id='"+mediaId+"']").addClass('validate-error');
											$(".media_wpr[data-id='"+mediaId+"'] [data-attr='"+k+"']").find('.attr-input').addClass('validate-error');
										}else{
											if (that.attributeTypes[tmpl.type]){
												type = that.attributeTypes[tmpl.type];
												if (type.validate){
													//do validation
													res = type.validate(v);
													if (res.error){
														errors.push("<b>[Image "+mediaCount+"] "+k+": </b>"+res.error);
														
														$(".media_wpr[data-id='"+mediaId+"']").addClass('validate-error');
														$(".media_wpr[data-id='"+mediaId+"'] [data-attr='"+k+"']").find('.attr-input').addClass('validate-error');
													}
												}
											}
										}
									} 
								}); //<--end of each attribute
							}); //<--end of each media 
							break;
						default:
							
							//type check
							if (that.data.template.project[k]){
								tmpl = that.data.template.project[k];
								v = (typeof v == "string") ? v.trim() : v;
								if (tmpl.required && v=="" && isNaN(v)){
									errors.push("<b>"+k+": </b> Field is required.");
									$("#proj_info [data-attr='"+k+"']").find('.attr-input').addClass('validate-error');
								}else{
									if (that.attributeTypes[tmpl.type]){
										type = that.attributeTypes[tmpl.type];
										if (type.validate){
											//do validation
											res = type.validate(v);
											if (res.error){
												errors.push("<b>"+k+": </b>"+res.error);
												
												$("#proj_info [data-attr='"+k+"']").find('.attr-input').addClass('validate-error');
											}
										}
									}
								}
							}
							break;
					
					}
				});
				$(opts.errorBox).hide();
				$("#proj_info").find("input, textarea").prop('disabled', true);
				if (errors.length > 0){
					//error!
					errorUl = $("<ul></ul>")
					$.each(errors, function(index, v){
						errorUl.append("<li>"+v+"</li>");
					});
					$(opts.errorBox).html(" ");
					$(opts.errorBox).append("<b>The Following "+errors.length+" Errors Occurred:</b>");
					$(opts.errorBox).append(errorUl);
					$(opts.errorBox).show();
					$("#proj_info").find("input, textarea").prop('disabled', false);
				}else{
					//do success
					//check if new or not
					var mode = (this.$_GET('id')) ? this.$_GET('id') : "new";
					
					that.saveFile(that.src, {
						action : (mode=="new") ? 'addProject' : 'editProject',
						onError : function(msg){
							$(opts.errorBox).html("<b>Server error</b>:"+msg);
							$(opts.errorBox).show();
							$("#proj_info").find("input, textarea").prop('disabled', false);
						},
						onSuccess : function(respObj){
								var mode = (that.$_GET('id')) ? that.$_GET('id') : "new";
								that.original = $.extend(true, {}, that.draft);
								if (mode=="new"){
									
									link = "project.php?id="+respObj.data['project_id'];
									setTimeout(function(){
										window.location.href = link;
									}, 1000);
									$(opts.successBox).html("Success! Now redirecting to edit page. If not automatically redirected, click <a href='"+link+"'>here</a>.");
									$(opts.successBox).show();
								}else{
									that.toggleButton("#project_save", 0, "Up To Date");
									$("#project_title").html(that.draft.title);
									$("#proj_info").find("input, textarea").prop('disabled', false);
								}
						}
					});
					
					
				}
			},
			formatLogin : function(opts){
				
				var that = this;
				if (this.$_GET('a')=="logout"){
					$(opts.successBox).html("You have successfully logged out.");
					$(opts.successBox).show("slow");
				}else if (this.$_GET('p')){
					$(opts.errorBox).html("Please login to continue.");
					$(opts.errorBox).show("slow");
				}
				var submitAction = function(el){
					if (!$(el).hasClass("disabled")){
						$(opts.errorBox).hide();
						$(opts.successBox).hide();
						$('.validate-error').removeClass('validate-error');
						that.toggleButton(opts.submitButton, 0, "Validating..");
						$(".loginBox").find("input, textarea").prop('disabled', true);
						var data = {
							username : $("#login_username").val()+"",
							password : $("#login_pw").val()+""
						};
						
						if (data.username.trim()=="" || data.password.trim()==""){
								if (data.username.trim()=="")
									$("#login_username").addClass('validate-error');
								if (data.password.trim()=="")
									$("#login_pw").addClass('validate-error');
						
							
							$(opts.errorBox).html("Please fill in all fields.");
							$(opts.errorBox).show();
							that.toggleButton(opts.submitButton, 1, "Login");
							$(".loginBox").find("input, textarea").prop('disabled', false);
						}else{
							that.saveFile("xxx", {
								action : "login",
								data : data,
								onError : function(msg){
									$(opts.errorBox).html(msg);
									$(opts.errorBox).show();
									that.toggleButton(opts.submitButton, 1, "Login");
									$(".loginBox").find("input, textarea").prop('disabled', false);
								},
								onSuccess : function(respObj){
									//refresh
									link = (that.$_GET('a')!="logout") ? window.location.href : "index.php";
									setTimeout(function(){
											if (that.$_GET('a')!="logout")
												window.location.reload();
											else
												window.location.href = "index.php";
									}, 1000);
									that.toggleButton(opts.submitButton, 1, "Redurecting..");
									$(opts.successBox).html("Success! Now redirecting to edit page. If not automatically redirected, click <a href='"+link+"'>here</a>.");
									$(opts.successBox).show("slow");
								}
							});
						}
					}
				};
				
				//add event to button and input enter hits
				$(opts.submitButton).on("click", function(){submitAction(this)});
				$(".loginBox").find("input").on("keypress", function(event){
						var keycode = (event.keyCode ? event.keyCode : event.which);
						if(keycode == '13')
							submitAction(this);
				});
				//ie placeholder fix
				$("input, textarea").placeholder();
				//keep container vertically centered
				window.onresize = function(){
					that.verticalCenter("#container");
				};
				that.verticalCenter("#container");
			},
			//<----------TEMPLATE UTILS----------------->//			
			getUniqueKey : function(name, dataObj, delimiter){
				delimiter = (delimiter!=undefined) ? delimiter : "_";
				uName = name;
				count = 0;
				while (dataObj[uName]!=undefined){
					count++;
					uName = name+delimiter+count;
				}
				return uName;
				
			},
			removeFromTemplate : function(k, tmpl){
				template_id = $(tmpl).attr("data-tmpl");
				//remove from storage	
				this.remove(k, this.data.template[template_id]);
				$(tmpl).find("td[data-id='"+k+"']").parent().remove();
				this.handleChanges();
			},
			addToTemplate : function(tmpl){
				
				//get actual template key
				template_id = $(tmpl).attr("data-tmpl");
				
				//get new name
				newName = this.getUniqueKey("new_data", this.data.template[template_id]);
				
				//default value
				newValue = { 
					type : "text"
				}
				tr = this._templateRow(tmpl, newName, newValue);
				
				//add to data structure
				this.data.template[template_id][newName] = newValue;
				//add to table
				$(tmpl).append(tr);
				this.handleChanges();
			},
			//update key name of object
			changeKeyTemplate : function(nk,ok,dataObj){
				dataObj[ nk ] = dataObj[ ok ];
				delete dataObj[ ok ];
				this.handleChanges();
			},
			
			//update values of object
			changeValueTemplate : function(key,dataObj, newData){
				$.each(newData, function(k,v){
					dataObj[key][k] = v;	
				});
				this.handleChanges();
			},
			
			
			//for template resorting..
			resortTemplate : function(el){
				var newKeys = [];
				templateType =$(el).attr("data-tmpl");
				
				
				$(el).find("[data-id]").each(function(){
					newKeys.push($(this).attr("data-id"));
				});
				
				
				this.resort(newKeys, this.data.template[templateType]);
				this.handleChanges();
				
			},
			//render extras in the box
			renderExtras : function(obj){
				/*
					obj is
					@typeObj : attributeType
					@tmplObj : tmplType
				*/
				//any extra parameters?
				if (obj.typeObj.extras){
					extrasObj = obj.typeObj.extras(obj.tmplObj);
					//append to type
					return extrasObj.dom;
					
				}
			},
			//for media resorting..
			resortMedia : function(el){
				var newKeys = [];
				
				
				$(el).find(".media_wpr").each(function(){
					newKeys.push($(this).attr("data-id"));
				});
				
				this.resort(newKeys, this.draft.media);
				//this.handleChanges();
				
			},
			//resort project
			resortProjects : function(el){
				var that=this;
				newKeys = [];
				newKeysDom = [];
				$(el).find('.img_wpr').each(function(){	
					newKeys.push($(this).attr("id"));
				});
				$(el).find('.img_wpr').each(function(){	
					newKeysDom.push($(this).attr("data-key"));
				});
				this.resort(newKeysDom, this.data.projects);
				this.saveFile(this.src, {
					action : 'sortProjects',
					list :  newKeys,
					onSuccess : function(respObj){
					}
				});
			},
			toggleTemplateEdit : function(el){

				var that = this;
			
				this.editMode=1;
				
				tr = $(el).closest("tr");
				//name of template type (media or project)
				templateType = tr.parent().parent().attr("data-tmpl");
				//type of attribute e.g. bool, int, etc ie. attributetypes (type)
				attrubuteTypeObj = false;
				//name of attribute within template
				attributeId = tr.find(".name").attr("data-id");
				//attribute obj within template
				attributeObj = false;
				this.trBackup = tr.clone(true, true);
				
				
				//GET TMPL + TYPE OBJ
				//is this in template
				
				if (this.data.template[templateType][attributeId]){
					attributeObj = this.data.template[templateType][attributeId];
					//does type exist
					if (this.attributeTypes[attributeObj.type]){
						attributeTypeObj=this.attributeTypes[attributeObj.type];
					}
				}
				
				
				//edit mode for name and type
				nameEdit = $("<input type='text'>");
				nameEdit.css({
					width: "100%"
				});
				nameEdit.val(tr.find(".name").attr("data-id"));
				
				//type edit mode
				typeEdit = $("<select></select>");
				$.each(this.attributeTypes, function(k,v){
					select = (k==tr.find(".type").html()) ? "selected" : "";
					opt = $("<option "+select+">"+k+"</option>");
					opt.attr("value", k);
					typeEdit.append(opt);
				});
				
				typeEdit.on("change", function(e){
					tr =  $(el).closest("tr");
					parent = $(this).parent();
					typeObj = that.attributeTypes[$(this).val()];
					//remove extrasBox
					parent.find(".extrasBox").remove();
					
					
					if (typeObj.extras){
						wpr = $("<div class='extrasBox'></div>");
						wpr.append(that.renderExtras({
							typeObj : typeObj,
							tmplObj : that.data.template[templateType][attributeId]
						}));
						parent.append(wpr);
					}
					
					
				});
				
				tr.find(".name,.type").html("");
				tr.find(".name").append(nameEdit);
				
				tr.find(".type").append("<div class='errorBox'></div>");
				tr.find(".type").append(typeEdit);
				isRequired = (this.data.template[templateType][tr.find(".name").attr("data-id")].required) ? "checked=true" : "";
				req = $("<span class='req_checkbox'>Required? <input type='checkbox' "+isRequired+"></span>");
				tr.find(".type").append(req);
				
				//render extras
				typeEdit.trigger("change");
				/*
				if(attributeTypeObj.extras){
					tr.find(".type").append(that.renderExtras(attributeTypeObj));
				}
				*/
				
				//append cancel and ok to actionPanel
				panel = tr.find(".actionPanel");
				panelBackup = panel.clone(true, true);
				
				tr.off("mouseover");
				tr.off("mouseout");
	
				cancelButton =  $("<span class='glyphicon glyphicon-ban-circle icon' title='cancel'></span>");
				
				
				okButton =  $("<span class='glyphicon glyphicon-ok icon' title='ok'></span>");
				
	
				okButton.on("click", function(){
					tr =  $(this).closest("tr");
					name = tr.find(".name input").val();
					nameEl = that.trBackup.find(".name");
					oldName = nameEl.attr("data-id");
					errorBox  = tr.find(".type .errorBox");
					
					//reset error
					errorBox.hide();
					if (!name.match(that.regex.key.regex)){
						errorBox.html(that.regex.key.error).show();
						return false;
					}
					
					name = name.replace(/\s/g, "_");
					//make sure its unique
					if (name!=oldName){
						name = that.getUniqueKey(name, that.data.template[templateType]);
						//update template
						that.changeKeyTemplate(name, oldName, that.data.template[templateType]);
					}
					
					type = tr.find(".type select").val();
					required = tr.find(".type input").is(":checked");
					
				
					
					
			
					
					nameEl.attr("data-id", name);
					asterisk = (required) ? "*" : "";
					
					nameEl.html(nameEl.attr("data-id")+asterisk);
					
				
					that.trBackup.find(".type").html(type);
					tr.replaceWith(that.trBackup);
					
					//save to data
			//		that.data.template[templateType][nameEl.attr("data-id")].required = (required) ? 1 : 0;
					
					
					//find what it was before..and remove those attributes if different
					attrObj = that.data.template[templateType][name];
					oldType = that.attributeTypes[attrObj.type];
					newType = that.attributeTypes[type];

					//delete old
					if (oldType.extras!=undefined){
						
						//EXTRAS REMOVE
						extrasObj = oldType.extras({});
						$.each(extrasObj.data, function(index, v){
							delete attrObj[v];
						});
					}
					
					
					//add new
					if (newType.extras!=undefined){
						
						//EXTRAS ADD
						//apply extras here..loop through each new
							//add new stuff
						extrasObj = newType.extras({});
						$.each(extrasObj.data, function(index, k){
							newVal = tr.find("[data-extra-attr='"+k+"']").attr("data-extra-val");
							 that.add(k, newVal, attrObj); 
						});
					}
					
						
					that.changeValueTemplate(nameEl.attr("data-id"), that.data.template[templateType], {
						required : (required) ? 1 : 0,
						type : type
					});
					
					
						
					that.editMode = 0;
				});
				
				cancelButton.on("click", function(){
						tr.replaceWith(that.trBackup);
						that.editMode = 0;
				});
				
				panel.html("");
				panel.append(cancelButton).append(okButton);
				
			},
			//<------REUSED OBJS------------------->//
			_consolFiles : function(el, files){
				var that  = this;
				el.find(".errorBox, .successBox").hide();
				//file = $(this).closest("tr").find(".unlinked_file").attr("data-file");
				this.saveFile(that.src, {
					action : "consolidateFiles",
					data : files,
					onSuccess : function(respObj){
						if (respObj.data.error){
							//some files could not be removed
							el.find(".errorBox").html("Some files couldn't be moved: "+that._simpleList(respObj.data.error)).show();
							setTimeout(function(){
								el.find(".errorBox").hide("slow");
							}, 2000);
						}else if (respObj.data.success){
								el.find(".successBox").html(respObj.data.success.length+" File(s) moved").show();
							setTimeout(function(){
								el.find(".successBox").hide("slow");
							}, 2000);
						}
						if (respObj.data.success!=undefined){
							$.each(respObj.data.success, function(i,v){
								el.find(".unconsol_file[data-file=\""+v+"\"]").closest("tr").remove();
								$("#config_consolCount").html(parseInt($("#config_consolCount").html())-1);	
							});
						}
						
					},
					onError : function(msg){
						//none for this
					}
				});

			},
			_removeUnlinkedFiles : function(el, files){
				var that  = this;
				el.find(".errorBox, .successBox").hide();
				//file = $(this).closest("tr").find(".unlinked_file").attr("data-file");
				this.saveFile("xxx", {
					action : "deleteFiles",
					data : files,
					onSuccess : function(respObj){
						
						if (respObj.data.error){
							//some files could not be removed
							el.find(".errorBox").html("Some files couldn't be removed: "+that._simpleList(respObj.data.error)).show();
							setTimeout(function(){
								el.find(".errorBox").hide("slow");
							}, 2000);
						}else if (respObj.data.success){
								el.find(".successBox").html(respObj.data.success.length+" File(s) removed").show();
							setTimeout(function(){
								el.find(".successBox").hide("slow");
							}, 2000);
						}
						if (respObj.data.success!=undefined){
							$.each(respObj.data.success, function(i,v){
								el.find(".unlinked_file[data-file=\""+v+"\"]").closest("tr").remove();
								$("#config_unlinkCount").html(parseInt($("#config_unlinkCount").html())-1);	
							});
							
						}
						
					},
					onError : function(msg){
						//none for this
					}
				});

			},
			_simpleList : function(obj){
				ul = $("<ul></ul>");
				$.each(obj, function(k,v){
					ul.append("<li>"+v+"</li>");
				});
				return ul.html();
			},
			_projectRow : function( k, v){
					/*
						ex. title : "my name is chris"
					*/
					//stores template
					var tmpl = null;
					//stores type attributes
					var type = null;
					var that = this;
					
					//lets see if it is in template
					if (this.data.template.project[k]){
						tmpl = this.data.template.project[k];
						//lets find registered type
						if (this.attributeTypes[tmpl.type]){
							type = this.attributeTypes[tmpl.type];
						}else{
							//define as string by default
							type = this.attributeTypes["string"];
						}
					
					}else{
						//default stuff for non template attributes
						tmpl = {
							protected :0,
							required : 0
						}
						type = this.attributeTypes["string"];
					}
				
					trClass = (tmpl.protected) ? "class='protected'" : "";
					req = (tmpl.required) ? "*" : "";
					
					tr = $("<tr id='tr-"+k+"' "+trClass+"></tr>");
				
					tr.append("<td class='name'>"+k+req+"</td>");
					
					val = v;
					
					extras = "";
					
					//if it is a registered type 
					if (type){
						//EDIT MODE 
						if (type.edit){
							val = type.edit({
								key : k,
								value : that.unHtmlEntities(v),
								tmpl : tmpl,
								el : "#tr-"+k+" .val",
								onChange : function(o){
									that.edit(o.k, o.v, that.draft, 'v');
									
								}
							});						
						}
						
					}
					
					tdVal = $("<td class='val'></td>");
					tdVal.attr("data-attr", k); 
					
				
					//val should be an element
					tdVal.append(val);
					
					//if textbox ...initiate autocomplete	
					isTextBox = tdVal.find("input[type='text']");
					if (isTextBox.length){
						isTextBox.autocomplete({
							source : that.getExistingValues(k, that.data.projects),
							close : function(){
								el = $(this).closest("input[type='text']");
								el.trigger("change");
							}
						});
					}
					
					tr.append(tdVal);

					return tr;

			},
			_templateRow : function(tmplEl, k, v){
					var that=this;
					trClass = (v.protected) ? "class='protected'" : "";
					req = (v.required) ? "*" : "";
					
					tr = $("<tr "+trClass+"></tr>");
				
					tr.append("<td class='name' data-id='"+k+"'>"+k+req+"</td>");
					tr.append("<td class='type'>"+v.type+"</td>");
					
					
					panel = $("<div class='actionPanel'></div>");
					deleteButton = $("<span class='glyphicon glyphicon-remove icon' title='remove'></span>");
					deleteButton.click(function(){
					
					 that.modal({
						 subject : "Confirm Deletion",
						 description : "<div class='errorBox'></div><div class='successBox'></div><p>Are you sure you want to delete the <b>"+k+"</b> attribute?</p><p><input type='checkbox' id='tmpl_removeAttributes'> Remove this attribute from existing Projects</p>",
						 buttons : {
							 Delete : {
								 type : "custom",
								 clicked : function(me){
								 
							
								 	
								 	tmplType = $(tmplEl).closest("table").attr("data-tmpl");
								 	attr = k;
								 	//get project keys with that attribute...this is for "project" template
								 	if (tmplType=="project"){
								 		affectedProjects =that.filterProjects(attr, null, that.data.projects, [attr]);
								 		$.each(affectedProjects, function(projId, proj){
									 		affectedProjects[projId] = Object.keys(proj);
								 		});
								 	}else{
								 		//get for media template
								 		affectedProjects = {};
									 	$.each(that.data.projects, function(projId, proj){
											tmpProj =that.filterProjects(attr, null, proj.media, [attr]);
											affectedProjects[projId] = {};
											$.each(tmpProj, function(mediaKey, mediaObj){
												affectedProjects[projId][mediaKey] = Object.keys(mediaObj);
									 		});
									 	});
								 	}
								 	removeAttributes = ($("#tmpl_removeAttributes").is(":checked")) ? 1 : 0;
								 	if (removeAttributes==1){
								 		
								 		that.saveFile(that.src, {
									 		action : (tmplType=="project") ? "deleteAttributes" : "deleteMediaAttributes",
									 		data : affectedProjects
								 		});
								 		$(me.el).find(".successBox").html(removeAttributes+" tmplType: "+tmplType+" attr:"+attr+" affectedProjects"+json_encode(affectedProjects)).show();
								 	}
								 	
								 	that.removeFromTemplate(k,"#"+$(tmplEl).attr("id"));
								 	 me.close();
									
								 },
							 },
							 Cancel : {
								 type : "cancel"
							 }
						 }
						 
					 });
			
					});
					editButton = $("<span class='glyphicon glyphicon-wrench icon' title='edit'></span>");
					editButton.click(function(){
					
					 that.toggleTemplateEdit(this);
			
					});
					infoButton = $("<span class='glyphicon glyphicon-info-sign icon' title='info'></span>");
					
					if (!v.protected){
					panel.append(deleteButton);
					panel.append(editButton);
					}
					//panel.append(infoButton);
					$("<td></td>").append(panel).appendTo(tr);
					
					tr.on("mouseover", function(){
						if (that.editMode!=1)
							$(this).find(".actionPanel").show();
					});
					
					tr.on("mouseout", function(){
						$(this).find(".actionPanel").hide();
					});
					
					return tr

			},
			//<---------CMCM FILE UPLOADER FUNCS-------------->//
			//checks files
			fileValidator : function(f){
				res = {
					valid : 0,
					type : null,
					ext :  f.name.substring(f.name.lastIndexOf('.') + 1)
				}
				allowed = {
					"image" : this.filesAllowed
				}
				
				$.each(allowed, function(type, exts){
					$.each(exts, function(index, ext){
						if (ext.trim().toLowerCase()==f.type.trim().toLowerCase()){
							res.valid = 1;
							res.type = type;
						}
					})
				});
				
				return res;
			},
			//handles resused responses from server
			uploadHandlerResp : {
				error : function(data,error){
					//error or text status
					msg = "An error Occurred";
					msg = ( data.textStatus) ?  data.textStatus : msg;
					msg = (msg=="parsererror") ? data.errorThrown+" : " : msg;
					msg = (error) ? error : msg;
					
					//hide progress bar and panel
				    data.context.addClass("errorWpr");
				    errorIcon = $("<div class='errorIcon'><i class='glyphicon glyphicon-exclamation-sign white has_tooltip' title='"+msg+"'></i></div>");
				    
				    
				    data.context.append(errorIcon);
				    cmcm.updateTooltip();
				    data.context.find(".img_panel").remove();
				    data.context.find(".progress_wpr").remove();
				    
				    //remove after 5 seconds
				    this.remove(data.context);
				    
				},
				remove : function(el, timer){
					timer = (timer) ? timer : 5000;
					 
					 //remove
				    setTimeout(function(){
					   $(el).hide({
					    		effect : "scale",
					    		complete : function(e){
						    		$(this).remove();
					    		}
				    	});
			    	}, timer);

				}
			},
			//makes an element an uploader input..and also sets up blueimp plugin
			formatMediaUploader : function(el){
				 var that= this;
				 
				$(el).addClass('has_tooltip');
				mediaContainer = "#media_files";
				
				//make media files sortable
				$(mediaContainer).sortable({ 
					update : function(e,ui){
						that.resortMedia(mediaContainer);
					}
				});
	
		      
		         var cssInfo = {

			        display: "inline-block",
			        position : "relative"
		        }
		        
		        fileInputWpr = $("<div id='media_file_input'>");
		        fileInputWpr.css(cssInfo);
		        
		        $(el).wrap(fileInputWpr);
		        
		        //file input el
		        fileInput =  $('<input id="fileupload" title="" class="has_tooltip" type="file" name="files[]" data-url="lib/blueimp-jqueryFileUpload/" accept="image/gif, image/jpeg, image/png"  multiple>');
		        //fileInput.attr("title", 'Add Images <i class=\"small\">(Hold for more Options)</i>');
		        fileInput.attr("title", "<p>Add Images <i class=\"small\">(Hold for more Options)</i></p><p>Upload Settings "+that._simpleList([
					"<span class=\"small\">Upload limit: <b>"+that.maxFileSize.upload_max_filesize+"</b></span>",
					"<span class=\"small\">Allowed Filetypes: <b>"+that.filesAllowed.join(", ")+"</b></span>"
				])+"</p>");
		        //make it hidden so we dont see it..
		        $.extend(cssInfo, cssInfo, {
			        filter: "alpha(opacity=0)",
			        width :  $(el).width()+"px",
			        height :  $(el).css("height"),
			        padding : $(el).css("padding"), 
			        opacity: 0,
			         position: "absolute",
		        	 top : 0,
		        	 left :0
		        });
		        
		       //set up file upload thing to be hidden and rest on top..
		       fileInput.css(cssInfo);
		       //sync hover events
		       fileInput.on("mouseover", function(){
			      $(el).addClass("hovered"); 
		       });
		       fileInput.on("mouseout", function(){
			      $(el).removeClass("hovered"); 
		       });
		        fileInput.on("change", function(){
			       $(el).removeClass("hovered");  
		        });
		        
		        fileInput.on("mousedown", function(e){
			        that.timer = setTimeout(function(){
				     	e.stopPropagation();
				     	e.preventDefault();
				     	
				
				     	/*
				     	testzzz

						*/
				     	that.modal({
					     	subject : "External Media",
					     	description : "<div class='errorBox'></div><div class='successBox'></div><div class='loadingBox'><div class='loading'></div> Please wait while we grab media data...</div>Here you can include external media files (videos, music) from popular sites. Paste in urls, one per line. Currently supported are youtube, vimeo, and soundcloud.<div style='height:150px;'><textarea id='externalMedia_textarea' style='width:600px; resize: none; height: 100% !important;display:inline-block; white-space:pre;'></textarea><div id='externalMedia_indicatorColumn' style='width:50px; text-align: center; height: 100%; display:inline-block;background: #c0c0c0; overflow:hidden;white-space:nowrap; float:left;'></div></div><div id='externalMedia_stats'></div>",
					     	 buttons : {
								 Insert : {
									 type : "custom",
									 clicked : function(me){
									 	$(me.el).find(".errorBox,.successBox").hide();
									 	$(me.el).find(".loadingBox").show();
									//	$(me.el).find(".successBox").html(json_encode(that.parseExternalMediaUrls($("#externalMedia_textarea").val()))).show();
										that.saveFile(that.src, {
											action : "addExternalMedia",
											data : that.parseExternalMediaUrls($("#externalMedia_textarea").val()),
											onError : function(msg){
												$(me.el).find(".errorBox").html(msg).show();
											},
											onDone : function(){
												$(me.el).find(".loadingBox").hide();
											},
											onSuccess : function(respObj){
												$(me.el).find(".successBox").html(json_encode(respObj.data)).show();
													$.each(respObj.data, function(index, exMedia){
															wpr = $("<div class='media_wpr' data-id=''></div>");	
															 mediaId = that.getUniqueKey(base64_encode(exMedia.src).replace(/=/gi, '-'), that.draft.media);
															 wpr.attr("data-id", mediaId);
															//add to that.draft.media with template
															that.addMediaToProject(mediaId, {
																visible : 1,
																type : that.externalMediaTypes[exMedia.type].type, 
																src : exMedia.src,
																thumb : exMedia.thumb
															});
															//add preview to wrapper
															wpr.append(that.externalMediaTypes[exMedia.type].preview(that.draft.media[mediaId]));
															$("#media_files").append(wpr);
															that.updateTooltip();
															//add all fancy settings...
															that._mediaFormat(mediaId);
															 me.close();
													})	
											}
										})
									
									
									 },
								 },
								 Cancel : {
									 type : "cancel"
								 }
							}
				     	});
				     	$("#externalMedia_indicatorColumn").css({
				     		"line-height" :  $("#externalMedia_textarea").css("line-height"),
				     		"font-size" : $("#externalMedia_textarea").css("font-size"),
				     		"padding" : $("#externalMedia_textarea").css("padding")
				     	});
				     	$("#externalMedia_textarea").resize(function(){
					     		$("#externalMedia_indicatorColumn").height($(this).height());
				     	});
				     	$("#externalMedia_textarea").on("scroll", function(e){
					     		$("#externalMedia_indicatorColumn").scrollTop($(this).scrollTop());
				     	});
				     	$("#externalMedia_textarea").on("keyup change paste", function(){
					     	$("#externalMedia_indicatorColumn").empty();

					     	//get lines
					     	//parse string from textarea
					     	that.parseExternalMediaUrls($(this).val(), {
						     	onEach : function(url, externalType, stats){
						     		if (externalType!="unknown"){
						     			icon = $("<span class='glyphicon glyphicon-ok has_tooltip' style='color:green' title='"+externalType+" detected'></span>");
						     		}else
						     			icon = $("<span class='glyphicon glyphicon-remove has_tooltip' style='color:red' title='Unknown type'></span>");
						     		$("#externalMedia_indicatorColumn").append(icon).append("<br>");
						     	}
					     	});
					     	//post stuff
					     	$(this).trigger("scroll");
					     	that.updateTooltip();
					     	$("#externalMedia_stats").html("<b>"+stats.valid+"/"+stats.total+" Urls Valid.</b>");
				     	});
				     	
			        }
			        ,1000);
		        });
		        fileInput.on("mouseup", function(e){
			       	clearInterval(that.timer); 
		        });
		       //file uploader
				fileInput.fileupload({
			        dataType: 'json',
			        add: function (e, data) {
				      //var that = this;
				      var file = data.files[0];
				      that.jqXHR_count++;
				      var xhrID = "jqXHR-"+that.jqXHR_count;
				       wpr = $("<div class='media_wpr'></div>");
				       //replace = with - for attribute spec
				      
				       wpr.attr("data-id", base64_encode(file.name).replace(/=/gi, '-'));
				      
				      fileInfo = that.fileValidator(file);
				      
				      //return if not valid
				      if (!fileInfo.valid)
				      	return false;
				      	
				      //img preview
				      if (fileInfo.type=="image"){
				          img = new Image();
		           		  img.src=URL.createObjectURL(file);
		           		   //preview mode
		           		  $(img).css("opacity", ".8");
		           		  
		           		  imgCrop = $("<div class='media_crop'></div>");
		           		  imgCrop.append(img);
		           		   wpr.append(imgCrop);
	           		   //misc files
	           		   }else{
		           		   miscFile = $("<div class='noImage'>"+fileInfo.ext+"</div>");
		           		    wpr.append(miscFile);
	           		   }

	           		  
	           		  //progress bar
	           		  progWpr = $("<div class='progress_wpr'></div>");
	           		  progBar = $("<div class='progress_bar'></div>");
	           		  progWpr.append(progBar);
	           		  
	           		  //img panel
	           		  imgPanel = $("<div class='img_panel'></div>");
	           		  removeButton = $("<i class='glyphicon glyphicon-ban-circle white icon' data-id="+xhrID+"></i>");
	           
	           		  removeButton.on("click", function(e){
		           		  xhrID = $(this).attr("data-id");
		           		  that.jqXHR[xhrID].abort();
	           		  });
	           		  imgPanel.append(removeButton);
	           
	           		  	wpr.on("mouseover", function(e){
	           		  		if (that.editMode==0)
								$(this).find(".img_panel").show();
						});
						wpr.on("mouseout", function(e){
							if (that.editMode==0)
								$(this).find(".img_panel").hide();
						});
	           		  
	           		  wpr.appendTo("#media_files");
	           		  wpr.append(progWpr);
	           		  wpr.append(imgPanel);
	           		  
	           		  
				       // attach new element to data context
				        data.context = wpr;
				       
				       //submit thing..
				        that.jqXHR[xhrID] = data.submit();
				        
				   
				    },
				    progress : function (e, data) {
				        var progress = parseInt(data.loaded / data.total * 100, 10);
			           	data.context.find(".progress_bar").css({
					        	width : progress+"%"
				        	});
				    },
				    fail : function(e, data){
				    	console.log(data);
					    that.uploadHandlerResp.error(data);
				    },
			        done: function (e, data) {
				        	
				     	
			        	that.setDebugBox(data.result.files, "file_resp", {
				        	left : "inherit",
				        	top: "20px",
				        	right : "20px"	
			        	});
			        	
			        	file = data.result.files[0];
						data.context.attr("data-id", base64_encode(file.name).replace(/=/gi, '-'));
							
			        	//check if error
				       	if (file.error){
					       	that.uploadHandlerResp.error(data, file.error);
					       	return false;
				       	}
				       	//ELSE
				       	//ADD TO draft project
			        	that.addMediaToProject(data.context.attr("data-id"), {
				        	visible : 1,
				        	type : "image",
				        	src : that.data.mediaFolder+file.name,
				        	thumb : file.thumbnailUrl
			        	});
			        	
			        	//add image preview to wrapper
						data.context.find(".media_crop").replaceWith(that.mediaTypes.image.preview(that.draft.media[data.context.attr("data-id")]));
						//fancy settings
						that._mediaFormat(data.context.attr("data-id"), file);
						
			        }
		        });
		        
		        
		       $("#media_file_input").append(fileInput);
		       that.updateTooltip();
		      

			},
			//add media to draft project..@mediaId will be created
			addMediaToProject : function(mediaId, fields){
				//BLANK MEDIA
			     //add blank media tmpl to draft
	        	newMedia = {};
	        	$.each(this.data.template.media, function(k,v){
		        	newMedia[k]="";
	        	})
	        	//add to data obj
	        	this.add(mediaId, newMedia, this.draft.media);
	        	this.multiedit(fields, this.draft.media[mediaId]);
				
			},
			parseExternalMediaUrls : function(str, opts){
				var that = this;
				ret = [];
				stats = {
			     	total : 0,
			     	valid : 0
		     	};
				opts = (opts!=undefined) ? opts : {};
				//some callbacks	
				opts = {
					onEach : (opts.onEach!=undefined) ? opts.onEach : function(url, type, stats){}
				};
				lines = str.split("\n");
		     	
		     	$.each(lines, function(index, line){
		     		externalType = "unknown";
		     		$.each(that.externalMediaTypes, function(type, obj){
		     			if (line.match(obj.regex)){
		     				externalType = type;
		     				stats.valid++;
		     				ret.push({url : line, type : externalType});
		     			}
		     		});
		     		stats.total++;   		
		    		opts.onEach(line,externalType, stats);
		     	});
		     	return ret;
			},
			//formats media_wpr to have all the fancy settings panels and stuff 
			//@file is optional.. means its just been added
			_mediaFormat : function(id, file){

						//generate content
						var that = this;
						mediaObj  = this.draft.media[id];
						data.context = $("[data-id='"+id+"']");
				      	data.context.attr("data-id",id);
						
						if (file==undefined){
							//EXISTING MEDIA
							filename = mediaObj.src.split("/").pop();
							file = {
								deleteUrl : "lib/blueimp-jqueryFileUpload/?file="+encodeURI(filename)+"&_method=DELETE",
								name : filename,
								url : encodeURI(mediaObj.src)
							}
							data.context.append("<div class='img_panel'></div>");
							data.context.on("mouseover", function(e){
								if (that.editMode==0)
									$(this).find(".img_panel").show();
							});
							data.context.on("mouseout", function(e){
								if (that.editMode==0)
									$(this).find(".img_panel").hide();
							});
						}else{
						//NEW MEDIA
							//store data
							data.context.find("img").css({
								opacity : 1
							});
							data.context.find("img").attr("src",file.url);
							data.context.find(".progress_wpr").css('display', 'none');
							
							
						}
			        	//delete button...do itss
						panel = data.context.find(".img_panel");
						panel.html("");
						data.context.attr("data-deleteUrl", file.deleteUrl);
						data.context.attr("data-name", file.name);
						
			        	deleteButton = $("<i class='glyphicon glyphicon-remove white icon has_tooltip' title='Delete'></i>");
			        	
			        	deleteButton.on("click", function(){
			        		
			        		mWpr = $(this).closest(".media_wpr");
			        		mediaId = mWpr.attr("data-id");
			        		media = that.draft.media[mediaId];
			        		
			        		that.saveFile("xxx", {
				        		action : "deleteMediaFiles",
				        		data : media,
				        		onSuccess : function(respObj){
				        			//remove from draft media data
						    		that.remove(mWpr.attr("data-id"), that.draft.media);
						    		if (that.draft.coverImage == mWpr.attr("data-id")){
							    		that.draft.coverImage = "";
						    		}
						    		//remove from dom
						    		that.uploadHandlerResp.remove(mWpr,1);
						        	that.setDebugBox(respObj, "db-delete", {
							        	top: "20px",
							        	left: "auto",
							        	right : "20px"
						        	});
						        	
						       
				        		}
			        		});

			        	});
			        	starButton = $("<i class='glyphicon glyphicon-star white icon has_tooltip' title='Make Cover Image'></i>");
			        	starButton.on("click", function(){
			        		$(".featuredIcon").remove();
			        		mWpr = $(this).closest(".media_wpr");
			        		that.edit('coverImage', mWpr.attr("data-id"), that.draft, 'v');
			        		featuredIcon = $("<div class='featuredIcon'><i class='glyphicon glyphicon-star white has_tooltip' title='This is the cover Image'></i></div>");
			        		mWpr.append(featuredIcon);
			        		that.updateTooltip();
			        	});
						

						
			        	settingsWrapper = $('<div class="dropdown dropup media-dropdown"></div>');
			        	settingsWrapper.attr("id", "dd-"+data.context.attr("data-id"));
			        	settingsButton = $('<span data-toggle="dropdown" class="glyphicon glyphicon-cog  white icon has_tooltip" title="Settings"></span>');
			        
			        	settingsWrapper.append(settingsButton);
			        	settingsMenu = $('<ul class="dropdown-menu "></ul>');
				       
				       $.each(that.draft.media[data.context.attr("data-id")], function(k,v){
				       
						 	tmpl = null;
						 	type = null;
						 	
						    //lets see if it is in template
							if (that.data.template.media[k]){
								tmpl = that.data.template.media[k];
								//lets find registered type
								if (that.attributeTypes[tmpl.type]){
									type = that.attributeTypes[tmpl.type];
								}else{
									//define as string by default
									type = that.attributeTypes["string"];
								}
							
							}else{
								//default stuff for non template attributes
								tmpl = {
									protected :0,
									required : 0
								}
								type = that.attributeTypes["string"];
							}
							
						
							if (!tmpl.hidden){
								val = v;
									
								//if it is a registered type 
								if (type){
									//EDIT MODE 
									if (type.edit){
										
										
										//handle custom onchange events
										onChangeFunc_def = function(o){
												mWpr = $(o.el).closest(".media_wpr");
												that.edit(o.k, o.v,that.draft.media[mWpr.attr("data-id")], 'v');
											};
										switch (k){
											case 'visible':
												//set visible on change
												onChangeFunc = function(o){
												onChangeFunc_def(o);
												mWpr = $(o.el).closest(".media_wpr");
												if (o.v==0){
													mWpr.append("<div class='shadow'><span data-toggle='dropdown' class='glyphicon glyphicon-eye-close  white has_tooltip notVisible' title='Not Visible'></span></div>");
													that.updateTooltip();
												}
												else
													mWpr.find(".shadow").remove();
												
												};
												break;	
											case 'thumb':
												//change thumb src on change
												onChangeFunc = function(o){
						
													onChangeFunc_def(o);
													mWpr = $(o.el).closest(".media_wpr");
													mWpr.find("img").attr('src', that.draft.media[mWpr.attr("data-id")][k]);
												};
												break;
											default:
												onChangeFunc = function(o){
												onChangeFunc_def(o);
												};
												break;		
										}
										
										//initialize edit mode
										val = type.edit({
											key : k,
											value : that.unHtmlEntities(v),
											tmpl : tmpl,
											el : "#tr-"+k+" .val",
											onChange : onChangeFunc
										});
										
									
									
									}
								}
								req = (tmpl.required) ? "*" : "";
						       sli = $("<li class='input'></li>");
						       sli.attr("data-attr", k);
						       sli.append('<span class="header">'+k+req+'</span>');
		
						       sli.append(val);
						       //custom edit modes..manipulate sli
						       
						       switch (k){
						       	case "thumb":
						       	case "src":
						       		if (mediaObj.type=="image" || k=="thumb"){
										that.serverBrowser(sli.find("input"), {
											imagesOnly : 1, 
											folderSelectMode: 0,
											onOk : function(filepath, el){
												wpr = $(el).closest(".media_wpr");
												mediaId =  $(el).closest(".media_wpr").attr("data-id");
												media = that.draft.media[mediaId];
												if (k=="thumb" || !media.thumb || media.thumb=="false"){
													img = $(el).closest(".media_wpr").find("img");	
													if (img.length!=0){
														img.attr("src", filepath);
													}	
												}				
											}
										});
									}
						       		break;
						       }
						       
						       settingsMenu.append(sli);
					       }
				       });
				       
				      
		
				        settingsMenu.on("click", function(e){
					       e.stopPropagation();
					     //  e.preventDefault(); 
				        });
				        settingsWrapper.append(settingsMenu);
				        
		
			        	panel.append(starButton);
			        	panel.append(deleteButton);
			        	panel.append(settingsWrapper);
			        	
			        	//dropdown event
			        	$("#dd-"+data.context.attr("data-id")).on('show.bs.dropdown', function () {
						 	that.editMode = 1;
						});
						$("#dd-"+data.context.attr("data-id")).on('hide.bs.dropdown', function () {
						 	that.editMode = 0;
						 	$(this).parent().hide();
						});

						if (that.draft.coverImage==id)
							starButton.trigger("click");
			        	
						that.updateTooltip();
			},
			//open a modal dialog with serverBrowser
			serverBrowser : function(el, opts){
			    var	that = this;
				opts = (opts!=undefined) ? opts : {};
				opts.imagesOnly = (opts.imagesOnly!=undefined) ?  opts.imagesOnly : 0;
				opts.initFolder = (opts.initFolder!=undefined) ?  opts.initFolder : that.data.mediaFolder;
				opts.folderSelectMode = (opts.folderSelectMode!=undefined) ?  opts.folderSelectMode : 0;
				opts.onOk = (opts.onOk!=undefined) ? opts.onOk : function(file,el){return false};

				
				//el should be an input
				el.parent().css({position: "relative"});
	       		el.parent().find("input").wrap("<div class='fileInputWrapper' style='margin-right: 30px; position: relative;'></div>");
	       		serverBrowser = $("<span class='glyphicon glyphicon-folder-open icon has_tooltip' title='Click for server browser' style='position: absolute; top:30%; right: -30px;'></span>");
	       		
	       		fileBrowserDiv = $("<p>Here you can select files and directories visually instead of manually typing them in.. Click ok to change. </p><div id='cmcm_server_wpr'> <div id='cmcm_serverBrowser'></div><div id='cmcm_serverPreview'></div></div>");
	       		if (opts.folderSelectMode){
	       			fileBrowserDiv.find("#cmcm_serverPreview").remove();
	       			fileBrowserDiv.find("#cmcm_serverBrowser").css({
		       			width: "100%"
	       			});
	       		}
	       			
	       		
	       		serverBrowser.on("click", function(){
	       			//modal init
	       			that.modal({
		       			subject : "Server Browser",
		       			description : fileBrowserDiv,
		       			buttons : {
			       			Ok : {
				       			type : "custom",
				       			clicked : function(me){
				       				el.val($("#cmcm_serverBrowser").find(".cmcm_fileBrowser_selected").attr("rel"));
				       				el.trigger("change");
					       			opts.onOk($("#cmcm_serverBrowser").find(".cmcm_fileBrowser_selected").attr("rel"), el);
					       			me.close();
				       			}	
				       		},
				       		Cancel : {
					       		type : "cancel"
				       		}
		       			}
	       			});
	       			
   					//initialize filetree
       				$('#cmcm_serverBrowser').fileTree({
				        root: '',
				        script: 'lib/jquery-filetree/jqueryFileTree.php',
				        initFolder : opts.initFolder, 
				        expandSpeed: 1000,
				        folderSelectMode : opts.folderSelectMode, //this will only allow directory to be selected..event will be respondent to that
				        allowed : (opts.imagesOnly) ? ["jpg", "jpeg", "gif", "png"] : 0, //allow only certian types
				        collapseSpeed: 1000,
				        multiFolder: false,
				    }, function(file, el) {
				    	$(".cmcm_fileBrowser_selected").removeClass("cmcm_fileBrowser_selected");
				    	$(el).addClass('cmcm_fileBrowser_selected');
				    	if (!opts.folderSelectMode){
					    	img = new Image();
					    	img.onload = function(){
					    		$("#cmcm_serverPreview").empty();
					    		
					    		$("#cmcm_serverPreview").append(this);
					    		$(this).css({marginTop:  $(this).height()/-2, visibility: "visible"});
					       		$("#cmcm_serverPreview").append("<div class='cmcm_prevDimensions'>"+this.width+"px X "+this.height+"px</div>");
					       		$("#cmcm_serverPreview").append("<div class='cmcm_serverFileName'></div>");
					       		$("#cmcm_serverPreview").find(".cmcm_serverFileName").html(file);
				       		};
					    	img.src = file;
				    	}else{
					    	//folder select
				    	}
			        })
	       			
	       		});
	       		 
	       		
				el.parent().append(serverBrowser);
			},
			//analyze projects and return data obj with discrepancies between template and project NEED MORE EFFICIENT
			getDiscrepancies : function(){
				var that = this;
				errorCodes = {
					NOT_IN_TEMPLATE : 0, //proj has attribute not in template
					MISSING_ATTRIBUTE : 1, //proj is missing an attribute found in template
					NOT_VALID : 2, //proj attribute is not valid ex. not an integer
				};
				//return obj
				retX = {project :{}, media :{}};	
				//go through each project
				$.each(that.data.projects, function(projId,proj){
					ignoreList = ["media"];
					//go through each attribute
					$.each(proj, function(k,v){
						//check if in template
						if ($.inArray(k,ignoreList)==-1){
							//not in template
							if (that.data.template.project[k]==undefined){
								retX.project[k] =  (retX.project[k]!=undefined) ? retX.project[k] : [];
								retX.project[k].push({"projId" : projId, "code" : errorCodes.NOT_IN_TEMPLATE, "msg" : "("+k+") Not in Template."});
							}
							
						}
					});
					
					//go through each media in each project
					count = 0;
					$.each(proj.media, function(mediaId, media){
						count++;
						$.each(media, function(k,v){
						
							//not in template
							if (that.data.template.media[k]==undefined){
								retX.media[k] =  (retX.media[k]!=undefined) ? retX.media[k] : [];
								retX.media[k].push({mediaId : mediaId, mediaIndex : count,"projId" : projId, "code" : errorCodes.NOT_IN_TEMPLATE, "msg" : "("+k+") Not in Template."});
							}
						});
					});
					
					//go through each template thing
					$.each(that.data.template.project, function(tk, tv){
						tmpl = tv;
						//check if it has attribute
						if (proj[tk]==undefined){
							retX.project[tk] = (retX.project[tk]!=undefined) ? retX.project[tk] : [];
							retX.project[tk].push({"projId" : projId, "code" : errorCodes.MISSING_ATTRIBUTE, "msg" : "Missing ("+tk+") attribute."});
						}else if (tmpl.required && that.trim(proj[tk])===""){
							//check if its required
							retX.project[tk] = (retX.project[tk]!=undefined) ? retX.project[tk] : [];
							retX.project[tk].push({"projId" : projId, "code" : errorCodes.NOT_VALID, "msg" : "Required field ("+tk+") is blank."});
						}else if (that.attributeTypes[tmpl.type]){
							type = that.attributeTypes[tmpl.type];
							//check if its valid
							if (type.validate){
								//do validation
								res = type.validate(proj[tk]);
								if (res.error){
									retX.project[tk] = (retX.project[tk]!=undefined) ? retX.project[tk] : [];
									retX.project[tk].push({"projId" : projId, "code" : errorCodes.NOT_VALID, "msg" : "("+tk+") "+res.error});
								}
									
							}
						}
					});
					
					//loop through each media object
					count = 0;
					$.each(proj.media, function(mediaId, media){
						//go through each media attribute in tmpl
						count++;
						$.each(that.data.template.media, function(tk, tv){
							tmpl = tv;
							//check if it has attribute
							if (media[tk]==undefined){
								retX.media[tk] = (retX.media[tk]!=undefined) ? retX.media[tk] : [];
								retX.media[tk].push({mediaId : mediaId, mediaIndex : count,  "projId" : projId, "code" : errorCodes.MISSING_ATTRIBUTE, "msg" : "Missing ("+tk+") attribute."});
							}else if (tmpl.required && that.trim(media[tk])===""){
								//check if its required
								retX.media[tk] = (retX.media[tk]!=undefined) ? retX.media[tk] : [];
								retX.media[tk].push({mediaId : mediaId, mediaIndex : count, "projId" : projId, "code" : errorCodes.NOT_VALID, "msg" : "Required field ("+tk+") is blank."});
							}else if (that.attributeTypes[tmpl.type]){
								type = that.attributeTypes[tmpl.type];
								//check if its valid
								if (type.validate){
									//do validation
									res = type.validate(media[tk]);
									if (res.error){
										retX.media[tk] = (retX.media[tk]!=undefined) ? retX.media[tk] : [];
										retX.media[tk].push({mediaId : mediaId, mediaIndex : count, "projId" : projId, "code" : errorCodes.NOT_VALID, "msg" : "("+tk+") "+res.error});
									}
										
								}
							}
						});
					});

				});
				
				stats = {
					atts : 0,
					discreps : 0
				}
				
				$.each(retX.project, function(k,v){
					stats.atts++;
					stats.discreps+=v.length;
				});
				$.each(retX.media, function(k,v){
					stats.atts++;
					stats.discreps+=v.length;
				});
				
				$("#discrep_stats").html("( Attributes: "+stats.atts+", Items: "+stats.discreps+")");
				//no discrepencies
				if (stats.atts<=0){
					$("#discrep").hide();
					$("#discrep_none").html("<div><i>Currently there are no Discrepancies :)</i><br><br></div>").show();
				}else{
					$("#discrep_none").hide();
					$("#discrep").show();
					//discrepencies!! handle	
						$("#discrep").append("<tr><th colspan=2 style='text-align: right; background :#000; color: #fff;'>PROJECT TEMPLATE DISCREPENCIES</th></tr>");	
					
					//classes with colors
					colors = [
						"errorSmall", //NOT_IN_TEMPLATE..less important
						"errorLarge", //MISSING_ATTRIBUTE..danger
						"errorMedium" //NOT_VALID...middle bad
					];
					
					//each proj discrep
					$.each(retX.project, function(attName, items){
						//get tmpl object
						tmpl = {};
						if (that.data.template.project[attName]!=undefined){
							tmpl = that.data.template.project[attName];
						}
						tr = $("<tr data-type='project' data-attr='"+attName+"'><th colspan=2 style='text-align: right'> "+attName+"</th></tr>");
						resolveAllButton = $("<span class='glyphicon glyphicon-wrench icon has_tooltip resolveAllButton' title='Resolve All!'></span>");
						//cant multiresolve if unique is required
						if (!tmpl.unique){
							tr.find("th").prepend(resolveAllButton);
						}
						
								
						$("#discrep").append(tr);
						$.each(items, function(index, val){
							project = that.data.projects[val.projId];
							tr = $("<tr class='discrep_item "+colors[val.code]+"' data-key='"+val.projId+"' data-type='project' data-error-code="+val.code+" data-attr='"+attName+"'><td><a href='project.php?id="+project.id+"' class='noLink'>"+project.id+": "+project.title+"</a></td><td>"+val.msg+"</td><td class='actionPanel'></td></tr>");	
							resolveButton = $("<span class='glyphicon glyphicon-wrench icon has_tooltip resolveButton' title='Resolve This!'></span>");
							tr.find(".actionPanel").append(resolveButton);
							$("#discrep").append(tr);
						});
					});
					
					//each media discrep
					$("#discrep").append("<tr><th colspan=2 style='text-align: right; background :#000; color: #fff;'>MEDIA TEMPLATE DISCREPENCIES</th></tr>");	
					$.each(retX.media, function(attName, items){

						
						$("#discrep").append("<tr data-type='media' data-attr='"+attName+"'><th colspan=2 style='text-align: right'><span class='glyphicon glyphicon-wrench icon has_tooltip resolveAllButton' title='Resolve All!'></span> "+attName+"</th></tr>");	
						$.each(items, function(index, val){
							project = that.data.projects[val.projId];
							media = that.data.projects[val.projId].media[val.mediaId];
							$("#discrep").append("<tr class='discrep_item "+colors[val.code]+"' data-media-key='"+val.mediaId+"' data-media-index='"+val.mediaIndex+"' data-type='media' data-key='"+val.projId+"' data-error-code="+val.code+" data-attr='"+attName+"'><td><a href='project.php?id="+project.id+"' class='noLink'>"+project.id+": "+project.title+" (Media #"+val.mediaIndex+")"+"</a></td><td>"+val.msg+" ("+media.src+")</td><td class='actionPanel'><span class='glyphicon glyphicon-wrench icon has_tooltip resolveButton' title='Resolve This!'></span></td></tr>");	
						});
					});
					
					//EVENTS
					$("#discrep").find("tr.discrep_item").on("mouseover", function(e){
						$(this).find(".actionPanel").show();
					});
					$("#discrep").find("tr.discrep_item").on("mouseout", function(e){
						$(this).find(".actionPanel").hide();
					});
					
					//multiple instances
					$("#discrep").find(".resolveAllButton").on("click", function(){
						tr = $(this).closest("tr");
						attr = tr.attr("data-attr");
						tmplType =  tr.attr("data-type");
						items = $(".discrep_item[data-attr='"+attr+"'][data-type='"+tmplType+"']");
						//check first one for type..
						firstErrorCode = items.first().attr("data-error-code");
						
						switch (firstErrorCode){
							case errorCodes.NOT_IN_TEMPLATE+"":
								that.modal({
									subject : "Resolve All : "+attr,
									description : "<div class='errorBox'></div><div class='successBox'></div><p>Resolve Multiple Projects all at once. THe errors associated with this attribute is that is is not found in the  "+tmplType+" template. To resolve, we can just remove the attribute from the projects. (Alternatively, You can also just add the attribute to the "+tmplType+" template).</p><p><b>"+items.size()+" projects found</b></p>",
									buttons : {
										"Resolve_All" : {
											type : "custom",
											clicked : function(me){
												//assemble ajax actions
												$(me.el).find(".successBox,.errorBox").empty().hide();
												
												//setup params
												params = {
													action : (tmplType!="media") ? "deleteAttributes" : "deleteMediaAttributes",
													data : {}
												}
												
												
												//iterate through each
												$(items).each(function(){
													tr = $(this);
													errorType = tr.attr("data-error-code");
													projId = tr.attr("data-key");
													tmplType_item =  tr.attr("data-type");
													proj = that.data.projects[projId];
													attr_item = tr.attr("data-attr");
													if (tmplType_item=="media"){
														media = that.data.projects[projId].media[tr.attr("data-media-key")]; 
														mediaKey = tr.attr("data-media-key");
														mediaIndex = tr.attr("data-media-index");
													}
													params.data[projId] = (params.data[projId]!=undefined) ? params.data[projId] : {};
													
													if (tmplType!="media") 
														params.data[projId] = [attr] 
													else{
														 params.data[projId][mediaKey] = [attr];
													}
							
												})
											
											
												//perform tasks
												that.saveFile(that.src, {
													action : params.action,
													data : params.data,
													onError : function(msg){
														$(me.el).find(".errorBox").html(msg).show();
													},
													onSuccess : function(respObj){
														//show success
														$(me.el).find(".successBox").html("Success").show();
														me.close();
														//remove dom
														items.remove();
													}
												});
												
												
						
											}
										}
										,Cancel : {
											type : "cancel"
										}
									}
								});
								break;
							case errorCodes.NOT_VALID+"":
							case errorCodes.MISSING_ATTRIBUTE+"":
								that.modal({
									subject : "Resolve All : "+attr,
									description : "<div class='errorBox'></div><div class='successBox'></div><p>Resolve Multiple Projects all at once. THe errors associated with this attribute is it either missing or not valid in the project. We can provide a default value for those projects to at least get rid of the discrepancy.</p><p><b>"+items.size()+" projects found</b></p><p><br><input type='text' id='discrep_newValue' class='form-control halfWidth' placeholder='New Value'></p>",
									buttons : {
										"Resolve_All" : {
											type : "custom",
											clicked : function(me){
												//assemble ajax actions
												$(me.el).find(".successBox,.errorBox").empty().hide();
												params = {
													action : (tmplType!="media") ? "editAttributes" : "editMediaAttributes",
													data : {}
												}
												//preval												
												tmpl=that.data.template[tmplType][attr];
												type = that.attributeTypes[tmpl.type]; 
												newVal = $(me.el).find("#discrep_newValue").val();
												
												if (tmpl.required){
													if (that.trim(newVal)==""){
														$(me.el).find(".errorBox").html("For this attribute, a non-blank value is required").show();
														return false;
													}
												} 
												if (type.validate){
													//do validation
													res = type.validate(newVal);
													if (res.error){
														$(me.el).find(".errorBox").html(res.error).show();
														return false;
													}
;
												}
												
												//setup params
												params = {
													action : (tmplType!="media") ? "editAttributes" : "editMediaAttributes",
													data : {}
												}
												
												//iterate through each
												$(items).each(function(){
													tr = $(this);
													errorType = tr.attr("data-error-code");
													projId = tr.attr("data-key");
													tmplType_item =  tr.attr("data-type");
													proj = that.data.projects[projId];
													attr_item = tr.attr("data-attr");
													if (tmplType_item=="media"){
														media = that.data.projects[projId].media[tr.attr("data-media-key")]; 
														mediaKey = tr.attr("data-media-key");
														mediaIndex = tr.attr("data-media-index");
													}
													params.data[projId] = (params.data[projId]!=undefined) ? params.data[projId] : {};
													if (tmplType!="media") 
														params.data[projId][attr_item] =  newVal; 
													else{
														params.data[projId][mediaKey] = {};
														 params.data[projId][mediaKey][attr_item] = newVal;
													}
												
												})
											
											
												
												//perform tasks
												that.saveFile(that.src, {
													action : params.action,
													data : params.data,
													onError : function(msg){
														$(me.el).find(".errorBox").html(msg).show();
													},
													onSuccess : function(respObj){
														//show success
														$(me.el).find(".successBox").html("Success").show();
														me.close();
														//remove dom
														items.remove();
													}
												});
												
												
						
											}
										}
										,Cancel : {
											type : "cancel"
										}
									}
								});
								break;
						}
						
					});
					
					//single instances
					$("#discrep").find(".resolveButton").on("click", function(){
						tr = $(this).closest(".discrep_item");
						errorType = tr.attr("data-error-code");
						projId = tr.attr("data-key");
						tmplType =  tr.attr("data-type");
						proj = that.data.projects[projId];
						attr = tr.attr("data-attr");
						if (tmplType=="media"){
							media = that.data.projects[projId].media[tr.attr("data-media-key")]; 
							mediaKey = tr.attr("data-media-key");
							mediaIndex = tr.attr("data-media-index");
						}
						switch (errorType){
							case errorCodes.NOT_IN_TEMPLATE+"":
								that.modal({
									subject : "Resolve Discrepancy : Not in Template",
									description : "<div class='errorBox'></div><div class='successBox'></div>The attribute <b>"+attr+"</b> is not in the template but is in <b>"+proj.title+"</b>. To resolve, the attribute would just be removed from project.",
									buttons : {
										"Remove_Attribute" : {
											type : "custom",
											clicked : function(me){
												//assemble ajax actions
												$(me.el).find(".successBox,.errorBox").empty().hide();
												params = {
													action : (tmplType!="media") ? "deleteAttributes" : "deleteMediaAttributes",
													data : {}
												}
												if (tmplType!="media") 
													params.data[projId] = [attr] 
												else{
													 params.data[projId] = {};
													 params.data[projId][mediaKey] = [attr];
												}
							
												//perform tasks
												that.saveFile(that.src, {
													action : params.action,
													data : params.data,
													onError : function(msg){
														$(me.el).find(".errorBox").html(msg).show();
													},
													onSuccess : function(respObj){
														//show success
														$(me.el).find(".successBox").html("Success").show();
														me.close();
														//remove dom
														tr.remove();
													}
												});
												
											}
										},
										"Cancel" : {
											type : "cancel"
										}
									}
								});
								break;
							case errorCodes.NOT_VALID+"":
							case errorCodes.MISSING_ATTRIBUTE+"":
										
								
								that.modal({
									subject : "Resolve Discrepancy : "+((errorType==(errorCodes.NOT_VALID+"")) ? "Not Valid Value" : "Missing Attribute" ),
									description : "<div class='errorBox'></div><div class='successBox'></div>The "+((tmplType=="media") ? "media " : "")+" attribute <b>"+attr+"</b> is part of the template but not "+((errorType==(errorCodes.NOT_VALID+"")) ? "valid" : "found" )+" in <b>"+proj.title+((tmplType=="media") ? " (Media #"+mediaIndex+")" : "")+"</b>. To resolve, the attribute would  be  "+((errorType==(errorCodes.NOT_VALID+"")) ? "replaced in" : "added to" )+" "+((tmplType=="media") ? proj.title+"(Media #"+mediaIndex+")" : "the project")+" with provided default value.<p><br><input type='text' id='discrep_newValue' class='form-control halfWidth' placeholder='New Value'></p>",
									buttons : {
										"Update" : {
											type : "custom",
											clicked : function(me){
												//assemble ajax actions
												$(me.el).find(".successBox,.errorBox").empty().hide();
												params = {
													action : (tmplType!="media") ? "editAttributes" : "editMediaAttributes",
													data : {}
												}
												//preval												
												tmpl=that.data.template[tmplType][attr];
												type = that.attributeTypes[tmpl.type]; 
												newVal = $(me.el).find("#discrep_newValue").val();
												
												if (tmpl.required){
													if (that.trim(newVal)==""){
														$(me.el).find(".errorBox").html("For this attribute, a non-blank value is required").show();
														return false;
													}
												} 
												if (type.validate){
													//do validation
													res = type.validate(newVal);
													if (res.error){
														$(me.el).find(".errorBox").html(res.error).show();
														return false;
													}
;
												}
												//special validators for project
												if (tmplType = "project"){
													switch(attr){
														case "cleanUrl":
															if (!that.isUnique('cleanUrl', newVal, that.data.projects)){
																$(me.el).find(".errorBox").html("For this attribute, it must be unique..one with this already exists.").show();
																return false;
															}
															break;
													}
												}
												
												 params.data[projId] = {};
												if (tmplType!="media") 
													params.data[projId][attr] =  newVal; 
												else{
													params.data[projId][mediaKey] = {};
													 params.data[projId][mediaKey][attr] = newVal;
												}
												
												
												//perform tasks
												that.saveFile(that.src, {
													action : params.action,
													data : params.data,
													onError : function(msg){
														$(me.el).find(".errorBox").html(msg).show();
													},
													onSuccess : function(respObj){
														//show success
														$(me.el).find(".successBox").html("Success").show();
														me.close();
														//remove dom
														tr.remove();
													}
												});
												
												
											}
										},
										"Cancel" : {
											type : "cancel"
										}
									}
								});
								break;
						}
					});

					/*
					//debugging..
					that.setDebugBox(retX, "debug-discrep", {
						top: "20px",
						left: "auto",
						right : "20px"
					});
					*/
				
				

				}
			},
			//<---------END CMCM FILE UPLOADER FUNCS-------------->//
			
			//transforms elements into dropdowns
			dropdown : function(el, ddId, obj, opts){
				button = $(el).clone(true, true);
				opts = (opts!=undefined) ? opts : {};
				opts = {
					menuCSS :  (opts.menuCSS!=undefined) ? opts.menuCSS : false,
					up : (opts.up!=undefined) ? opts.up : false,
					keepMenuOpen : (opts.keepMenuOpen!=undefined) ? opts.keepMenuOpen : false
				}
		       	ddWrapper = $('<div class="dropdown  media-dropdown"></div>');
		       	if (opts.up)
		       		ddWrapper.addClass("dropup");
		       	else
		       		ddWrapper.addClass("tipDown");
		      
	
	        	ddWrapper.attr("id", "dd-"+ddId);
	        	button.attr("data-toggle", "dropdown");
	        	ddButton = button;
	        	ddWrapper.append(ddButton);
	        	ddMenu = $('<ul class="dropdown-menu"></ul>');
	        	if (opts.keepMenuOpen){
		        	ddMenu.on("click", function(e){
		        		e.preventDefault();
		        		e.stopPropagation();
		        	});
	        	}
	        	if (opts.menuCSS){
	        		ddMenu.css(opts.menuCSS);
	        	}
	        	$.each(obj, function(k,item){
	        		
		        	li = $("<li></li>");
		        	li.append(item);
		        	if ($(item).hasClass('noCloseOnClick')){
		        		item.on("click", function(e){
		        			e.stopPropagation();
		        		});
		        	}
		        	ddMenu.append(li);
	        	});
	        	ddWrapper.append(ddMenu);	
	        	$(el).html(" ");
	        	$(el).append(ddWrapper);	
			},
			//switch toggle (better looking radios)
			switcher : function(desiredId, opts){
				btnGroup = $("<div class='switcher btn-group'  data-value=''></div>");
				btnGroup.attr("id", desiredId);
				btnGroup.attr("data-toggle", desiredId);
			
				$.each(opts.options, function(k,v){
					label = $("<label class='btn btn-primary'></label>");
					input = $('<input type="radio"name="setup_fileOption">');
					input.attr("id", desiredId+"_"+k);
					input.attr("value", v.value);
					if (v.default){
						btnGroup.attr("data-value", v.value);
						input.attr("checked", true);
						label.addClass("active");
					}
					
					label.on("click", function(){
						$(this).closest(".btn-group").find(".active").removeClass("active");
						$(this).addClass("active");
						$(this).closest(".btn-group").attr("data-value", $(this).find("input").val());
						if (opts.onChange){
							opts.onChange($(this).find("input").val());
						}
					});
					
					label.append(input);
					label.append(v.text);
					btnGroup.append(label);
				});	
				return btnGroup;
			},
			modal : function(opts){
				
				var modal_bg = $("<div id='cmcm_modal_bg'></div>");
				var modal_content = $("<div id='cmcm_modal_content'></div>");
				var close = function(){
					modal_bg.remove();
				}
				
				 
				modal_content.on("click", function(e){
					e.stopPropagation();
					//e.preventDefault();
				});
				var subject = (opts.subject) ? opts.subject : "Attention";
				var description = (opts.description) ? opts.description : "";
				description = (typeof opts.description=="string") ? $(description) : description;
				closeButton = $("<span class='glyphicon glyphicon-remove icon modal_close' title='info'>");
				closeButton.on("click", close);
				modal_content.append(closeButton);
				modal_content.append($("<h3 style='margin-top:0px; margin-bottom: 20px'>"+subject+"</h3>"));
				descripEl = $("<div class='modal_description' style='margin:0px; padding:0px;'></div>");
				descripEl.append(description);
				modal_content.append(descripEl);
				
				//modal methods to send to custom functions
				var me = {
					close : function(){ close(); },
					el : "#cmcm_modal_content"
				}
				
				buttonWrapper = $("<p style='margin-top: 20px;margin-bottom:0px;'></p>");
				if (opts.buttons){
					$.each(opts.buttons, function(k,v){
						
						button = $("<button class='button'>"+k.replace("_", " ")+"</button>");
						switch (v.type){
							case "cancel":
								button.on("click", close);
								break;
							case "custom":
								button.on("click", function(){
									me.button = this;
									v.clicked(me);
								});
								break;
						}
						buttonWrapper.append(button);
						
					});
				}
				modal_content.append(buttonWrapper);
				//no padding and match content
				if (opts.boxDimensions){
					modal_content.css({
						width : opts.boxDimensions.width+"px",
						height : opts.boxDimensions.height+"px",
						border: 0,
						padding: 0,
						background : "none"
					});
					modal_content.find(".modal_close").css({color: "#fff", right : "0px", top: "0px"});
					modal_content.find("p,h3").css({color: "#fff",background : "none", margin:0, width: opts.boxDimensions.width+"px", padding:0, visibility : "hidden"});
					modal_content.find(".modal_description").css({border: "1px solid #c0c0c0", overflow: "hidden"});
				}
			
				modal_bg.append(modal_content);
				//top_orig = 20;
				modal_content.css({
					"margin-top" : "0%",
					"opacity" :0
				});

				
				modal_bg.on("click", close);
				$("body").append(modal_bg);
				
				//calculate top
				buffer = -20;
				topCalc = ($(window).height()-modal_content.height()+buffer)/2;
				topCalc = (topCalc < 0 ) ? 0 : topCalc;
				
				$("#cmcm_modal_content").animate({
					"opacity" : 1,
					"margin-top" :  topCalc+"px"
				},200);
				
				window.onresize = function(){
					//calculate top
					buffer = -20;
					topCalc = ($(window).height()-modal_content.height()+buffer)/2;
					topCalc = (topCalc < 0 ) ? 0 : topCalc;		
					$("#cmcm_modal_content").stop();
					$("#cmcm_modal_content").clearQueue();		
					$("#cmcm_modal_content").animate({
						"margin-top" :  topCalc+"px"
					},200);
					
					//reset
					$("#cmcm_modal_content").on("remove", function(){
						window.onresize = function(){return false};
					});
				
				};
				
			},
			updateTooltip : function(el, newValue){
				if (el && newValue){
					$(el).attr("title", newValue);
				}
				
				$(".has_tooltip").tooltip({ 
						track: true, 
						tooltipClass : "jdatTooltip",
						show : {duration : 300},
						hide : {duration :50},
						content: function () {
							 return $(this).prop('title');
					    }
					});
			},
			//this only handles templates
			handleChanges : function(){
				var that = this;
				//attempt to save templates
				this.saveFile(this.src, {
					action : "updateTemplates",
					onSuccess : function(respObj){
						//update data
						tmpData = $.parseJSON(respObj.data.data);
						that.data.projects = tmpData.projects;
						//update discrep
						$("#discrep").empty();
						that.getDiscrepancies();
					}
				});	

			},
			setStatus : function(a, msg){
				var msg = (msg != undefined) ? msg : "";
				//hide all
				$("#loading .statusIndicator").hide();
				//show requested one
				if (a!="none"){
					$("#loading ."+a+"Icon").show("fade");
					//reinitate tooltip
					this.updateTooltip("#loading ."+a+"Icon", msg);
				}
				switch (a){
					case "success":
						$("#loading .loadingIcon").hide("fade");
						setTimeout(function(){
							$("#loading ."+a+"Icon").hide("fade");
							
						}, 5000);
					break;
				}
			},
			//<------BELOW ARE RECYCLED FROM JDAT------------>//
			getEndpoint : function(a, additional){
			if (additional!=undefined){
				$.each(additional, function(k,v){
					a+="&"+k+"="+v;
				});
			}
			return this.endpoint+"?a="+a;
			},
			load : function(afterLoad){
				var that = this;
				//ajax call
				this.ajax(this.getEndpoint("load", {f : "config.json", "skey" : cmcm.kelly}), {
					success : function(respObj){
						//we get @respobj.data.config and @respobj.data.data
						that.src =  respObj.data.config.src;
						that.data = respObj.data.data;
						afterLoad();
					},
					error : function(msg){
						that.src = "Not Found";
						that.data = {};
						that.errorHandler(msg);
						that.modal({
							subject : "Fatal Error",
							description : $("<p>Error: "+msg+"</p>")
						});
					}
				});
				
			},
			errorHandler : function(msg){
				this.setStatus("error", msg);	
			},
			saveFile : function(f, opts){
				//options
				opts = (opts!=undefined) ? opts : {};
				opts.noSuccessIndicator = (opts.noSuccessIndicator != undefined) ? opts.noSuccessIndicator : false;
				opts.action = (opts.action != undefined) ? opts.action : "save";
				opts.data = (opts.data != undefined) ? opts.data : "";
				switch (opts.action){
					//config Actions
					case "loadConfig":
						opts.data = "";
						break;
					case "addUser":
						opts.data = opts.data;
						break;
					case "deleteUser":
						opts.data = opts.data;
						break;
					case "login":
						opts.data = opts.data;
						break;
					//master save
					case "save":
						opts.data = this.data;
						break;
					//update templates
					case "updateTemplates":
						opts.data = this.data.template;
						break;
					//save an existing project
					case "saveProject":
						opts.data = this.data.projects[opts.projectId];
						break;
					//edit a project
					case "editProject":
						opts.data = this.draft;
						break;
					//delete projects
					case "deleteProjects":
						opts.data = opts.data;
						break;
					//edit projects' attributes
					case "editAttributes":
						opts.data = opts.data;
						break;
					//add a project
					case "addProject":
						opts.data = this.draft;
						break;
					//sort Projects
					case "sortProjects":
						opts.data = opts.list;
						break;
						
				}
				var that = this;

				//ajax call
				this.ajax(this.getEndpoint(opts.action, {"f": encodeURI(f), "skey" : cmcm.kelly}), {
					type : "POST",
					data : { data : encodeURI(json_encode(opts.data).replace(/'/g, "%27"))},
					loading : function(){
						that.setStatus("loading", "loading..");	
					},
					success : function(respObj){
						if (!opts.noSuccessIndicator){
							success_msg = (respObj.success_msg) ? respObj.success_msg : "Saved Changes";
							if (respObj.data)
								success_msg = (respObj.data.success_msg) ? respObj.data.success_msg : success_msg;
							that.setStatus("success", success_msg);
						}else{
								that.setStatus("none");
						}
						if (opts.onSuccess){
							opts.onSuccess(respObj);
						}
					},
					done : function(){
						if (opts.onDone){
							opts.onDone();
						}	
					},
					error : function(msg){
						if (msg=="Not authorized"){
							//show dialog, and send request to update kelly
							that.modal({
								subject : "Not authorized",
								description : "<p>This page is no longer authorized to make changes. If it has been a while since you have been to this page, the session may have expired. An attempt will be made to start a new session (see result below, so try your action again! If the problem persists, reload the page or open the page in a new tab.<p>Reauthorized? :  <b id='notAuthorized_result'></b></p></p>",
								buttons : {
									ok : {
										type : "cancel"
									}
								}
							});
							that.saveFile("xxx", {
								action : 'refreshKelly',
								onError : function(msg){
									$("#notAuthorized_result").html(msg);
								},
								onSuccess : function(respObj){
									that.updateKelly(respObj.data);
									$("#notAuthorized_result").html("success");
								}
							});
						}
						that.errorHandler(msg);
						if (opts.onError){
							opts.onError(msg);
						}
					}
				});
			},
			eventStream : function(f, opts){
				var that = this;
				opts = (opt!=undefined) ? opts : {};
				//callbacks
				opts.onMessage = (opts.onMessage!=undefined) ? opts.onMessage : function(respObj){}; //on message from server
				opts.onError   = (opts.onError!=undefined)   ? opts.onError   : function(msg){}; //on error
				opts.onOpen    = (opts.onOpen!=undefined)    ? opts.onOpen    : function(){}; //on connection
				opts.onDone    = (opts.onDone!=undefined)    ? opts.onDone    : function(){}; //on finished event
				
				server = this.getEndpoint(opts.action, {"f": encodeURI(f), "skey" : cmcm.kelly});
			     
			     that.es = new EventSource(server);
			      var listener = function (event) {
						var type = event.type;
						switch (event.type){
							   //connect to server
							   case "open":
						   		opts.onOpen();
						   		 break;
						   //error..had to disconnect
						   case "error":
						   		opts.onError();
						   		 break;
						  //connected..received a message
						   case "message":
						        try{
							        dataObj = $.parseJSON(event.data);
						        }catch(e){
						        	 opts.onError("Parse Error!");
						        }
						        opts.onMessage(dataObj);
						        break;
						}
						opts.onDone();
			      };
			      
			      that.es.addEventListener("open", listener);
			      that.es.addEventListener("message", listener);
			      that.es.addEventListener("error", listener);
			},
			ajax : function(url, opts){
				
				var that = this;
				
				//set callbacks
				var settings  = {
					type : (opts.type != undefined) ? opts.type : "GET",
					sel : (opts.sel != undefined) ? opts.sel : false,
					context : (opts.context != undefined) ? opts.context : this,
					data : (opts.data != undefined) ? opts.data : "",
					done : (opts.done != undefined) ? opts.done : function(e){},
					loading :  (opts.loading != undefined) ? opts.loading : function(e){},
					error : (opts.error != undefined) ? opts.error : function(e){},
					success :  (opts.success != undefined) ? opts.success : function(e){},
				};
				
				//set loading
				settings.loading();
				
	
				
				$.ajax({
						url : url,
						type : settings.type,
						context : settings.context,
						data : settings.data
					}).done(function(json){
						var sel = opts.sel;
						try{
							data = $.parseJSON(json);
							data['json'] = json;
						//<---ajax exception! probably server error
						}catch(e){
							settings.error("Response could not be resolved: "+that.htmlEntities(json)+"/////Also, here is exception message: "+e.message);
							data = {};
						}
						
						if (data['success']){
							settings.success(data);
						}
						else if (data['error']){
							//if this error happens...we can run fbLogin and attempt this again
							settings.error(data['error']);
						}
						
						settings.done(json);
					//<----ajax FAIL
					}).fail(function(e){
						settings.done(that.htmlEntities(e.responseText));
						settings.error('Failed request: '+that.htmlEntities(e.responseText));	
					});
	
			},
			$_GET : function(variable, remove) { 
				var query = window.location.search.substring(1); 

				if (remove!=undefined){
				   var rtn = "",
						param,
						params_arr = [],
						queryString = query;
					if (queryString !== "") {
						params_arr = queryString.split("&");
						for (var i = params_arr.length - 1; i >= 0; i -= 1) {
							param = params_arr[i].split("=")[0];
							if (param === variable) {
								params_arr.splice(i, 1);
							}
						}
						window.location.search =  "?" + params_arr.join("&");
					}
					
				}else{
					var vars = query.split("&"); 
					for (var i = 0; i < vars.length; i++) { 
						var pair = vars[i].split("="); 
						if (pair[0] == variable) { 
						return unescape(pair[1]); 
						} 
					} 
					return false; 
				}
				
			},
			parseNum : function(n){
				//integer
				if (n != "" && !isNaN(n) && Math.round(n) == n){
					return parseInt(n);
				//float
				}else if(n != ""){
					return parseFloat(n);
				}else{
					return n;
				}
			},
			htmlEntities : function(str, single_quote) {
				if (!isNaN(str))
					return this.parseNum(str);
				if (single_quote){
					str = String(str).replace(/'/g, '&#039;');
				}
				return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			},
			unHtmlEntities : function(str, single_quote) {
				if (!isNaN(str))
					return this.parseNum(str);
				if (single_quote){
					str = String(str).replace(/&#039;/g, '\'');
				}
				return String(str).replace(/(?:&amp;)/g, '&').replace(/(?:&lt;)/g, '<').replace(/(?:&gt;)/g, '>').replace(/(?:&quot;)/g, '"');
			}
			
		}
