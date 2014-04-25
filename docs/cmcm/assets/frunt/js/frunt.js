 var cmcm = {};
 
 function Frunt(CMCM_DIR, CMCM_URL, SITE_URL, dataFile, opts){
	 	var that = this;
		this.CONFIG_FILE = "data/config/config.json";
		this.DATA_DIR = "data/";
		
		this.data = {};
		this.templates = {};
		//set cmcm root
		this.CMCM_DIR = CMCM_DIR;
		this.CMCM_URL = CMCM_URL;
		this.SITE_URL = SITE_URL;
		
		//default opts
		this.opts = {
			file : dataFile,
			async : false,
			show_unpublished : false,
			show_unpublished_media : false,
			load_widget_lib : true,
			onLoad : function(){
				
			}
		};
		//set user opts
		if (opts)
			$.extend(this.opts, opts);
		
	
		
			
		//initialize the instance
		this.init = function(){
			
	
			//grab data
			 this.get(this.CMCM_DIR+this.DATA_DIR+this.opts['file'],  function(resp){
					that.data = resp;
					that.templates = that.data.template;
					
					//grab template keys
					keys = {
						'project' : Object.keys(that.templates.project),
						'media' :  Object.keys(that.templates.media)
					};
					
					//PROJECTS
					//remove unpublished projects
					if (!that.opts['show_unpublished']){
						$.each(that.data.projects, function(projId,proj){
							if (parseInt(proj['published'])==false){
								delete that.data.projects[projId];
							}	
						});
					}	
					//organize project att by templates
					that.multisort(that.data.projects, "asc", 3, keys['project']);
					//MEDIA
					//remove unpublished media and reorder by 
					
					$.each(that.data.projects, function(projId,proj){
						$.each(proj['media'], function(mediaId,media){
							if (!that.opts['show_unpublished_media']){
								if (parseInt(media['visible'])==false){
									delete that.data.projects[projId]["media"][mediaId];
								}
							}
						});
						
						that.multisort(that.data.projects[projId]["media"], "asc", 3, keys['media']);		
					});

					that.opts['onLoad'](that.data);
					
				}, that.opts['async']);
		
		}
		
	//same as sort, except same routine done to all child arrays
	this.multisort = function(dataObj, ascOrDesc, key, att){

		$.each(dataObj, function(id, obj){
			 dataObj[id] = that.sort(dataObj[id], ascOrDesc, key, att);
		});
	}
	
	/********************************
	 *   sort with several options 
	 *	(0: sort by values, 
	 *	 1 : sort by keys, 
	 *	 2: sort by given attribute, 
	 *	 3: sort by ordered keys)
	 ********************************/
	 this.sort = function(dataObj, ascOrDesc, key, att){
	 	
		key = (key==undefined) ? 0 : key;
		if (key==0){
			if (ascOrDesc=="asc"){
				dataObj = this._sort(dataObj);
			}else{
				dataObj = this.rsort(dataObj);
			}
			return dataObj;
		//by attribute
		}else if(key==1){
			if (ascOrDesc=="asc"){
				dataObj =  this.ksort(dataObj);
			}else{
				dataObj = this.krsort(dataObj);
			}
			return dataObj;
		
		}else if (key==2){
		
		
			newObj = {};
			keys  = Object.keys(dataObj);
			keys.sort(function(a, b){dataObj[a][att].toLowerCase().localeCompare(dataObj[b][att].toLowerCase());});
			$.each(keys, function(index, val){
				newObj[val] = dataObj[val];
			});
			dataObj = newObj;
			//usort(dataObj, sortByAtt);
			if (ascOrDesc=="desc"){
				dataObj = that.reverse(dataObj);
			}
			return dataObj;
		//by array of new order keys
		}else if (key==3){
			
			newArr ={};
			oldArr = dataObj;
			$.each(att, function(i, key){
				if (oldArr[key]!=undefined){
					newArr[key] = oldArr[key]; 
					delete oldArr[key];
				}
			});
			//sort remaining
			
			if (ascOrDesc=="asc")
				oldArr = that.ksort(oldArr);
			else
				oldArr = that.krsort(oldArr);
			
			//merge ordered and extras
			dataObj = $.extend(newArr, oldArr);
			return dataObj;
			//dataObj = newArr;
		}
		
	}
	this.getData = function(){
		return this.data;
	}
	
	this.getProjects = function(){
		return this.data.projects;
	}
	
	//get template by name or if no option, get all of em
	this.getTemplates = function(type){
		type = (type==undefined) ? false : type;
		if (type){
			return this.data.template[type];
		}
		else
			return this.data.template;
	}
	
	/*
	//get template attribute
	this.getTemplateAttribute = function(att, type){
		if (this.isset(this.data.template.att)){
			return this.data.template.att;
		}else{
			return false;
		}
	}
	*/
	
	//get attributes from specific project/media
	this.getAttributes = function(arr,dataObj, ascOrDesc){
		ascOrDesc = (ascOrDesc==undefined) ? "desc" : ascOrDesc;
		ret = {};
		if (typeof arr == "string")
			arr = [arr];
		$.each(arr, function(index,key){
			ret[key] = (that.isset(dataObj[key])) ? dataObj[key] : "undefined"; 
		});
		ret = this.sort(ret, ascOrDesc, 1);
		return ret;
	}
	
	
	//get project by attribute (default by: id)
	this.getProject = function(val,att){
		att = (att==undefined) ? "id" : att;
		ret = false;
		$.each(this.data.projects, function(projId,proj){
			if(that.isset(proj[att]) && proj[att]==val){
				ret = proj;
				
			}
		});
		return ret;
	}
	
	this.getItem = function(dataObj, id){
		count = 0;
		for (var i in dataObj){
			if (count==id)
				return dataObj[i];
			count++;
		}
		return false;
	};
	
	this.getInfo = function(){
		data = $.extend({}, this.data);
		delete data.projects;
		delete data.template;
		return data;	
	}
	
	//this wont work if the file is protected!
	this.getSettings = function(incUsers, onDone, async){
	 	config = this.get(this.CMCM_DIR+this.CONFIG_FILE, function(config){
	 	if (!incUsers)
	 		delete config.users;
	 	this.config = config; 
	 	if (onDone)
	 		onDone(this.config);
	 	}, async);
	 	//for async = false;
		return config;
	}
	
	//grab possible values from data
	this.getExistingValues = function(searchKey, dataObj, ascOrDesc){
		ascOrDesc = (ascOrDesc==undefined) ? "desc" : ascOrDesc;
		arr = [];
		$.each(dataObj, function(projId,projObj){
			//if its got that attr and value is not in arr already
			if (that.isset(projObj[searchKey]) && projObj[searchKey]!=""){
				projObj[searchKey] += "";
				if ($.inArray(projObj[searchKey], arr)==-1)
					arr.push(projObj[searchKey]);
				
			}else if ((!that.isset(projObj[searchKey]) || projObj[searchKey]=="") &&  $.inArray("undefined", arr)==-1){
				arr.push("undefined");
			}
				
		});

		arr = this.sort(arr, ascOrDesc);
		return arr;			
	}
	
	//grab possible values from filtered data
	this.getExistingValuesByCond =  function(searchKey, condArray, dataObj,  ascOrDesc){
		ascOrDesc = (ascOrDesc==undefined) ? "desc" : ascOrDesc;
		data = that.filter(dataObj, condArray);
		values = that.getExistingValues(searchKey, data, ascOrDesc);
		return values;
	}
	
	
	//get cover images as media objects for stated data object
	this.getCoverImages = function(dataObj){
		arr = {};
		$.each(dataObj, function(projId,projObj){
			if (projObj['coverImage']){
				if (that.isset(projObj['media'][projObj['coverImage']])){
					arr[projObj['coverImage']] = projObj['media'][projObj['coverImage']];
				}
			}
		});
		return arr;
	}
	
	this.get = function(src,onDone, async){
		var RESP = null;
		$.ajax(src, {
			async : async,
			dataType : "text",
			success : function(resp){
				RESP = $.parseJSON(resp);
				onDone(RESP);
			}
		});
		//for async - false
		return RESP;
	}


	//group together
	this.group = function(sort_arr, data, ascOrDesc){
		
		sort_arr = $.extend([], sort_arr);
		data = $.extend({}, data);
		ascOrDesc = (ascOrDesc==undefined) ? "desc" : ascOrDesc;
		LAST = false;
		if (this.isString(sort_arr))
			sort_arr = [sort_arr];
		if (!$.isEmptyObject(sort_arr)){
			searchKey = sort_arr.shift();
			if ($.isEmptyObject(sort_arr))
				LAST = true;
			possibleValues = that.getExistingValues(searchKey, data);
			subLists = {};
			
			$.each(possibleValues, function(k, v){
					subLists[v] = that.filter(data, [searchKey, "EQUALS", v], "all");
			});
			if (LAST){
				//last one, just return group
				
				if (ascOrDesc=="asc")
					subLists = that.ksort(subLists);
				else
					subLists = that.krsort(subLists);
				return subLists;
			}else{
				
				//more subgroups
				
				//subGroups = $.extend({}, subGroups);
				rand = Math.random()*1000000;
				data = {};
				subGroups = {};
				$.each(subLists,  function(key, list){
					//subGroups[key]  = that.group(sort_arr,list, ascOrDesc);
					data[key]  = that.group(sort_arr, list, ascOrDesc);
				});
				
				data = that.sort(data, ascOrDesc, 1);
				
				if (ascOrDesc=="asc")
					data = that.ksort(data);
				else
					data = that.krsort(data);
				
				return data;
			}
		}
	}
	
	//convert data to all arrays or all objects
	this.convert = function(d, type, extras) {
		var that = this;
		type = (type == undefined) ? "array" : type;
		extras = (extras==undefined) ? false : extras;
		type = type.split(",");
		$.each(type, function(i,t){
			t = (typeof t == "string") ? t.trim() : t;
			switch (t){
				case 'date':
					d = d.replace("-", ",");
					d = new Date(d);
					d = date_to_string(extras, d);
					break;
				case 'timestamp':
					d = d.replace("-", ",");
					d = new Date(d);
					d =  d.getTime()/1000;
					break;
				case 'html':
					d = that.unHtmlEntities(d);
					//return html_entity_decode(d);
					break;
				case 'cmcm_code':
					d = d.replace(/{{([^{}]+)}}/g, function(str, match, offset, s){
						command_delim = match.indexOf(":");
						command = substr(match, 0, command_delim);
						args =  substr(match, command_delim+1);
						ret = "";
						switch (command){
							case "CMCM_URL":
								ret = that.CMCM_URL;
								break;
							case "SITE_URL":
								ret = that.SITE_URL;
								break;
							case "MEDIA_SRC":
								temp = args.split("//");
								projId = temp[0];
								mediaId = temp[1];
								proj = frunt.getProject(that.parseNum(that.trim(projId)));
								if (isset(proj["media"][that.trim(mediaId)])){
									media = proj["media"][that.trim(mediaId)];
									ret = (media['type']=="image") ?  frunt.CMCM_URL+media['src'] :  media["src"];
								}else{
									ret = "";
								}
								break;
						}
						return ret;
					});
					break;
				case 'breaks':
					d = d.replace(/\n/gi, '<br/>');
					break;
				case 'plain':
					d =  that.stripTags(that.unHtmlEntities(d));
					break;
				default:
				case 'array':
					//not necessary
			        break;
			    case 'object':
			    	//not necessary
			    	break;
		    }
		});
		return d;
	 }	 


	//filter out by rules..DATA MUST BE ARRAY
	this.filter = function(data, rules, satisfy){
		data = $.extend({}, data);
		satisfy = (satisfy==undefined) ? "all" : satisfy;
		//data = (object) data;
		//check for individual
		MULTI = true;
		if (this.isset(data["id"])){
			data = [data];
			MULTI = false;
		}
		res = (satisfy=="all") ? data : {};
		
		if (!$.isArray(rules[0])){
			rules = [rules];
		}
		//print_r(rules);
		$.each(rules,  function(index,rule){
			attr = (rule[0] != undefined) ? rule[0] : false;
			cond = (that.isset(rule[1])) ? rule[1] : false;
			val =  (that.isset(rule[2])) ? rule[2] : false;
			toRemove = {};
			$.each((satisfy=="all") ? res : data, function(projId, proj){
					eval = undefined;
					toRemove[projId] = [];
					proj = $.extend({}, proj);
					if (!$.isArray(attr)){
						if (!that.isset(proj[attr]))
							proj[attr] = "undefined";
					}
					
					
					switch(cond){
						case 'IGNORE':
							
							if (attr!=false){
								if (that.isString(attr))
									attr = [attr];
								$.each(attr, function(index, att){
									if (that.isset(res[projId][att])){
										delete res[projId][att]
										//toRemove[projId].push(att);
									}	
								});
							}
							
							break;
						case 'ONLY':
						
							if (attr!=false){
								tmpArr = {};
								
								if (that.isString(attr))
									attr = [attr];
								
								$.each(attr, function(index, att){
									if (that.isset(res[projId][att]))
										tmpArr[att] = res[projId][att];
								});
								res[projId] = tmpArr;
								
							}
							
							break;
						case 'WITH ANY TAGS':
							//get tags
							vals = val.split(",");
							atts = proj[attr].toString().split(",");
							eval = false;
							$.each(vals, function(index, v){
								if ($.inArray(v, atts)!=-1)
									eval = true;
							});
							break;
						case 'WITH TAGS':
							//get tags
							vals = val.split(",");
							atts = proj[attr].toString().split(",");
							eval = true;
							$.each(vals, function(index, v){
								if ($.inArray(v, atts)== -1)
									eval = false;
							});
							break;
						case 'NOT WITH TAGS':
							//get tags
							vals = val.split(",");
							atts = proj[attr].toString().split(",");
							eval = true;
							$.each(val, function(index, v){
								if ($.inArray(v, proj[attr])!=-1)
									eval = false;
							});
						case 'CUSTOM':
							//condition to meet
							eval = val(proj[attr]);
							break;
						case 'MORE THAN':
							//condition to meet
							eval = (proj[attr]>val);
							break;
						case 'LESS THAN':
							//condition to meet
							eval = (proj[attr]<val);
							break;
						case 'NOT CONTAINS':
							//condition to meet
							eval = (proj[attr].toLowerCase().indexOf(val.toLowerCase())==-1);
							break;
						case 'CONTAINS':
							//condition to meet
							eval = (proj[attr].toLowerCase().indexOf(val.toLowerCase())!=-1);
							break;
						default:	
						case "EQUALS":
							//condition to meet
							eval = (proj[attr] == val);
							break;
						case "NOT EQUALS":
							//condition to meet
							eval = (proj[attr] != val);
							break;
						
					}
					
					//for basic evals
					if (that.isset(eval)){
					//check for condition
						if (satisfy=="all"){
							if (!eval)
								delete res[projId];
						}else{
							if (eval)
								res[projId] = data[projId];
						}
						delete eval;
					}
					
					
				});//<--end loop	
			});//<--end loop
		
		
		if (MULTI==false){
			res = res[0];
		}
		return res;
		
	}

	
	/*
	 *
	 * PHP functions written in js
	 *
	 */
	this.parseNum = function(n){
				//integer
				if (n != "" && !isNaN(n) && Math.round(n) == n){
					return parseInt(n);
				//float
				}else if(n != ""){
					return parseFloat(n);
				}else{
					return n;
				}
	};
	this.htmlEntities =  function(str, single_quote) {
		if (!isNaN(str))
			return this.parseNum(str);
		if (single_quote){
			str = String(str).replace(/'/g, '&#039;');
		}
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	};
	
	this.unHtmlEntities = function(str, single_quote, double_quote) {
		if (!isNaN(str))
			return this.parseNum(str);
		if (single_quote){
			str = String(str).replace(/&#039;/g, '\'');
		}else if (double_quote){
			str = String(str).replace(/(?:&quot;)/g, '\"');
		}
		return String(str).replace(/(?:&amp;)/g, '&').replace(/(?:&lt;)/g, '<').replace(/(?:&gt;)/g, '>');
	};
	
	
	//run widget
	this.widget = function(_type, data, opts){
		
		defaults = {
			type : ""
		};
		
		opts = $.extend(defaults, opts);
		
		//subtype was specified in the type
		if (_type.indexOf(".")!=-1){
			opts['type'] = _type.substring(_type.indexOf(".")+1);
			_type = _type.substring(0, _type.indexOf("."));
		}
		widget =  new that.FruntWidget(_type, data, opts);
		return widget.render();
	};
	
	
	//WIDGET CLASS, instantiatable...requires twig
	this.FruntWidget = function(widgetName, data, opts){
		
		this.CMCM_DIR = that.CMCM_DIR;
		this.TMPL_DIR  = "templates/";
		this.CMCM_URL = that.CMCM_URL;  	
		this.SITE_URL = that.SITE_URL;
		this.TMPL_FOLDER = CMCM_DIR+"assets/frunt/php/"+this.TMPL_DIR;
		
		this.data = data;
		this.wName = widgetName;
		
		defaults = {
			async : true,
			load : function(d){return d}
		}
		
		this.opts = $.extend(defaults, opts);
		
		//frunt
		this.frunt = that;
		//this instance..
		var thatthat = this;
		
		this.render = function(){
			//get widget
		 	return this[this.wName]();
			//return 'weeee '+this.wName+this.CMCM_URL;
		};
		
		//WIDGETZZZZZZ
		
		this.blah = function(opts){
			return 'weeee';
		}
		
		//preview widget 
		this.preview = function(){
				
				
				defaults_media = {
					"src" : false,
					"thumb" : false,
					"caption" : false,
					"media_type" : "image",
					"modal_group" : "modal",
					"mode" : "none",
					"responsive" : true,
					"real-fit" : "within",
					'bias' : false,
					"sync-parent" : false,
					"fit" : "fill",
					"autoplay" : false,
					"use-thumb" : false,
					"no-ratio" : false
				};
				
				//if user supplies media, we can grab most from just that
				if (this.data!=undefined){
					mediaObj = {
						src : this.data.src || false,
						thumb : this.data.thumb || false,
						caption : this.data.caption || false,
						media_type : this.data.type || false
					}
					//extend it!
					defaults_media = $.extend(true, {}, defaults_media, mediaObj);
				}
				
				
				this.opts = $.extend(true, {}, defaults_media, this.opts);
				
				
				//preview widget
				link = $("<a href='' class='frunt-widget frunt-widget-preview'></a>");
				link.attr("href", this.opts.src);
				link.html( this.opts.src);
				link.attr("title",  this.opts.caption);
				link.attr("rel",  this.opts['modal_group']);
				link.attr("data-type", this.opts['media_type']);
				for (var i in defaults_media){
					whiteList = ["media_type", "modal_group", "caption"];
					if ($.inArray(i, whiteList)==-1)
						link.attr("data-"+i.replace(/_/gi, "-"), this.opts[i]);
				}
				console.log($(link).clone().wrap('<p>').parent().html());
				return link;		
		}
		
		 //layout widget
		this.layout = function(){
				//default media opts
				defaults_media = {
					"mode" : "none",
					//responsive settings
					"responsive" : true,
					"real_fit" : "within",
					'bias' : false,
					"fit" : "fill",
					"no_ratio" : false
				};
				
				image_media = {
					"use_thumb" : false
				};
				video_media = {
					"autoplay" : true
				};
				sound_media = {
					"autoplay" : true,
					"visual" : true
				};
				
				//default opts
				defaults = {
					"type" : "grid",
					//GRID SPECIFIC
					"sort_by" : false, //false,string, or array 
					"force_cols" : false, //false or int force break after x, change media_wpr dimension to percentages,
					"ascOrDesc" : "desc", //asc or desc, direction of list
					//SLIDESHOW SPECIFIC
					"autoplay" : false, //false or delay length, 
					"transition_effect" : "slide", //slide or fade, effect of transition
					"transition_length" : 400, //int, length of transition
					"next_on_click" : true, //true or false, if slide click goes to next
					"loop_slides" : true, //true or false, if next is clicked on last slide, it should return to first
					"slide_controls" : false, //false, numbers,dots,or thumbs
					//GENERAL
					"document_scroll" : false, //true or false, whether controls should track document or slider scroll
					"just_thumbs" : false, //true or false, for just thumb
					"use_thumb" : false, //true or false, for image type media
					"media_opts" : {
						"image" : $.extend(true, {}, defaults_media, image_media),
						"video" : $.extend(true, {}, defaults_media, video_media),
						"sound" : $.extend(true, {}, defaults_media, sound_media)
					},
					"no_caption" : false //dont show caption
				};
				
				
				/*
				//media opts defaults
				if (isset(this.opts['media_opts'])){
					foreach (this.opts['media_opts'] as type:o){
						this.opts['media_opts'][type] = o + defaults['media_opts'][type] ;
					}
					
				}
				*/
				//set user opts
				this.opts = $.extend(true, {}, defaults, this.opts);
				
				switch(this.opts['type']){
					case "vertical":
					case "horizontal":
					case "slideshow":
						return this.frunt.twig({
						 	location : this.TMPL_FOLDER,
						 	file : "layout_"+((this.opts['type']=="vertical") ? "horizontal" :  this.opts['type'])+".php", 
						 	load : this.opts.load,
						 	async : this.opts.async,
						 	params : $.extend(this.opts, {
								"media" : this.data,
								"site_url" : this.SITE_URL,
								"cmcm_url" : this.CMCM_URL
							})
						});
						break;
					case "grid":
					//if sortby..we wanna only grab the 1st if array...it will be a string..
					if (this.opts['sort_by']){
						if (this.frunt.isString(this.opts['sort_by'])){
							this.opts['sort_by'] = [this.opts['sort_by']];
						}
						
						if($.isArray(this.opts['sort_by'])){
							this.opts['sort_by'] = [this.opts['sort_by'].shift()];
						}
						//regroup stuff
						this.data = this.frunt.group(this.opts['sort_by'], this.data, this.opts['ascOrDesc']);
					}else{
						this.data = [this.data];
					}
					return this.frunt.twig({
					 	location : this.TMPL_FOLDER,
					 	file :"layout_grid.php",
					 	load : this.opts.load,
					 	async : this.opts.async,
					 	params : $.extend(this.opts, {
							"media" : this.data,
							"site_url" : this.SITE_URL,
							"cmcm_url" : this.CMCM_URL
						})
					});
					break;
				}
		}

			 
		 //simple list widget
		this.simpleList = function(){
			
			//default opts
			defaults = {
	 			"template" : "<span class='key'>{{key}}</span>: <span class='val'>{{val}}</span>", //template for each list item
	 			//default for everything
	 			"default_format" : {
	 				"key" : function(k){return k.replace(/_/gi, " ");},
	 				"value" : function(v){return v;}
	 			},
	 			//custom formats for types (prefixed with *) and individual attributes
	 			"custom_format" : {
	 				"*bool" : { "value": function(v){ return (v) ? "yes" : "no"; } }
	 			},
	 			//listype
	 			"list_type" : "project",
	 			//elements to ignore..defaults, and additionals
	 			"showDefaultIgnores" : false,
	 			"ignore_default" : {
	 				"project" : ["id","published","added","cleanUrl","coverImage","media"],
	 				"media" : ["visible","src","type","thumb"]
	 			},
	 			"ignore" : false, 
	 			//restrict elements to the following
	 			"only" : false
			};
			
			//set user opts, 
			this.opts =  $.extend(true, {}, defaults, this.opts);
			
			//filter
			rules = [];
			//filter out default ignores
			if (!this.opts['showDefaultIgnores'])
				rules.push([this.opts['ignore_default'][this.opts['list_type']], "IGNORE"]);
			
			//filter out ignore list 
				rules.push([this.opts['ignore'], "IGNORE"]);
			//pick out only..;
				rules.push([this.opts['only'], "ONLY"]);
			this.data = this.frunt.filter(this.data, rules);
			_FINAL = {};
			//go through each thing to get formatting
			
			
			$.each(this.data, function(key,value){
				
				//for types
				if (key.substring(0, 1)=="*"){
					
				}else if (thatthat.frunt.isset(thatthat.opts["custom_format"][key])){
					if	(thatthat.frunt.isset(thatthat.opts["custom_format"][key]['key']))
						newKey = thatthat.opts["custom_format"][key]['key'](key);
					else
						newKey = thatthat.opts["default_format"]['key'](key);
					if	(isset(thatthat.opts["custom_format"][key]['value']))
						newVal = thatthat.opts["custom_format"][key]['value'](value);
					else
						newVal = thatthat.opts["default_format"]['value'](value);
				}else{
						newKey = thatthat.opts["default_format"]['key'](key);
						newVal = thatthat.opts["default_format"]['value'](value);
				}
				
				_FINAL[newKey] = newVal;
			});
			//display
			items = {};
			
			$.each(_FINAL, function(key,value){
				
				if ($.isArray(value))
					value = "("+value.length+")";
				tmp = thatthat.opts['template'].replace(/{{key}}/gi ,  key);
				tmp = tmp.replace(/{{val}}/gi , value);
				items[key] = tmp;
			});
			
			return this.frunt.twig({
			 	location : this.TMPL_FOLDER,
			 	file : "simple_list.php", 
			 	load : this.opts.load,
			 	async : this.opts.async,
			 	params : $.extend(this.opts, {
					"items" : items,
					"site_url" : this.SITE_URL
				})
			});
		 }
	 
		
		this.menu = function(opts){
			
		defaults = {
			"identifier" : "cleanUrl", //cleanUrl or id
			"current" : false, //current identifier
			"getQuery" : "id", //name of $_GET variable that holds identifier
			"url_rewrite" : "projects/", //if set, url will be written as specifed followed by indentifier
			"type" : "basic", //basic: vertical, horiz, submenu: shows next to menu, thumb
			"ascOrDesc" : "desc", //asc or desc, direction of list
			"extras" : false,
			//MENU TYPE-SPECIFIC OPTIONS
			//basic, submenu
			"collapse" : (that.isset(this.opts['type']) && this.opts['type']=="horizontal") ? false : true, //true or false, to collapse if sort by
			"headers" : false, //false or array, things to show above menu and extras
        	"extras_location" : "bottom", //top or bottom, extras menu location
			"collapse_multiple_fans" : false, //true or false, multiple submenus can be revealed or only allow one at a time
			"collapse_current" : false, //string or false, which one to reveal on init
			//basic, submenu, horiz
			"sort_by" : false, //array,string,false of attribute to sort by,
			//thumb
			"no_title" : false, //dont show title 
			"force_cols" : false, //false or int force break after x, change media_wpr dimension to percentages 
			
		};
		//set user opts
		this.opts =  $.extend(defaults, this.opts);
		//setup
		switch (this.opts['type']){
			case "grid":
				//if sortby..we wanna only grab the 1st if array...it will be a string..
				if (this.opts['sort_by']){
				
					if (this.frunt.isString(this.opts['sort_by'])){
						this.opts['sort_by'] = [this.opts['sort_by']];
						
					}
					
					
					if($.isArray(this.opts['sort_by'])){
						this.opts['sort_by'] =[this.opts['sort_by'].shift()];
					}
					//regroup stuff
					this.data = this.frunt.group(this.opts['sort_by'], this.data, this.opts['ascOrDesc']);
				}else{
					this.data = [this.data];
				}
				
			
				return this.frunt.twig({
				 	location : this.TMPL_FOLDER,
				 	file : "menu_grid.php", 
				 	load : this.opts.load,
				 	async : this.opts.async,
				 	params : $.extend(this.opts, {
						"projects" : this.data,
						"site_url" : this.SITE_URL,
						"cmcm_url" : this.CMCM_URL
					})
				});
				break;
			
			case "horizontal":
				//if sortby
				if (this.opts['sort_by']){
					if (this.frunt.isString(this.opts['sort_by'])){
						this.opts['sort_by'] = [this.opts['sort_by']];
						
					}
					
					cols = {};
					$.each(this.opts['sort_by'], function(index,sortKey){
						if (index!=0){
							tmp =  thatthat.frunt.getExistingValues(sortKey, thatthat.data);
							_res_ = {};
							$.each(tmp, function(index2, possibleValue){
								_res_[possibleValue] = {};
								for(i=0; i<index; i++){
									tmpKey = thatthat.opts['sort_by'][i];
									_res_[possibleValue][tmpKey] = thatthat.frunt.getExistingValuesByCond(tmpKey, [
										[sortKey, "EQUALS", possibleValue]
									], thatthat.data, thatthat.opts['ascOrDesc']);
						 		}
							});
							 cols[sortKey] = _res_;
						 }else{
							 cols[sortKey] = thatthat.frunt.getExistingValues(sortKey, thatthat.data, thatthat.opts['ascOrDesc']);
						 }
					});
					_res2_ = {};
					this.data = this.frunt.sort(this.data,  this.opts['ascOrDesc'], 2, 'title');
					
					$.each(this.data, function(projId,proj){
						//echo proj['title']."\n";
						 _res2_[projId] = thatthat.frunt.getAttributes(thatthat.opts['sort_by'], proj, thatthat.opts['ascOrDesc']);
					});
					
					cols["projects"] = _res2_;
				}
				
				
				//return JSON.stringify(cols,null,4);
				return this.frunt.twig({
				 	location : this.TMPL_FOLDER,
				 	file : "menu_horiz.php", 
				 	load : this.opts.load,
				 	async : this.opts.async,
				 	params : $.extend(this.opts, {
						"projects" : this.data,
						"site_url" : this.SITE_URL,
						"col_data" : this.frunt.isset(cols) ? cols : false
					})
				});
				break;
			case "basic":
			case "vertical":
			default:
				//if sortby
				if (this.opts['sort_by']){
					if (this.frunt.isString(this.opts['sort_by'])){
						this.opts['sort_by'] = [this.opts['sort_by']];
						
					}
					if($.isArray(this.opts['sort_by'])){
						//regroup stuff
						 this.data = this.frunt.group(this.opts['sort_by'], this.data, this.opts['ascOrDesc']);
						 //print_r(this.data);
					}else{
						//do nothing
						this.opts['sort_by'] = false;
					}
				}
				
				 //print_r(this.opts);
				 return this.frunt.twig({
				 	location : this.TMPL_FOLDER,
				 	file : "menu_basic.php", 
				 	load : this.opts.load,
				 	async : this.opts.async,
				 	params : $.extend(this.opts, {
						"projects" : this.data,
						"site_url" : this.SITE_URL
					})
				});
				break;
			}
			
		};
	 	
		
		
	};
		
	//initiate Twig for templating
	this.twig = function(_opts){
		
		defaults = {
			location : 'templates/',
			file : false,
			data : false,
			async : false,
			load : function(data){return false},
			params : {}
		};
		
		_opts = $.extend(defaults, _opts);
		
				
				if (_opts.file){
					
					_twig = twig({
					    id: _opts.file+"_"+Math.random()*100000,
					    templateFolder: _opts.location,
					    href: _opts.location+_opts.file,
					    async: _opts.async,
					    cache: _opts.cache,
					    load : function(template){
					    	if (_opts.async){
					    		_opts.load(template.render(_opts.params));
					    	}
					    }
					    
					})
					if (!_opts.async){
						return _twig.render(_opts.params);
					}
				}else if (_opts.data){
					return twig({
					    id: "inlineData_"+Math.random()*100000,
					    templateFolder: _opts.location,
					    data : _opts.data    
					}).render(_opts.params);
				}


	};
	
	
	this.stripTags = function(input, allowed){
			allowed = (((allowed || '') + '')
	    .toLowerCase()
	    .match(/<[a-z][a-z0-9]*>/g) || [])
	    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	  return input.replace(commentsAndPhpTags, '')
	    .replace(tags, function($0, $1) {
	      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	};
	
	
	this.loadScript = function(url, id, callback){
    // Adding the script tag to the head as suggested before
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.id = id;
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = callback;
    script.onload = callback;

    // Fire the loading
    head.appendChild(script);

	}
	
	this.isString = function(v){
		return (typeof v == "string");
	}
	this.isset = function(v){
		return v !== undefined;
	}
	this.reverse = function(obj){
		if (!$.isArray(obj)){
			newObj = {};
			keys=  Object.keys(obj).reverse();
			$.each(keys, function(index, key){
				newObj[key] = obj[key];
			});
		}else if ($.isArray(arr)){
			newObj = obj.reverse();
		}
		return newObj;
	}
	this.rsort = function(obj){
		return this.reverse(this._sort(obj));
	}
	this.krsort = function(obj){

		obj = this.ksort(obj);
		
		return  this.reverse(this.ksort(obj));
	}
	
	this._sort = function(obj) { 
	    var arr = [];
	    for (var prop in obj) {
	        if (obj.hasOwnProperty(prop)) {
	            arr[prop] = obj[prop];
	        }
	    }
	    arr.sort();
	    /*
	    arr.sort(function(a, b) { 
	    	return a - b;
	     });
	    //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
	    */
	    return arr; // returns array
	}
	
	this.ksort = function(obj){
	    var keys = [];
	    var sorted_obj = {};
	
	    for(var key in obj){
	        if(obj.hasOwnProperty(key)){
	            keys.push(key);
	        }
	    }
	    numKeys = true;
	    //check if theyre all numbers
	    for (var i in keys){
		    if (isNaN(keys[i]))
		    	numKeys = false;
	    }
	   //if so convert keys to numbers!
	    if (numKeys)
	     for (var i in keys){
	     	keys[i] = this.parseNum(keys[i]);
	     }	
	    // sort keys
	    keys.sort();
	   
	    // create new array based on Sorted Keys
	    for (i in keys){
	        sorted_obj[keys[i]] = obj[keys[i]];
	    };

	   return sorted_obj;
	};
	
	//initialize
		this.init();	 
 }
  /*
 * date_to_string v1.0.0
 *
 * Copyright (c) 2013 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @copyright Copyright (c) 2013 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @link http://github.com/andrewgjohnson/date_to_string
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 1.0.0
 * @package date_to_string
 *
 */
 
 if(typeof date_to_string=="undefined"){var date_to_string=function(f,d){f=String(f);d=typeof d!="undefined"&&d instanceof Date?d:new Date();var b={a:function(g){return g.getHours()<12?"am":"pm"},A:function(g){return g.getHours()<12?"AM":"PM"},B:function(g){return("000"+Math.floor((g.getHours()*60*60+(g.getMinutes()+60+g.getTimezoneOffset())*60+g.getSeconds())/86.4)%1000).slice(-3)},c:function(g){return date_to_string("Y-m-d\\TH:i:s",g)},d:function(g){return(g.getDate()<10?"0":"")+g.getDate()},D:function(g){switch(g.getDay()){case 0:return"Sun";case 1:return"Mon";case 2:return"Tue";case 3:return"Wed";case 4:return"Thu";case 5:return"Fri";case 6:return"Sat"}},e:function(h){var i=parseInt(Math.abs(h.getTimezoneOffset()/60),10),g=Math.abs(h.getTimezoneOffset()%60);return(new Date().getTimezoneOffset()<0?"+":"-")+(i<10?"0":"")+i+(g<10?"0":"")+g},F:function(g){switch(g.getMonth()){case 0:return"January";case 1:return"February";case 2:return"March";case 3:return"April";case 4:return"May";case 5:return"June";case 6:return"July";case 7:return"August";case 8:return"September";case 9:return"October";case 10:return"November";case 11:return"December"}},g:function(g){return g.getHours()>12?g.getHours()-12:g.getHours()},G:function(g){return g.getHours()},h:function(h){var g=h.getHours()>12?h.getHours()-12:h.getHours();return(g<10?"0":"")+g},H:function(g){return(g.getHours()<10?"0":"")+g.getHours()},i:function(g){return(g.getMinutes()<10?"0":"")+g.getMinutes()},I:function(g){return g.getTimezoneOffset()<Math.max(new Date(g.getFullYear(),0,1).getTimezoneOffset(),new Date(g.getFullYear(),6,1).getTimezoneOffset())?1:0},j:function(g){return g.getDate()},l:function(g){switch(g.getDay()){case 0:return"Sunday";case 1:return"Monday";case 2:return"Tuesday";case 3:return"Wednesday";case 4:return"Thursday";case 5:return"Friday";case 6:return"Saturday"}},L:function(g){return new Date(g.getFullYear(),1,29).getMonth()==1?1:0},m:function(g){return(g.getMonth()+1<10?"0":"")+(g.getMonth()+1)},M:function(g){switch(g.getMonth()){case 0:return"Jan";case 1:return"Feb";case 2:return"Mar";case 3:return"Apr";case 4:return"May";case 5:return"Jun";case 6:return"Jul";case 7:return"Aug";case 8:return"Sep";case 9:return"Oct";case 10:return"Nov";case 11:return"Dec"}},n:function(g){return g.getMonth()+1},N:function(g){return g.getDay()==0?7:g.getDay()},o:function(g){return g.getWeekYear()},O:function(h){var i=parseInt(Math.abs(h.getTimezoneOffset()/60),10),g=Math.abs(h.getTimezoneOffset()%60);return(new Date().getTimezoneOffset()<0?"+":"-")+(i<10?"0":"")+i+(g<10?"0":"")+g},P:function(h){var i=parseInt(Math.abs(h.getTimezoneOffset()/60),10),g=Math.abs(h.getTimezoneOffset()%60);return(new Date().getTimezoneOffset()<0?"+":"-")+(i<10?"0":"")+i+":"+(g<10?"0":"")+g},r:function(g){return date_to_string("D, d M Y H:i:s O",g)},s:function(g){return(g.getSeconds()<10?"0":"")+g.getSeconds()},S:function(g){switch(g.getDate()){case 1:case 21:case 31:return"st";case 2:case 22:return"nd";case 3:case 23:return"rd";default:return"th"}},t:function(g){return new Date(g.getFullYear(),g.getMonth()+1,0).getDate()},T:function(g){var h=String(g).match(/\(([^\)]+)\)$/)||String(g).match(/([A-Z]+) [\d]{4}$/);if(h){h=h[1].match(/[A-Z]/g).join("")}return h},u:function(g){return g.getMilliseconds()*1000},U:function(g){return Math.round(g.getTime()/1000)},w:function(g){return g.getDay()},W:function(g){return g.getWeek()},y:function(g){return String(g.getFullYear()).substring(2,4)},Y:function(g){return g.getFullYear()},z:function(g){return Math.floor((g.getTime()-new Date(g.getFullYear(),0,1).getTime())/(1000*60*60*24))},Z:function(g){return(g.getTimezoneOffset()<0?"+":"-")+(g.getTimezoneOffset()*24)}};var c="",e=false;for(var a=0;a<f.length;a++){if(!e&&f.substring(a,a+1)=="\\"){e=true}else{if(e||typeof b[f.substring(a,a+1)]=="undefined"){c+=String(f.substring(a,a+1));e=false}else{c+=String(b[f.substring(a,a+1)](d))}}}return c}}Date.prototype.getWeek=function(){var a=new Date(this.valueOf());var b=(this.getDay()+6)%7;a.setDate(a.getDate()-b+3);var c=a.valueOf();a.setMonth(0,1);if(a.getDay()!=4){a.setMonth(0,1+((4-a.getDay())+7)%7)}return 1+Math.ceil((c-a)/(1000*60*60*24*7))};Date.prototype.getWeekYear=function(){var a=new Date(this.valueOf());a.setDate(a.getDate()-((this.getDay()+6)%7)+3);return a.getFullYear()};