<?php

/*
 * Frunt-PHP widgets lib v0.5 for use with CMCM
 * http://chris-malcolm.com/projects/cmcm
 *
 * Copyright 2014, Chris Malcolm
 * http://chris-malcolm.com/
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 *
 *	Frunt is a set of sdk(s) to read,parse, and work 
 *  with data files generated with cmcm. Use, contribute, Enjoy!
 *
 *	Widgets lib for reusable code chunks..some may require the frunt.js and frunt.css files
 *
 */
 
 class FruntWidget{
	 
	 const TMPL_DIR = "templates/";
	 
	  //actual dir
	 private $CMCM_DIR = ""; 
	 //url to cmcm
	 private $CMCM_URL = "";
	 //site url
	 private $SITE_URL = "";
	  //widget templates
	 private $TMPL_FOLDER = "";
	 
	 private $data = "";
	 //widget name
	 private $wName = "";
	 //widget opts
	 private $opts = "";
	 
	 private $twig = "";
	
	 
	 function __construct($widgetName="menu",  $data="", $widgetOpts="", $CMCM_DIR="", $CMCM_URL="", $SITE_URL="") {
	 	
	 	//set up vars
	 	$this->CMCM_DIR = $CMCM_DIR;
	 	$this->CMCM_URL = $CMCM_URL;  	
	 	$this->SITE_URL = $SITE_URL; 
	 	$this->TMPL_FOLDER = $CMCM_DIR."assets/frunt/php/".self::TMPL_DIR;	
	 	//for static funcs
	 	require_once($this->CMCM_DIR.'assets/frunt/php/frunt.php');
	 	
	 	//widget vars
	 	$this->data = $data;
	 	$this->wName = $widgetName;
	 	$this->opts = $widgetOpts;
	 	
	 	//initiate twig
	 	$this->twig = $this->twig();
	 }
	 
	 public function render(){
	 	 //get widget
	 	 $widgetName = $this->wName;
		 return $this->$widgetName($this->opts);
	 }
	 
	 //initiate Twig for templating
	public function twig(){
		require_once($this->CMCM_DIR.'assets/frunt/php/lib/Twig/Autoloader.php');
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem($this->TMPL_FOLDER);
		return new Twig_Environment($loader, array(
			//	'cache' => $location."cache/"
			"autoescape" => false, //we already escape everything
    	));
	}

	//preview widget
	 public function preview(){
		
		 $defaults = array(
			"src" => false,
			"thumb" => false,
			"caption" => false,
			"media_type" => "image",
			"modal_group" => "modal",
			"mode" => "none",
			"responsive" => true,
			"real-fit" => "within",
			'bias' => false,
			"sync-parent" => false,
			"fit" => "fill",
			"autoplay" => false,
			"use-thumb" => false,
			"no-ratio" => false
		 );
		 
 		//if user supplies media, we can grab most from just that
		if (isset($this->data)){
			$mediaObj = array(
				"src" =>  isset($this->data['src']) ? $this->data['src'] : false,
				"thumb" => isset($this->data['thumb']) ? $this->data['thumb'] : false,
				"caption" =>  isset($this->data['caption']) ? $this->data['caption'] : false,
				"media_type" => isset($this->data['type']) ? $this->data['type'] : false 
			);
			//extend it!
			
			$defaults =  $mediaObj + $defaults;
			
		}
	
		//set user opts
		$this->opts = $this->opts + $defaults;	

		//render media preview
		return $this->twig->render('media_preview.php', array(
			"cmcm_url" => $this->CMCM_URL,
			"mediaOpts" => $this->opts,
			"media" => $this->data
		));
	 }
	 
	 //layout widget
	 public function layout(){
		//default media opts
		$defaults_media = array(
			"mode" => "none",
			//responsive settings
			"responsive" => true,
			"real_fit" => "within",
			'bias' => false,
			"fit" => "fill",
			"no_ratio" => false
		);
		
		$image_media = array(
			"use_thumb" => false
		);
		$video_media = array(
			"autoplay" => true
		);
		$sound_media = array(
			"autoplay" => true,
			"visual" => true
		);
		
		//default opts
		$defaults = array( 
			"type" => "grid",
			//GRID SPECIFIC
			"sort_by" => false, //false,string, or array 
			"force_cols" => false, //false or int force break after x, change media_wpr dimension to percentages,
			"ascOrDesc" => "desc", //asc or desc, direction of list
			//SLIDESHOW SPECIFIC
			"autoplay" => false, //false or delay length, 
			"transition_effect" => "slide", //slide or fade, effect of transition
			"transition_length" => 400, //int, length of transition
			"next_on_click" => true, //true or false, if slide click goes to next
			"loop_slides" => true, //true or false, if next is clicked on last slide, it should return to first
			"slide_controls" => false, //false, numbers,dots,or thumbs
			//GENERAL
			"document_scroll" => false, //true or false, whether controls should track document or slider scroll
			"use_thumb" => false, //true or false, for image type media
			"just_thumbs" => false, //true or false, for just thumb
			"media_opts" => array(
				"image" => $image_media + $defaults_media,
				"video" => $video_media + $defaults_media,
				"sound" => $sound_media + $defaults_media
			),
			"no_caption" => false, //dont show caption
		);
		
		//media opts defaults
		if (isset($this->opts['media_opts'])){
			foreach ($defaults['media_opts'] as $type=>$o){
				if (isset($this->opts['media_opts'][$type]))
					$this->opts['media_opts'][$type] =  $this->opts['media_opts'][$type] + $o;
				else
					$this->opts['media_opts'][$type] = $defaults['media_opts'][$type];
			}
			
		}
		//set user opts
		$this->opts = $this->opts + $defaults;
		
		
		switch($this->opts['type']){
			case "vertical":
			case "horizontal":
			case "slideshow":
			return $this->twig->render("layout_".(($this->opts['type']=="vertical") ? "horizontal" :  $this->opts['type']).".php", array_merge($this->opts, array(
				"media" => $this->data,
				"site_url" => $this->SITE_URL,
				"cmcm_url" => $this->CMCM_URL
			)));
				break;
			case "grid":
			//if sortby..we wanna only grab the 1st if array...it will be a string..
			if ($this->opts['sort_by']){
				if(is_array($this->opts['sort_by'])){
					$this->opts['sort_by'] = array_shift($this->opts['sort_by']);
				}
				//regroup stuff
				$this->data = Frunt::group($this->opts['sort_by'], $this->data, $this->opts['ascOrDesc']);
			}else{
				$this->data = array($this->data);
			}
			
			return $this->twig->render("layout_grid.php", array_merge($this->opts, array(
				"media" => $this->data,
				"site_url" => $this->SITE_URL,
				"cmcm_url" => $this->CMCM_URL
			)));
			break;
		}
	 }
	 
	 
	 //simple list widget
	 public function simpleList(){
		//default opts
		$defaults = array(
 			"template" => "<span class='key'>{{key}}</span>: <span class='val'>{{val}}</span>", //template for each list item
 			//default for everything
 			"default_format" => array(
 				"key" => function($k){return str_replace("_", " ", $k);},
 				"value" => function($v){return $v;}
 			),
 			//custom formats for types (prefixed with *) and individual attributes
 			"custom_format" => array(
 				"*bool" => array(
 					"value"=> function($v){ return ($v) ? "yes" : "no";}
 				)
 			),
 			//listype
 			"list_type" => "project",
 			//elements to ignore..defaults, and additionals
 			"showDefaultIgnores" => false,
 			"ignore_default" => array(
 				"project" => array("id","published","added","cleanUrl","coverImage","media"),
 				"media" => array("visible","src","type","thumb")
 			),
 			"ignore" => false, 
 			//restrict elements to the following
 			"only" => false
		);
		//set user opts
		$this->opts = $this->opts + $defaults;
		
		//filter
		$rules = array();
		//filter out default ignores
		if (!$this->opts['showDefaultIgnores'])
			$rules[] = array($this->opts['ignore_default'][$this->opts['list_type']], "IGNORE");
		//filter out ignore list 
		$rules[] = array($this->opts['ignore'], "IGNORE");
		//pick out only..;
		$rules[] = array($this->opts['only'], "ONLY");
		$this->data = Frunt::filter($this->data, $rules);
		
		$FINAL = array();
		//go through each thing to get formatting
		foreach ($this->data as $key=>$value){
			//for types
			if (substr($key, 0, 1)=="*"){
				
			}else if (isset($this->opts["custom_format"][$key])){
				if	(isset($this->opts["custom_format"][$key]['key']))
					$newKey = $this->opts["custom_format"][$key]['key']($key);
				else
					$newKey = $this->opts["default_format"]['key']($key);
				if	(isset($this->opts["custom_format"][$key]['value']))
					$newVal = $this->opts["custom_format"][$key]['value']($value);
				else
					$newVal = $this->opts["default_format"]['value']($value);
			}else{
					$newKey = $this->opts["default_format"]['key']($key);
					$newVal = $this->opts["default_format"]['value']($value);
			}
			
			$FINAL[$newKey] = $newVal;
		
		}
		//display
		$items = array();
		foreach ($FINAL as $key=>$value){
			if (is_array($value))
				$value = "(".count($value).")";
			$tmp = str_replace("{{key}}" ,   $key,   $this->opts['template']);
			$tmp = str_replace("{{val}}" , $value, $tmp);
			$items[$key] = $tmp;
		}
		 return $this->twig->render("simple_list.php", array_merge($this->opts, array(
			"items" => $items,
			"site_url" => $this->SITE_URL,
		)));
	 }
	 
	 //menu widget
	 public function menu(){
	 //	print_r(self::group("year", $this->data));
		//default opts
		$defaults = array(
			"identifier" => "cleanUrl", //cleanUrl or id
			"current" => false, //current identifier
			"getQuery" => "id", //name of $_GET variable that holds identifier
			"url_rewrite" => "projects/", //if set, url will be written as specifed followed by indentifier
			"type" => "basic", //basic: vertical, horiz, submenu: shows next to menu, thumb
			"ascOrDesc" => "desc", //asc or desc, direction of list
			"extras" => false,
			//MENU TYPE-SPECIFIC OPTIONS
			//basic, submenu
			"collapse" =>  (isset($this->opts['type']) && $this->opts['type']=="horizontal") ? false : true,  //true or false, to collapse if sort by
			"collapse_multiple_fans" => false, //true or false, multiple submenus can be revealed or only allow one at a time
			"collapse_current" => false, //string or false, which one to reveal on init
			//basic, submenu, horiz
			"sort_by" => false, //array,string,false of attribute to sort by,
			//thumb
			"no_title" => false, //dont show title 
			"force_cols" => false, //false or int force break after x, change media_wpr dimension to percentages 
			
		);
		//set user opts
		$this->opts = $this->opts + $defaults;
		
		//setup
		switch ($this->opts['type']){
			case "grid":
				//if sortby..we wanna only grab the 1st if array...it will be a string..
				if ($this->opts['sort_by']){
					if(is_array($this->opts['sort_by'])){
						$this->opts['sort_by'] = array_shift($this->opts['sort_by']);
					}
					//regroup stuff
					$this->data = Frunt::group($this->opts['sort_by'], $this->data, $this->opts['ascOrDesc']);
				}else{
					$this->data = array($this->data);
				}
				//print_r($this->data);
				return $this->twig->render("menu_grid.php", array_merge($this->opts, array(
					"projects" => $this->data,
					"site_url" => $this->SITE_URL,
					"cmcm_url" => $this->CMCM_URL
				)));
				
				break;
			case "horizontal":
				//if sortby
				if ($this->opts['sort_by']){
					if (is_string($this->opts['sort_by'])){
						$this->opts['sort_by'] = array($this->opts['sort_by']);
						
					}
					
					$cols = array();
					foreach ($this->opts['sort_by'] as $index=>$sortKey){
						if ($index!=0){
							$tmp =  Frunt::getExistingValues($sortKey, $this->data);
							$res = array();
							foreach ($tmp as $possibleValue){
								$res[$possibleValue] = array();
								for($i=0; $i<$index; $i++){
									$tmpKey = $this->opts['sort_by'][$i];
									$res[$possibleValue][$tmpKey] = Frunt::getExistingValuesByCond($tmpKey, array(
										array($sortKey, "EQUALS", $possibleValue)
									), $this->data, $this->opts['ascOrDesc']);
						 		}
							}
							 $cols[$sortKey] = $res;
						 }else{
							 $cols[$sortKey] = Frunt::getExistingValues($sortKey, $this->data, $this->opts['ascOrDesc']);
						 }
					}
					$res = array();
					Frunt::sort($this->data,  $this->opts['ascOrDesc'], 2, 'title');
					
					foreach ($this->data as $projId=>$proj){
						//echo $proj['title']."\n";
						 $res[$projId] = Frunt::getAttributes($this->opts['sort_by'], $proj, $this->opts['ascOrDesc']);
					}
					
					$cols["projects"] = $res;
				}
				
				 return $this->twig->render("menu_horiz.php", array_merge($this->opts, array(
					"projects" => $this->data,
					"site_url" => $this->SITE_URL,
					"col_data" => isset($cols) ? $cols : false
				)));
				break;
			case "basic":
			case "vertical":
			default:
				//if sortby
				if ($this->opts['sort_by']){
					if (is_string($this->opts['sort_by'])){
						$this->opts['sort_by'] = array($this->opts['sort_by']);
						
					}
					if(is_array($this->opts['sort_by'])){
						//regroup stuff
						 $this->data = Frunt::group($this->opts['sort_by'], $this->data, $this->opts['ascOrDesc']);
						 //print_r($this->data);
					}else{
						//do nothing
						$this->opts['sort_by'] = false;
					}
				}
				
				 //print_r($this->opts);
				 return $this->twig->render("menu_basic.php", array_merge($this->opts, array(
					"projects" => $this->data,
					"site_url" => $this->SITE_URL
				)));
				break;
		}	
	 }
	 
	 //slideshow widget
	 //scroll widget
	 //preview widget
	 //list widget
}
?>


