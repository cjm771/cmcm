cmcmDocs = {
	timer : {},
	//format args
	formatArgs : function(o){
		if (o==undefined || o==false)
			return "";
		keys = Object.keys(o);
		for (i in keys){
			if (keys[i].indexOf("*")==0){
				delimiter = (keys.length==1) ? "" : ", ";
				keys[i] = "["+delimiter+keys[i].replace(/\*/gi, "")+"]";
			}else if (i!=0){
				keys[i] = ", "+keys[i]; 
			}
		}
		return keys.join(" ");
	},
	//formats methods
	formatMethod : function(name, o){
		
		methods_wpr = $("<div class='methods_wpr'></div>");
		if (name.indexOf("*")==0){
			codeType = name.substring(name.indexOf("*")+1, name.indexOf("_"));
			methodName =  name.substring(name.indexOf("_")+1);
			name = methodName;
		}else{
			codeType = undefined;
		}
		link = (codeType!=undefined) ? name+"-"+codeType : name;
		methods_wpr.append("<a name='frunt-method-"+link+"'></a>");
		if (o.type!=undefined && o.type!=false){
			methods_wpr.addClass("code");
			methods_wpr.addClass("code_"+o.type);
		}
		method = $("<div class='method'></div>");
		method.html("<b class='methodName'>"+name+"</b> ( "+this.formatArgs(o.params)+" )"+"<span class='return'>Returns: "+o._ret+"</span>");
		
		if (codeType!=undefined)
			method.find('.methodName').prepend("<i class='codeType'>"+codeType+"</i> ");
		methods_wpr.append(method);
		
		methods_wpr.append("<div class='method_descrip'>"+o.description+"</div>");
		if (o.note){
			methods_wpr.append("<div class='note'><span class='glyphicon glyphicon-exclamation-sign'></span>  <b>Note: </b>"+o.note+"</div>");
		}
		if (o.params!=undefined && o.params!=false){
			params = $("<ul class='params'></ul>")
			for (i in o.params){
				param = o.params[i];
				param_wpr = $("<li class='param_wpr'></li>");
				param_wpr.append("<div class='param_type'>"+i.replace(/\*/gi, "")+": <i>"+param.type+"</i></div>");
				param_wpr.append("<div class='param_descrip'><i>"+param.description+"</i></div>");
				if (param._default!=undefined)
					param_wpr.find(".param_descrip").append(" / <span class='default'>Default : "+param._default+"<b></b></span>");
				params.append(param_wpr);
				
			}
			methods_wpr.append(params);
		}
		return methods_wpr;
	},
	methods : {
		getData : {
			group : "fruntCoreGet",
			type : false,
			description : "Get entire data file into an object",
			_ret : "Object",
			params : false
		},
		getProjects : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all projects into an object",
			_ret : "Object (Js) / Assoc Array (php)",
			params : false
		},
		getTemplates : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all or a specific template",
			_ret : "Object",
			params : {
				"*type" : {
					_default : false,
					type : "false, String: 'media' or 'project'",
					description: "Name of template or nothing for all the templates"
				}
			}
		},
		getItem : {
			group : "fruntCoreGet",
			type : false,
			description : "Generic method of getting nth item in an object/array.",
			_ret : "Object",
			params : {
				data :  {
					type : "Array/Object",
					description : "Data to extract from"
				},
				"index" :  {
					type : "int",
					description : "index of item (0 based)"
				}
			}
		},
		getProject : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all or a specific template",
			_ret : "Object",
			params : {
				"id" : {
					type : "int or String",
					description : "identifier, should obviously be unique to project..generally project attribute's 'id'"
				},
				"*id_type" : {
					type : "String, name of attribute",
					_default : "'id'",
					description: "Name of attribute that this id corresponds to. You may use 'cleanUrl' for example to grab a project."
				}
			}
		},
		getInfo : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all information in file thats not (projects, templates). This would include site name, subtitle, description, and misc information",
			_ret : "Object",
			params : {}
			
		},
		getSettings : {
			group : "fruntCoreGet",
			type : false,
			description : "Get configuration settings object (found in data/config/config.json). This contains user info, current loaded file, and other information.",
			_ret : "Object",
			params : {},
			note : "This function will likely not work in javascript, because by default the file is read protected on client side."
		},
		getAttributes : {
			group : "fruntCoreGet",
			type : false,
			description : "Get attributes from specific project/media",
			_ret : "Object",
			params : {
				attributes : {
					type : "Array",
					description : "List of attributes"
				},
				data :  {
					type : "Array of Projects or Media",
					description : "Data to extract from"
				},
				"*ascOrDesc" :  {
					type : "String, 'asc' or 'desc'",
					_default : "desc",
					description : "Sort Direction"
				}
			}
		},
		getExistingValues : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all possible values of an attribute from a group of projects or media",
			_ret : "Array",
			params : {
				attribute : {
					type : "String",
					description : "Attribute name"
				},
				data :  {
					type : "Array of Projects or Media",
					description : "Data to extract from"
				},
				"*ascOrDesc" :  {
					type : "String, 'asc' or 'desc'",
					_default : "desc",
					description : "Sort Direction"
				}
			}
		},
		getExistingValuesByCond : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all possible values of an attribute from a group of filtered projects or media...basically shortcut of Filter method and getExistingValues method.",
			_ret : "Array",
			params : {
				attribute : {
					type : "String",
					description : "Attribute name"
				},
				rules : {
					type : "Object (Rules)",
					description : "Set of rules to filter data by..see <a href='#frunt-core-filter'>Filter</a> rules param"
				},
				data :  {
					type : "Array of Projects or Media",
					description : "Data to extract from"
				},
				"*ascOrDesc" :  {
					type : "String, 'asc' or 'desc'",
					_default : "desc",
					description : "Sort Direction"
				}
			}
		},
		getCoverImages : {
			group : "fruntCoreGet",
			type : false,
			description : "Get all cover images from a set of Projects.",
			_ret : "Object (Set of Media Objects)",
			params : {
				data :  {
					type : "Array of Projects",
					description : "Data to extract from"
				}
			}
		},
		group : {
			group : "fruntCoreGroup",
			type : false,
			description : "Split a single set of projects or media into subgroups.",
			_ret : "Set of Objects",
			params : {
				"sort_by" : {
					type : "String or Array of Strings",
					description : "Name or Array of attribute names to group by."
				},
				data :  {
					type : "Array of Projects or Media",
					description : "Data to extract from"
				},
				"*ascOrDesc" :  {
					type : "String, 'asc' or 'desc'",
					_default : "desc",
					description : "Sort Direction"
				}
			}
		},
		filter : {
			group : "fruntCoreFilter",
			type : false,
			description : "Filter out projects that don't meet specified rules.",
			_ret : "Set of Objects",
			params : {
				data :  {
					type : "Array of Projects or Media",
					description : "Data to extract from"
				},
				rules : {
					type : "Array or Set of Arrays",
					description : "Single or Set of rules to check. A rule is an array with (generally) 3 parameters: <u>attribute</u> <span class='light'>(String, attribute Name unless otherwise specifed)</span>, <u>condition String,</u> and <u>value</u> <span class='light'>(String unless otherwise specified)</span>. ex. ['title', 'CONTAINS', 'the'].<p style='font-style: normal'>Below are the possible Rules you can specify.</p><p  style='font-style: normal; '><ul  style='font-style: normal;'>"+
					"<li><b>[Attribute, 'EQUALS', Value] </b><i class='light'>Attribute must = value</i></li>"+
					"<li><b>[Attribute, 'NOT EQUALS', Value] </b><i class='light'>Attribute cannot = value</i></li>"+
					"<li><b>[Attribute, 'CONTAINS', Value] </b><i class='light'> Attribute must contain value</i></li>"+
					"<li><b>[Attribute, 'NOT CONTAINS', Value] </b><i class='light'>Attribute cannot contains value</i></li>"+
					"<li><b>[Attribute, 'LESS THAN', Value] </b><i class='light'>Attribute must be less than value</i></li>"+
					"<li><b>[Attribute, 'MORE THAN', Value] </b><i class='light'>Attribute must be more than value</i></li>"+
					"<li><b>[Attribute, 'WITH TAGS', Value] </b><i class='light'>Attribute must have the tags specifed in value. </i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span> Value should be comma seperated value string.</div></li>"+
					"<li><b>[Attribute, 'NOT WITH TAGS', Value] </b><i class='light'>Attribute cannot have the tags specifed in value. </i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span> Value should be comma seperated value string.</div></li>"+
					"<li><b>[Attribute, 'WITH ANY TAGS', Value] </b><i class='light'>Attribute should have at least one of the tags specifed in value. </i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span> Value should be comma seperated value string.</div></li>"+
					"<li><b>[Attributes, 'IGNORE'] </b><i class='light'>Special rule: Specified Attributes will be removed.</i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span> Attributes can be string or array of strings.</div></li>"+
					"<li><b>[Attributes, 'ONLY'] </b><i class='light'>Special rule: Specified Attributes will only be the ones kept.</i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span> Attributes can be string or array of strings.</div></li>"+
					"<li><b>[Attributes, 'CUSTOM', function ] </b><i class='light'>Attribute value will be fed to custom function, your function should return true or false.</i>"+
					"<div class='light'><span class='glyphicon glyphicon-exclamation-sign'></span>Value is an anonymous function: function(v){return true or false} </div></li>"+
					"</ul></p>"
				},
				"*satisfy" :  {
					type : "String, 'any' or 'all'",
					_default : "all",
					description : "If multiple rules are specified, then 'all' will make sure all rules are met. 'any' will only need one rule to be met."
				}
			}
		},
		convert :  {
			group : "fruntCoreConvert",
			type : false,
			description : "Converts an item to a different data type/format.",
			_ret : "Varies",
			params : {
				data :  {
					type : "Varies",
					description : "Data to convert"
				},
				type :  {
					type : "String",
					description : "Type of conversion.<p style='font-style: normal'>Below are the possible conversion types you can specify. Multiple conversions can be specifed by comma speperation.</p><p  style='font-style: normal; '><ul  style='font-style: normal;'>"+
					"<li class='code code_php'><b>'array'</b> <i class='light'>Converts an Object to an Associative Array (recursive)</i></li>"+
					"<li class='code code_php'><b>'object'</b> <i class='light'>Converts an Array to Object (recursive)</i></li>"+
					"<li><b>'plain'</b> <i class='light'>Strips a String of any HTML entities (which would be encoded by default).</i></li>"+
					"<li><b>'html'</b> <i class='light'>Converts HTML entites(encoded by default) into working HTML.</i></li>"+
					"<li><b>'breaks'</b> <i class='light'>Coverts line breaks '\\n' into break tags. </i></li>"+
					"<li><b>'cmcm_code'</b> <i class='light'>Our version of bbCode...in handlebars type syntax, good to use if you are using html and media in your attribute. general syntax is {{COMMAND:ARG1//ARG2//..}} Special commands inserted in textareas/inputs will be converted to their corresponding values. </i><p>"+
					"Below are the possible commands and what they'll transform into.<ul>"+
					"<li>MEDIA_SRC <i class='light'>Transforms into the src URL attribute of a media object. (drag a media thumb into a textarea or input to insert!)...example.. ex: show proj (id 20) - media (id XYZLM)  '{{MEDIA_SRC: 20//XYZLM}'</i></li>"+
						"<li>CMCM_URL <i class='light'>Transforms into cmcm_url specified in this frunt instance ex. '{{CMCM_URL}}'</i></li>"+
						"<li>SITE_URL <i class='light'>Transforms into site_url specified in this frunt instance ex. '{{SITE_URL}}'</i></li>"+
					"</ul></p></li>"+
					"<li><b>'timestamp'</b> <i class='light'>Coverts timestamp type attributes (which is a string by default) into it's unix numeric based counterpart. </i></li>"+
					"<li><b>'date'</b> <i class='light'>Coverts timestamp type attribute into a date formatted string (matches php date() formatting). Extra parameter would be the desired format string ex. 'm/d/y' or whatever. </i></li>"+
					"</ul></p>"
				},
				"*extra" : {
					type : "varies",
					description : "Certian types use an additional argument such as Date conversion."
				}
			}
		},
		sort :  {
			group : "fruntCoreSort",
			type : false,
			description : "Sort an Object.",
			_ret : "Object or Array",
			note : "In the PHP Sdk, sort affects actual variable. In JS it returns a new sorted variable.",
			params : {
				data :  {
					type : "Object or Array",
					description : "Data to Sort"
				},
				"*ascOrDesc" :  {
					type : "String, 'asc' or 'desc'",
					_default : "desc",
					description : "Sort Direction"
				},
				"*sort_method" :  {
					type : "Int",
					_default : 0,
					description : "Type of Sort method.<p style='font-style: normal'>Below are the possible conversion types you can specify.</p><p  style='font-style: normal; '><ul  style='font-style: normal;'>"+
					"<li><b>0</b> <i class='light'>Sort by values</i></li>"+
					"<li><b>1</b> <i class='light'>Sort by keys</i></li>"+
					"<li><b>2</b> <i class='light'>Sort by attribute (extras param, String required)</i></li>"+
					"<li><b>3</b> <i class='light'>Sort by custom key list (extras param, Array required) </i></li>"+
					"</ul></p>"
				},
				"*extras" : {
					type : "String or Array",
					description : "Extra param, if required by sort method"
				}
			}
		},
		"*PHP_twig" :  {
			group : "fruntCoreTwig",
			type : false,
			description : "Initialize the twig templating engine.",
			_ret : "Twig Instance",
			note : " In php, you initalize once, and then use it as specified in Twig's documentation.",
			params : {
				"*location" :  {
					type : "String",
					_default : "templates/",
					description : "Folder location (relative to current file) of templates."
				}
			}
		},
		"*JS_twig" :  {
			group : "fruntCoreTwig",
			type : false,
			description : "Render a twig template using the twig templating engine.",
			_ret : "String (HTML)",
			note : "In javascript, you use this function to both initalize and render templates. Also make sure you have included the twig.js library: <b>assets/frunt/js/lib/twig.js</b>",
			params : {
				"opts" :  {
					type : "Object",
					description : "Various options for this method. With javascript, external templates are pulled via ajax so you have an option of doing this with a callback or turning async off. <p style='font-style: normal'>Below are the possible options and their defaults.</p><p  style='font-style: normal; '><ul  style='font-style: normal;'>"+
					"<li><b>location</b> : Template location (for external templates) | <span class='light'>Default: </span> <i class='light'>  templates/</i></li>"+
					"<li><b>file</b> : filename (for external templates) | <span class='light'>Default: </span> <i class='light'>  false</i></li>"+
					"<li><b>data</b> : Template (String format, if template is not external data) | <span class='light'>Default: </span> <i class='light'>  false</i></li>"+
					"<li><b>async</b> : ajax boolean, true or false | <span class='light'>Default: </span> <i class='light'>  false</i></li>"+
					"<li><b>load</b> : callback function (if async: true) | <span class='light'>Default: </span> <i class='light'> function(data){return false;}</i></li>"+
					"<li><b>params</b> : params object, for replacing in template | <span class='light'>Default: </span> <i class='light'> {}</i></li>"+

					"</ul></p>"
				}
			}
		},
		"widget" :  {
			group : "fruntWidgetsBasic",
			type : false,
			description : "Returns a widget (HTML)",
			_ret : "String (HTML)",
			params : {
				name : {
					type : "String",
					description : "Widget name and subgroup. If there is a subgroup then a dot would be used in between and subgroup. Ex: <i>simpleList, menu.horizontal, layout.slideshow</i>"	
				},
				data : {
					type : "Varies",
					description : "Data to use, depending on the widget. Generallly it is usually a set of projects, single project, etc."
				},
				"*opts" :  {
					type : "Object",
					description : "Various options for this method. Each widget will have different options, but for javascript you once again have the option of have async on or off, with a callback function. <p style='font-style: normal'>Below are the global options and their defaults for JS sdk.</p><p  style='font-style: normal; '><ul  style='font-style: normal;'>"+
					"<li><b>async</b> : ajax boolean, true or false | <span class='light'>Default: </span> <i class='light'>  false</i></li>"+
					"<li><b>load</b> : callback function (if async: true) | <span class='light'>Default: </span> <i class='light'> function(data){return false;}</i></li>"+
					"</ul></p>"
				}
			}
		},


	},
	//init
	init : function(){
		var that = this;
		$.each(this.methods, function(methodName, method){
			$("#"+method.group).append(that.formatMethod(methodName, method));
		});
		//$("#fruntCoreGet").html(blah);
	}
	
	
}


