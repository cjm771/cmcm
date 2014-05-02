<?php

/*
 * EventSource v0.5 for use with CMCM
 * http://chris-malcolm.com/projects/cmcm
 *
 * Copyright 2014, Chris Malcolm
 * http://chris-malcolm.com/
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 *
 *	For initiating various actions on a text stream server 
 *
 */



class EventSource{
	
	const ERROR = 0;
	const PROGRESS = 1;
	const COMPLETE = 2;
	
	private $id = 0;
	private $action = null;
	//optional..set relative path to cmcm root
	private $dir;
	
	const ROOT_DIR = "../../";
	
	function __construct($action, $root=self::ROOT_DIR) {
		require_once($root."assets/php/lib/Jdat.class.php");	
		//initiate
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		header("Access-Control-Allow-Origin: *");
		
		//make sure action,file,data is set
		if (!isset($action)){
			$this->set(self::ERROR, "No action found");
			return false;
		}
		
		$this->dir = $root;
		$this->action = $action;
		$this->init();
	}
	
	//writes error to resp
	private  function init(){
		  
	    $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
		 if ($lastEventId == 0) {
		    $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
		 }
		
		echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
		echo "retry: 2000\n";
		
		// event-stream
		$this->id = $lastEventId;
		  
		  		
		//do action
		switch ($this->action){
			//count to $c, post progress, wait 1 sec, and then set done
			case "testAction":
				$c = $this->id+10;
				while ($this->id < $c) {
					 $this->set(self::PROGRESS, "makin progress..".$this->id);
					 sleep(1);
				}
				$this->set(self::COMPLETE, "DONE!");
				break;
			//grab media files in current data mediaFolder
			case "getMediaFiles":
				//get current data file
				$config = Jdat::getSettings($this->dir);
				if (!isset($config->data->mediaFolder))
					$this->set(self::ERROR, "No media Folder Found.");
				if (!file_exists($this->dir.$config->data->mediaFolder))
					$this->set(self::ERROR, "Media folder does not exist.");
				
				$files = Jdat::getFileList($this->dir.$config->data->mediaFolder, 1, 1);
				if (!$files)
					$this->set(self::ERROR, "No files found.");
				foreach ($files as $file){
					$dims = getimagesize($file);
				}
				$this->set(self::COMPLETE, "DONE!");
				break;
			//we get all media objects and resize based off settings
			case "regenerateThumbs":
				//for resizing
				require_once($this->dir."assets/php/lib/ExternalMedia.class.php");
				//get current data file
				$config = Jdat::getSettings($this->dir);
				if (!isset($config->data->mediaFolder))
					$this->set(self::ERROR, "No media Folder Found.");
				$data = Jdat::get($config->config->src, $this->dir."data");
				if (!$data)
					$this->set(self::ERROR, "No data file found at ".$this->dir."data/".$config->config->src);
				//check projects
				$this->set(self::PROGRESS, "settings: ".json_encode($config->data->thumb));
				$total = 0;
				$count = 0;
				foreach ($data->projects as $projId=>$proj){
					//check media
					foreach ($proj->media as $mediaId=>$media){
						$total++;
					}
				}
				foreach ($data->projects as $projId=>$proj){
					//check media
					foreach ($proj->media as $mediaId=>$media){
						$count++;
						$sendData = array('total'=>$total, 'current'=>$count, 'file'=> $media->src);
						$this->set(self::PROGRESS, "Converting thumb", $sendData);
						//check src and if file exists
						if (isset($media->src) && trim($media->src)!="" && file_exists($this->dir.$media->src)){
							//if exists, check if thumb does, and that thumb is not a filepath
							if (isset($media->thumb) && trim($media->thumb)!="" && file_exists($this->dir.$media->thumb)){
								//resize src and overwrite thumb file
								$thumbPaths = array(
									"src" => $this->dir.$media->src,
									"thumb" => $this->dir.$media->thumb,
									"mode" => "both"
								);
							}else{
								//resize src and save in /thumbnail
								$filename = basename($media->src);
								$dirname = dirname($media->src);
								$thumbPaths = array(
									"src" => $this->dir.$media->src,
									"thumb" => ExternalMedia::getUnique($this->dir.$dirname."/thumbnail/".$filename),
									"mode" => "src"
								);
								//we should now also save thumb attribute
								$data->projects->$projId->media->$mediaId->thumb = $thumbPaths['thumb']; 
							}
						//else if src doesnt exist, check if thumb does
						}else if (isset($media->thumb) && trim($media->thumb)!="" && file_exists($this->dir.$media->thumb)){
								if ($externalType = ExternalMedia::getExternalMediaType($media->src)){
									//its an api grab..lets reget the thumb but save it over existing one
									$exMedia = new ExternalMedia($media->src, $externalType, $this->dir);
									//overwrite weeeeee...(we dont have to shrink this..)
									if ($exMedia){
										$this->set(self::PROGRESS, "Grabbing from $externalType", $sendData);
										$exMedia->getThumbUrl( $this->dir.$media->thumb);
									}
								}else{
									//no known api..just resize thumb to new proportions for now..
									$thumbPaths = array(
										"src" => $this->dir.$media->thumb,
										"thumb" => $this->dir.$media->thumb,
										"mode" => "thumb"
									);
								}
						}
						
						//resize
						if (isset($thumbPaths)){
							$thumbData = ExternalMedia::make_thumb($thumbPaths['src'],  $thumbPaths['thumb'], $config->data->thumb['max_width'], $config->data->thumb['max_height'],$config->data->thumb['crop']);
						
						}
					}
				}
				//save project
				Jdat::set($config->config->src, json_encode($data), 0, $this->dir."data/");	
				$this->set(self::COMPLETE, "DONE!");
				break;

			default:
				$this->set(self::ERROR, "No action found");
				break;
		}
	}
	
	
	private function set($status, $msg=false, $data=false){
		$this->id++;
		$this->renderResponse(array(
			"status" => $status,
			"msg" => $msg,
			"data" => $data
		));
	}
	
	//renders json response
	private function renderResponse($respObj){
		/*
		*	recv obj:
		*		@status - success,progress,error
		*		@msg - message 
		*		@data - opt parameter..
		*/
		echo "id: " . $this->id . "\n";
	    echo "data: " .json_encode($respObj). "\n\n";
	    ob_flush();
	    flush();
	}
}
?>