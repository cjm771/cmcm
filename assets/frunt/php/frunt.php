<?php

/*
 * Frunt-PHP v0.5 for use with CMCM
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
 */
 class Frunt{
	 
	 //relative to root
	 const CONFIG_FILE = "data/config/config.json";
	 const DATA_DIR = "data/";
	 
	 //actual dir
	 private $CMCM_DIR = ""; 
	 //url to cmcm
	 private $CMCM_URL = ""; 
	 private $config = "";
	 private $data = "";
	 private $opts = "";
	 
	function __construct($CMCM_DIR="", $CMCM_URL="", $opts=false) {
		//set cmcm root
		$this->CMCM_DIR = $CMCM_DIR;
		//grab current cmcm config..ie..current source, setupmode..etc
		$this->config = $this->get($CMCM_DIR.self::CONFIG_FILE);
		
		//default opts
		$this->opts = array(
			"file" =>  $this->config->src,
			"show_unpublished" => false,
			"show_unpublished_media" => false
		);
		//set user opts
		if ($opts)
			$this->opts = $opts + $this->opts;
		
		//initialize
		$this->init();
	}
	
	//initialize the instance
	private function init(){
		//grab data
		$this->data = $this->get($this->CMCM_DIR.self::DATA_DIR.$this->opts['file']);
		//remove unpublished projects
		if (!$this->opts['show_unpublished']){
			foreach ($this->data->projects as $projId=>$proj){
				if (!$proj->published)
					unset($this->data->projects->$projId);
			}
		}
		
		/*
		//remove unpublished media
		if (!$this->opts['show_unpublished)media']){
			foreach ($this->data->projects as $projId=>$proj){
			
				if (!$proj->published)
					unset($this->data->projects->$projId);
			}
		}
		*/
		
	}
	
	//initiate Twig for templating
	public function twig($location="templates/"){
		require_once($this->CMCM_DIR.'assets/frunt/php/lib/Twig/Autoloader.php');
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem($location);
		return new Twig_Environment($loader, array(
			//	'cache' => $location."cache/"
			"autoescape" => false, //we already escape everything
    	));
	}
	
	//get and decode files
	private function get($f){
		return json_decode(file_get_contents($f));
	}
	
	//get project by attribute (default by: id)
	public function getProject($val, $att='id'){
		foreach ($this->data->projects as $projId => $proj)
			if (isset($proj->$att) && $proj->$att==$val){
				return $proj;
			}
		return false;
	}
	
	
	//grab settings
	 public function getSettings($incUsers=false){
	 	$config = $this->config; 
	 	if (!$incUsers)
	 		unset($config->users);
		return $config;
	}
	
	//grab data file
	public function getData($incTemplate=false){
		$data = $this->data;
		if (!$incTemplate)
	 		unset($data->template);
	 	return $data;
	}
	//grab data file
	public function getProjects(){
	 	return $this->data->projects; 
	}
	
	
	//convert data to all arrays or all objects
	public function convert($d, $type='array') {
		switch ($type){
			case 'array':
		        $ret = array();
		        foreach ( (array)$d as $prop => $value ) {
		            if ( is_object( $value ) ) {
		            
		                $ret[ $prop ] = $this->convert($value, 'array');
		                continue;
		            }
		            $ret[ $prop ] = $value;
		        }
	        	return $ret;
		        break;
		    case 'object':
		    	return is_array($d) ? (object) array_map(__METHOD__, $d) : $d;
	    }
	 }	 
 }
 
?>