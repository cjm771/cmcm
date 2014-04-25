
		var cmcm = {
			editMode : 0,
			src : 'data.json',
			endpoint : 'php/ajax.php',
			jqXHR : {},
			jqXHR_count : 0,
			debugInterval : null,
			draft : {},
			data : {
					/*
					mediaFolder : "images/",
					template : {
						project : {
							id : {
								type : "int",
								required : 1,
								protected : 1
							},
							
							published : {
								type : "bool",
								required : 1,
								protected : 1
							},
							added : {
								type : "timestamp",
								required : 1,
								protected : 1
							},
							title : {
								type : "text",
								required : 1,
								protected : 1,
							},
							coverImage : {
								type : "int",
								protected : 1,
							},
							description : {
								type : "text",
							}

						},
						media : {
							src : {
								type : "text",
								required : 1,
								protected : 1
							},
							visible : {
								type : "bool",
								required : 1,
								protected : 1
							},
							type : {
								type : "choice-image,video",
								required : 1,
								protected : 1
							},
							caption : {
								type : "text"
							}
						}
					},
					projects : [
						{
							id :0,
							feature : 0,
							title : "Test Project 1",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-001.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project 2 ",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-002.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project3= 3",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-003.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-004.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-005.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-006.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-007.jpg",	
									caption : "This is image 1"
								},
								
							]
						},
						{
							id :0,
							feature : 0,
							title : "Test Project",
							description : "This is a Description",
							media : [
								{
									type: "image",
									src : "image-008.jpg",	
									caption : "This is image 1"
								},
								
							]
						}
					
			
					]
					*/
			},
			attributeTypes : {
				bool : {
					edit : function(obj){
						i =  $("<input type='checkbox' class='attr-input' >");
						i.attr("checked", (obj.value) ? true : false);
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
					}
				},
				string : {
					edit : function(obj){
						i =  $("<input type='text' class='form-control attr-input'>");
						//value
						i.val(obj.value);
						//change event
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
					validate : function(){
						return {success : 1};
					}
				},
				int : {
					edit : function(obj){
						i =  $("<input type='text' class='form-control attr-input'>");
						//change event
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
						var reg = /^\d+$/;
						resp = {success : 1};
						if (!v.match(reg))
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
					 	addon = $('<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>'+
					 	'<input type="hidden" id="dtp_input1" value="2014-02-01 15:45:39">');
               
						
					
						
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
						ta =  $("<textarea class='form-control attr-input'></textarea>");
						ta.val(obj.value);
						if (obj.onChange){
							ta.on("keyup", function(e){
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
					extras : function(obj){
						//generate dom
						wpr =  $("<div></div>");
						input =  $("<input type='text' data-extra-attr='choices' data-extra-val='' class='form-control'>");
						input.on("keyup", function(e){
							$(this).attr("data-extra-val", cmcm.htmlEntities($(this).val()));
						});
						wpr.append(wpr);
						
						//extras should return obj with DOM @dom and array of attributenames @data
						ret = {
							dom : wpr,
							data : [
								"choices"
							]
						};
						return ret;
					}
				} //<--end choice type
			}, //<-- end attribute types
			init : function(afterLoad){
				//
				var that= this;
				this.loadFile(this.src, function(){
					//page specific init
					afterLoad();
					//debugging
					that.renderDebugBox();
					//tooltips
					that.updateTooltip();
					//save/loading dialog
					  $('#loading_wpr').affix({
					    offset: {
					      top: function(){return $("#header").offset().top+$("#header").height()}
					      }
					  });
				});
				
			},
			
			//<----------DEBUG FUNCS----------------->//	
			//regular debug box refreshes every 500 ms
			renderDebugBox : function(){
				var that = this;
				box = $("<pre class='debug' id='debugBox'></pre>");
				box.append(JSON.stringify(that.draft,null, 4));
				$("body").append(box);
				this.debugInterval = setInterval(function(){
					
					$("#debugBox").html(JSON.stringify(that.draft,null, 4));
				
				}, 500);
			},
			//special debug box @id= id attr and @css = css for box
			setDebugBox : function(obj, id, css){
				
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
			
			formatProject : function(){
				var tr;
				var that = this;
				var parentEl = "#proj_info";
				
				
				//<---NEW PROJECT---->
				
				//lets populate project template first
				$.each(this.data.template.project, function(k,v){
					that.draft[k] = "";
					if (!v.hidden){
						//default values
						def = "";
						
						def = (that.attributeTypes[v.type].def!=undefined) ? that.attributeTypes[v.type].def() : def;
						def = (that.data.template.project[k].def!=undefined) ? that.data.template.project[k].def : def;
						tr = that._projectRow(k,def);
					
						//default to data obj
						that.edit(k,def,that.draft, 'v');
						$(parentEl).append(tr);
					}
							
				});
				//blank media
				this.draft.media = {};
	
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
			renderProjectsGrid : function(){
				var that = this;
				$.each(this.data.projects, function(k,project){
					var wpr = $("<div class='img_wpr has_tooltip' title='"+project.title+"' data-placement='top'></div>");
					//attach image
					wpr.append("<img src='"+that.data.mediaFolder+project.media[project.feature].src+"'>");
					var panel =  $("<div class='img_panel'></div>");
					var deleteButton = $("<i class='glyphicon glyphicon-remove white icon'></i>");
					panel.append(deleteButton);
					wpr.append(panel);
					wpr.on("mouseover", function(e){
						$(this).find(".img_panel").show();
					});
					wpr.on("mouseout", function(e){
						$(this).find(".img_panel").hide();
					});
					$(".img_grid").append(wpr);	
					
					//$(".img_wpr").tooltip();
					$(".img_grid").sortable();
				});
					
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
						 dataObj[ k ] = that.htmlEntities(newVal);
						break;
				}
			},
			//delete element of object
			remove : function(k, dataObj){
				delete dataObj[k];
			},
			//handle sorting of object
			resort  : function(newKeys, dataObj){
				var newObj = {};
				$.each(newKeys, function(k,v){
					newObj[v] = dataObj[v];
				});
				$.each(dataObj, function(k,v){
					delete dataObj[k];
				});
				$.each(newObj, function(k,v){
					dataObj[k] = v;
				});
				dataObj = newObj;
			},
			//<-----------PROJECT UTILS----------------->//
			//validates draft
			validator : function(opts){
				$(".validate-error").removeClass('validate-error');
				var that = this;
				errors = [];
				$.each(this.draft, function(k,v){
					switch (k){
						case "media":
							mediaCount = 0;
							$.each(that.draft.media, function(mediaId, mediaItem){
								mediaCount++;
								$.each(mediaItem, function(k, v){
									//type check
									if (that.data.template.media[k]){
										tmpl = that.data.template.media[k];
										if (tmpl.required && v.trim()==""){
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
								if (tmpl.required && v.trim()==""){
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
				}else{
					//do success
				}
			},
			//<----------TEMPLATE UTILS----------------->//			
			getUniqueKey : function(name, dataObj){
				uName = name;
				count = 0;
				while (dataObj[uName]!=undefined){
					count++;
					uName = name+"_"+count;
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
			//for media resorting..
			resortMedia : function(el){
				var newKeys = [];
				
				
				$(el).find(".media_wpr").each(function(){
					newKeys.push($(this).attr("data-id"));
				});
				
				this.resort(newKeys, this.draft.media);
				//this.handleChanges();
				
			},
			toggleTemplateEdit : function(el){

				var that = this;
			
				this.editMode=1;
				tr = $(el).parent().parent();
				templateType = tr.parent().parent().attr("data-tmpl");
				attributeId = tr.find(".name").attr("data-id");
				trBackup = tr.clone(true, true);
				
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
					typeEdit.append("<option "+select+">"+k+"</option>");
				});
				
				
				tr.find(".name,.type").html("");
				tr.find(".name").append(nameEdit);
				
				tr.find(".type").append(typeEdit);
				isRequired = (this.data.template[templateType][tr.find(".name").attr("data-id")].required) ? "checked=true" : "";
				req = $("<span class='req_checkbox'>Required? <input type='checkbox' "+isRequired+"></span>");
				tr.find(".type").append(req);
				//any extra parameters?
				
				
				//append cancel and ok to actionPanel
				panel = tr.find(".actionPanel");
				panelBackup = panel.clone(true, true);
				
				panel.parent().off("mouseover");
				panel.parent().off("mouseout");
	
				cancelButton =  $("<span class='glyphicon glyphicon-ban-circle icon' title='cancel'></span>");
				
				
				okButton =  $("<span class='glyphicon glyphicon-ok icon' title='ok'></span>");
				
	
				okButton.on("click", function(){
					name = tr.find(".name input").val();
					nameEl = trBackup.find(".name");
					oldName = nameEl.attr("data-id");
					
					//make sure its unique
					if (name!=oldName){
						name = that.getUniqueKey(name, that.data.template[templateType]);
						//update template
						that.changeKeyTemplate(name, oldName, that.data.template[templateType]);
					}
					
					type = tr.find(".type select").val();
					required = tr.find(".type input").is(":checked");
					name = name.replace(/\s/g, "_");
				
					
					
			
					
					nameEl.attr("data-id", name);
					asterisk = (required) ? "*" : "";
					
					nameEl.html(nameEl.attr("data-id")+asterisk);
					
					trBackup.find(".type").html(type);
					
					tr.replaceWith(trBackup);
					
					//save to data
			//		that.data.template[templateType][nameEl.attr("data-id")].required = (required) ? 1 : 0;
			
					that.changeValueTemplate(nameEl.attr("data-id"), that.data.template[templateType], {
						required : (required) ? 1 : 0,
						type : type
					});
					that.editMode = 0;
				});
				
				cancelButton.on("click", function(){
						tr.replaceWith(trBackup);
						that.editMode = 0;
				});
				
				panel.html("");
				panel.append(cancelButton).append(okButton);
				
			},
			//<------REUSED OBJS------------------->//
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
								value : v,
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
					
					
					panel = $("<td class='actionPanel'></td>");
					deleteButton = $("<span class='glyphicon glyphicon-remove icon' title='remove'></span>");
					deleteButton.click(function(){
					
					 that.modal({
						 subject : "Confirm Deletion",
						 description : "Are you sure you want to delete the <b>"+k+"</b> attribute?",
						 buttons : {
							 delete : {
								 type : "custom",
								 clicked : function(me){
								 	that.removeFromTemplate(k,"#"+$(tmplEl).attr("id"));
									 me.close();
								 },
							 },
							 cancel : {
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
					tr.append(panel);
					
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
					"image" : ["image/jpeg", "image/jpg", "image/gif", "image/png"]
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
		        fileInput =  $('<input id="fileupload" type="file" name="files[]" data-url="lib/blueimp-jqueryFileUpload/" accept="image/gif, image/jpeg, image/png"  multiple>');
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
				        
			        	
			        	//store data
			        	data.context.attr("data-deleteUrl", file.deleteUrl);
			        	data.context.attr("data-name", file.name);
			        	
			        	//BLANK MEDIA
			        	//add blank media tmpl to draft
			        	newMedia = {};
			        	$.each(that.data.template.media, function(k,v){
				        	newMedia[k]="";
			        	})
			        	//add to data obj
			        	that.add(data.context.attr("data-id"), newMedia, that.draft.media);
			        	that.multiedit({
				        	visible : 1,
				        	type : "image",
				        	src : that.data.mediaFolder+file.name
			        	}, that.draft.media[data.context.attr("data-id")]);
			        	
			        	data.context.find("img").css({
				        	opacity : 1
			        	});
			        	data.context.find("img").attr("src",file.url);
			        	
			        	
			        	data.context.find(".progress_wpr").css('display', 'none');
			        	
			        	//delete button...do itss
			        	panel = data.context.find(".img_panel");
			        	panel.html("");
			        	
			        	deleteButton = $("<i class='glyphicon glyphicon-remove white icon has_tooltip' title='Delete'></i>");
			        	
			        	deleteButton.on("click", function(){
			        	
			        		mWpr = $(this).closest(".media_wpr");
				        	$.ajax({
							    url: mWpr.attr("data-deleteUrl"),
							    type: 'POST',
							    success: function(result) {
							    		result = $.parseJSON(result);
							    		/*
							    		result obj..
							    			[img name] : true or false (if deleted)
							    		*/
							    		if (result[mWpr.attr("data-name")]==true){
								    		//remove from draft media data
								    		that.remove(mWpr.attr("data-id"), that.draft.media);
								    		if (that.draft.coverImage == mWpr.attr("data-id")){
									    		that.draft.coverImage = "";
								    		}
								    		//should probably change featured
								    		//remove from dom
								    		that.uploadHandlerResp.remove(mWpr,1);
							    		}else{
								    		alert("Could not delete");
							    		}
							        	that.setDebugBox(result);
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
											default:
												onChangeFunc = function(o){
												onChangeFunc_def(o);
												};
												break;		
										}
										
										val = type.edit({
											key : k,
											value : v,
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

						
			        	that.updateTooltip();
			        }
		        });
		        
		        
		       $("#media_file_input").append(fileInput);
		      

			},
			//<---------END CMCM FILE UPLOADER FUNCS-------------->//
			
			
			modal : function(opts){
				
				var modal_bg = $("<div id='cmcm_modal_bg'></div>");
				var modal_content = $("<div id='cmcm_modal_content'></div>");
				var close = function(){
					modal_bg.remove();
				}
				
				 
				modal_content.on("click", function(e){
					e.stopPropagation();
					e.preventDefault();
				});
				var subject = (opts.subject) ? opts.subject : "Attention";
				var description = (opts.description) ? opts.description : "";
				closeButton = $("<span class='glyphicon glyphicon-remove icon modal_close' title='info'>");
				closeButton.on("click", close);
				modal_content.append(closeButton);
				modal_content.append($("<h3 style='margin-top:0px'>"+subject+"</h3>"));
				modal_content.append($("<p>"+description+"</p>"));
				
				//buttons
				/*
				modal_content.append($("<button>Delete</button>"));
				cancelButton = $("<button class='btn-cancel'>Cancel</button>");
				cancelButton.on("click", close);
				modal_content.append(cancelButton);
				*/
				
				//modal methods to send to custom functions
				var me = {
					close : function(){ close(); }
				}
				
				if (opts.buttons){
					$.each(opts.buttons, function(k,v){
						
						button = $("<button>"+k+"</button>");
						switch (v.type){
							case "cancel":
								button.on("click", close);
								break;
							case "custom":
								button.on("click", function(){
									v.clicked(me);
								});
								break;
						}
						modal_content.append(button);
					});
				}
				
				modal_bg.append(modal_content);
			
				
				
				modal_bg.on("click", close);
				$("body").append(modal_bg);
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
			handleChanges : function(){
				//attempt to save
				this.saveFile(this.src);	
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
					a+="&"+k+"="+v
				});
			}
			return this.endpoint+"?a="+a;
			},
			loadFile : function(f, afterLoad){
				var that = this;
				//ajax call
				this.ajax(this.getEndpoint("load", {"f": encodeURI(f)}), {
					success : function(respObj){
						/*
						utils.data = respObj.data;
						that.render();
						that.setFileName(f);
						// Deep copy
						that.data_saved = that.clone(that.data);
						that.checkUnsavedChanges();
						*/
						that.data = respObj.data;
						afterLoad();
					},
					error : function(msg){
						that.errorHandler(msg);
					}
				});
				
			},
			errorHandler : function(msg){
				this.setStatus("error", msg);	
			},
			saveFile : function(f){
				var that = this;
				//ajax call
				this.ajax(this.getEndpoint("save", {"f": encodeURI(f)}), {
					type : "POST",
					data : { data : encodeURI(json_encode(that.data))},
					loading : function(){
						that.setStatus("loading", "loading..");	
					},
					success : function(respObj){
						/*
						// Deep copy
						utils.data_saved = utils.clone(that.data);
						that.checkUnsavedChanges();
						utils.setFileName(filename_new);
						*/
						that.setStatus("success", "Saved Changes");
					},
					error : function(msg){
						that.errorHandler(msg);
					}
				});
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
							console.log(e.message);
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
							console.log(e.responseText);
						settings.error('Failed request: '+that.htmlEntities(e.responseText));	
					});
	
			},
			htmlEntities : function(str) {
				return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			}

		}
