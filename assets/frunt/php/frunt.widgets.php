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
	
	 //soundPreview 
	 public function soundPreview(){
	 	$defaults = array(
	 		"type" => "sound",
			"subtype" => "soundcloud", //string, type of sound (soundcloud, local*) *not implemented yet 
			"url" => false, //url for embedding
			"visual" => false, //visual mode for soundcloud,
			"embed" => "", //embed code (filled in later) 
		);
		//set user opts
		$this->opts = $this->opts + $defaults;
		switch ($this->opts['type']){
			case "soundcloud":
				$this->opts['embed'] = '<iframe width="100%" height="100%" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.$this->opts['url'].'&amp;auto_play=false&amp;hide_related=false&amp;visual='.$this->opts['visual'].'"></iframe>'; 
				break;
		}
		 return $this->twig->render("preview_embed.php", array("opts"=>$this->opts));
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
			//MENU TYPE-SPECIFIC OPTIONS
			//basic, submenu
			"collapse" => false, //true or false, to collapse if sort by
			"collapse_multiple_fans" => true, //true or false, multiple submenus can be revealed or only allow one at a time
			"collapse_current" => false, //string or false, which one to reveal on init
			//basic, submenu, horiz
			"sort_by" => "year", //array,string,false of attribute to sort by,
			//horiz
			"horiz_cols" => 1, //array,int for each section, how many cols
			//thumb
			"thumb_cols" => false, //false or int force break after x 
			"force_cols" => false, //if cols are specified..make images fit within it (hardcode dimensions)
			"padding_x" => false, //int right padding between squares
			"padding_y" => false, //int bottom padding between squares
			"extras" => false
		);
		//set user opts
		$this->opts = $this->opts + $defaults;
		
		//setup
		switch ($this->opts['type']){
		
			case "basic":
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


