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
	 private $templates = "";
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
		$this->templates = $this->data->template;
		
		//grab template keys
		$keys = array(
			'project' => array_keys($this->convert($this->templates->project, 'array')),
			'media' =>  array_keys($this->convert($this->templates->media, 'array'))
		);
		
		//PROJECTS
		//convert
		$this->data->projects  = $this->convert($this->data->projects, 'array');
		//remove unpublished projects
		if (!$this->opts['show_unpublished']){
			foreach ($this->data->projects as $projId=>&$proj){
				if (!$proj['published']){
					unset($this->data->projects[$projId]);
				}
			}
		}	
		//organize project att by templates
		self::multisort($this->data->projects, "asc", 3, $keys['project']);
		//MEDIA
		//remove unpublished media and reorder by 
		
		foreach ($this->data->projects as $projId => &$proj){
			foreach ($proj['media'] as $mediaId => &$media){
				if (!$this->opts['show_unpublished_media']){
					if (!$media['visible']){
						unset($this->data->projects[$projId]["media"][$mediaId]);
					}
				}
			}
			self::multisort($this->data->projects[$projId]["media"], "asc", 3, $keys['media']);		
		}
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
			}else if(isset($proj[$att]) && $proj[$att]==$val){
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
	public function getData(){
		
		$data = $this->data;
	 	return $data;
	}
	//grab data file
	public function getProjects(){
	 	return $this->data->projects; 
	}
	//get template by name or if no option, get all of em
	public function getTemplates($type=false){
		
		if ($type){
			return $this->data->template->$type;
		}
		else
			return $this->data->template;
	}
	//get template attribute
	public function getTemplateAttribute($att, $type){
		if (isset($this->data->template->$att)){
			return $this->data->template->$att;
		}else{
			return false;
		}
	}
	
	//get attributes from specific project/media
	public static function getAttributes($arr,$dataObj, $ascOrDesc="desc"){
		$ret = array();
		if (is_string($arr))
			$arr = array($arr);
		foreach ($arr as $key){
			$ret[$key] = (isset($dataObj[$key])) ? $dataObj[$key] : "undefined"; 
		}
		self::sort($ret, $ascOrDesc, 1);
		return $ret;
	}
	
	//grab possible values from filtered data
	public static function getExistingValuesByCond($searchKey, $condArray, $dataObj,  $ascOrDesc="desc"){
		$data = self::filter($dataObj, $condArray);
		$values = self::getExistingValues($searchKey, $data, $ascOrDesc);
		return $values;
	}
	
	//grab possible values from data
	public static function getExistingValues($searchKey, $dataObj, $ascOrDesc="desc"){
		$arr = array();
		foreach ($dataObj as $projId=>$projObj){
			//if its got that attr and value is not in arr already
			if (isset($projObj[$searchKey]) && !in_array($projObj[$searchKey], $arr)){
				$arr[] = $projObj[$searchKey];
			}else if (!isset($projObj[$searchKey]) &&  !in_array("undefined", $arr)){
				$arr[] = "undefined";
			}
			
		}
		self::sort($arr, $ascOrDesc);
		return $arr;
					
	}
	
	//same as sort, except same routine done to all child arrays
	public static function multisort(&$dataObj, $ascOrDesc="asc", $key=false, $att=false){
		foreach ($dataObj as $id=>&$obj){
			self::sort($dataObj[$id], $ascOrDesc, $key, $att);
		}
	}
	
	/********************************
	 *   sort with several options 
	 *	(0: sort by values, 
	 *	 1 : sort by keys, 
	 *	 2: sort by given attribute, 
	 *	 3: sort by ordered keys)
	 ********************************/
	public static function sort(&$dataObj, $ascOrDesc="asc", $key=false, $att=false){
		
		if ($key<=1){
			if ($ascOrDesc=="asc"){
				(!$key) ? sort($dataObj) :  ksort($dataObj);
			}else{
				(!$key) ? rsort($dataObj) : krsort($dataObj);
			}
		//by attribute
		}else if ($key==2){
			$sortByAtt = function($a, $b) use ($att) {
				 return strcmp(strtolower($a[$att]), strtolower($b[$att]));
			};
			usort($dataObj, $sortByAtt);
			if ($ascOrDesc=="desc"){
				$dataObj = array_reverse($dataObj);
			}
		//by array of new order keys
		}else if ($key==3){
			
			$newArr = array();
			$oldArr = $dataObj;
			foreach ($att as $key){
				if (isset($oldArr[$key])){
					$newArr[$key] = $oldArr[$key]; 
					unset($oldArr[$key]);
				}
			}
			
			//sort remaining
			if ($ascOrDesc=="asc")
				ksort($oldArr);
			else
				krsort($oldArr);
			
			//merge ordered and extras
			$dataObj = array_merge($newArr, $oldArr);
			//$dataObj = $newArr;
		}
	}
	
	//filter out by rules..DATA MUST BE ARRAY
	public static function filter($data, $rules, $satisfy="all"){
		//$data = (object) $data;
		//check for individual
		$MULTI = true;
		if (isset($data["id"])){
			$data = array($data);
			$MULTI = false;
		}
		$res = ($satisfy=="all") ? $data : array();
		
		if (!is_array($rules[0])){
			$rules = array($rules);
		}
		//print_r($rules);
		foreach ($rules as $index=>$rule){
			$attr = (isset($rule[0])) ? $rule[0] : false;
			$cond = (isset($rule[1])) ? $rule[1] : false;
			$val =  (isset($rule[2])) ? $rule[2] : false;
			$D = ($satisfy=="all") ? $res : $data;
			switch($cond){
				default:
					foreach ((($satisfy=="all") ? $res : $data) as $projId=>$proj){
							if (!is_array($attr)){
								if (!isset($proj[$attr]))
									$proj[$attr] = "undefined";
							}
							switch($cond){
								case 'IGNORE':
									if ($attr!=false){
										if (is_string($attr))
											$attr = array($attr);
										foreach($attr as $index=>$att){
											if (isset($res[$projId][$att]))
												unset($res[$projId][$att]);
										}
									}
									break;
								case 'ONLY':
									if ($attr!=false){
										$tmpArr = array();
										if (is_string($attr))
											$attr = array($attr);
										foreach($attr as $index=>$att){
											if (isset($res[$projId][$att]))
												$tmpArr[$att] = $res[$projId][$att];
										}
										$res[$projId] = $tmpArr;
									}
									break;
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

					}
					break;
			}
		}
		if ($MULTI==false){
			$res = $res[0];
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
				if ($ascOrDesc=="asc")
					ksort($subGroups);
				else
					krsort($subGroups);
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