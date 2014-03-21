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
 	 //site url
 	 private $SITE_URL = "";
	 private $config = "";
	 private $data = "";
	 private $opts = "";
	 
	function __construct($CMCM_DIR="", $CMCM_URL="", $SITE_URL="", $opts=false) {
		//set cmcm root
		$this->CMCM_DIR = $CMCM_DIR;
		$this->CMCM_URL = $CMCM_URL;
		$this->SITE_URL = $SITE_URL;
		//grab current cmcm config..ie..current source, setupmode..etc
		$this->config = $this->get($CMCM_DIR.self::CONFIG_FILE);
		
		//default opts
		$this->opts = array(
			"file" =>  $this->config->src,
			"show_unpublished" => false,
			"show_unpublished_media" => false,
			"load_widget_lib" => true
		);
		//set user opts
		if ($opts)
			$this->opts = $opts + $this->opts;
		
		//initialize
		$this->init();
	}
	
	//initialize the instance
	private function init(){
		//load widget
		if ($this->opts['load_widget_lib'])
			require_once($this->CMCM_DIR.'assets/frunt/php/frunt.widgets.php');
	
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
	public  function twig($location="templates/"){
		require_once($this->CMCM_DIR.'assets/frunt/php/lib/Twig/Autoloader.php');
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem($location);
		return new Twig_Environment($loader, array(
			//	'cache' => $location."cache/"
			"autoescape" => false, //we already escape everything
    	));
	}
	
	//run widget
	public function widget($type="", $data, $opts=""){
		
		$widget =  new FruntWidget($type, $data, $opts,  $this->CMCM_DIR, $this->CMCM_URL, $this->SITE_URL);
		return $widget->render();
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
	
	//grab possible values from data
	public static function getExistingValues($searchKey, $dataObj){
		$arr = array();
		foreach ($dataObj as $projId=>$projObj){
			//if its got that attr and value is not in arr already
			if (isset($projObj[$searchKey]) && !in_array($projObj[$searchKey], $arr)){
				$arr[] = $projObj[$searchKey];
			}else if (!isset($projObj[$searchKey]) &&  !in_array("undefined", $arr)){
				$arr[] = "undefined";
			}
			
		}
		return $arr;
					
	}
	
	//filter out by rules..DATA MUST BE ARRAY
	public static function filter($data, $rules, $satisfy="all"){
		//$data = (object) $data;
		$res = ($satisfy=="all") ? $data : array();
		
		if (!is_array($rules[0])){
			$rules = array($rules);
		}
		//print_r($rules);
		foreach ($rules as $index=>$rule){
			$attr = $rule[0];
			$cond = $rule[1];
			$val =  $rule[2];
			foreach ((($satisfy=="all") ? $res : $data) as $projId=>$proj){
				//print_r($proj);
				//if (isset($proj[$attr])){
					if (!isset($proj[$attr]))
						$proj[$attr] = "undefined";
					switch($cond){
						default:
						case "EQUALS":
							//condition to meet
							$eval = ($proj[$attr] == $val);
							//check for condition
							if ($satisfy=="all"){
								if (!$eval)
									unset($res[$projId]);
							}else{
								if ($eval)
									$res[$projId] = $data[$projId];
							}
							break;
						case "NOT EQUALS":
							//condition to meet
							$eval = ($proj[$attr] != $val);
							//check for condition
							if ($satisfy=="all"){
								if (!$eval)
									unset($res[$projId]);
							}else{
								if ($eval)
									$res[$projId] = $data[$projId];
							}
							break;
					}	
				/*
				}else{
					//condition to meet...wanting undefined
					$eval = ($val == "undefined");
					//check for condition
					if ($satisfy=="all"){
						if (!$eval)
							unset($res[$projId]);
					}else{
						if ($eval)
							$res[$projId] = $data[$projId];
					}
				}
				*/
			}
		}
		return $res;
	}
	
	//group together
	public static function group($sort_arr, $data, $ascOrDesc="desc"){
	
		$LAST = false;
		if (is_string($sort_arr))
			$sort_arr = array($sort_arr);
		if (!empty($sort_arr)){
			$searchKey = array_shift($sort_arr);
			if (empty($sort_arr))
				$LAST = true;
			$possibleValues = self::getExistingValues($searchKey, $data);
			$subLists = array();
			foreach ($possibleValues as $v){
					$subLists[$v] = self::filter($data, array($searchKey, "EQUALS", $v), "all");
			}
			if ($LAST){
				//last one, just return group
				if ($ascOrDesc=="asc")
					ksort($subLists);
				else
					krsort($subLists);
				return $subLists;
			}else{
				//more subgroups
				$subGroups = array();
				foreach ($subLists as $key=>$list)
					$subGroups[$key]  = self::group($sort_arr, $list, $ascOrDesc);
				return $subGroups;
			}
		}
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