$(document).ready(function(){
	cmcmDocs.init();
	$("#php_toggle, #js_toggle").on("click", function(){
		$(".code").hide();
		$(".code_"+$(this).attr("data-type")).show();
		$('.sdk').removeClass("active"); 
		$(this).addClass("active");
	});
	
	
	//add subitem
	$.each(cmcmDocs.methods, function(name, o){
		if (o.group=="fruntCoreGet"){
			$("#frunt_core_get").append("<span class='subItem' data-href='#frunt-method-"+name+"'>"+name+"</span>");	
		}
	});
	//fake links subitem
	$(".submenu .subItem").each(function(){
		$(this).on("click", function(e){
			e.preventDefault();
			location.href = $(this).attr("data-href");
		});
	});
	
	$(document).on("scroll", function(){
		//affix
		if ($("#main").position().top<$(document).scrollTop()){
			if (!$(".submenu_wpr").length){
				wpr = $("<div class='submenu_wpr'></div>");
				wpr.append($(".submenu").clone(true));
				$(".submenu").replaceWith(wpr);
			}
		}else{
			if ($(".submenu_wpr").length){
					$(".submenu_wpr").replaceWith($(".submenu").clone(true));
			}

			
		}
	
		$(document).stop();
		$(document).clearQueue();
		clearTimeout(cmcmDocs.timer.scroller);
		cmcmDocs.timer.scroller = setTimeout(function(){
			

			
			//scrollspy
			last = 0;
			found = 0;
			//check sections
			$(".section").each(function(){
				
				if ( $(".section:eq("+last+")").position().top<=$(document).scrollTop() &&
					 $(this).position().top<=$(document).scrollTop()){
					 attr = $(".section:eq("+last+")").attr("data-section");
					 $(".submenu a").removeClass("active");
					 $("#"+attr).addClass("active");
					 found = 1;
				}
				last++;
			});
			if (!found){
				$(".submenu a").removeClass("active");
				if ($(".section:eq(0)").position().top>=$(document).scrollTop()){
					 attr = $(".section:eq(0)").attr("data-section");
					 $(".submenu a").removeClass("active");
					 $("#"+attr).addClass("active");
					 found = 1;
				}
			}
			
			
			
		}, 10);

	});
});