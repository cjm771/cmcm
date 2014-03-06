<?php
	session_start();
	//dependencies
	require_once("lib/Jdat.class.php");
		
	class Ajax{
		
		//media attr associated with files
		private $file_atts =  array("thumb", "src");
		private $resp = array("error" => "Unknown error occurred");
		//actions read as streams (not ajax) should be below
		public  $streamActions = array("regenerateThumbs");
		function __construct() {
			//make sure action,file,data is set
			if (!isset($_GET['a'])){
				$this->throwError("No action Found");
				return false;
			}
			if (!isset($_GET['f'])){
				$this->throwError("No file Found");
				return false;
			}
			if (!isset($_GET['skey']) || ($_GET['skey']!=md5($_SESSION['skey']))){
				$this->throwError("Not authorized");
				return false;
			}
			
			//do action
			switch ($_GET['a']){
				case "save":	
					if (!$this->dataPosted())
						return false;
					//simpleSave
					$this->saveData($_GET['f'], $_POST['data']);	
					break;
				case "regenerateThumbs":
					//stream event source...to get feedback for perhaps a long process..
					require_once("lib/EventSource.class.php");
					$server = new EventSource("regenerateThumbs", Jdat::ROOT_DIR);
					break;
				case "srcManager":
					require_once("lib/Login.class.php");
					$data = Login::loadConfig(Jdat::ROOT_DIR);
					
					/* actions i.e. backup, new, saveAs, load, delete
					 * 
					 * ex  {"action" : "load", "f" : "xx.json"}
					 * ex2 {"action" : "new" }
					 * ex3 {"action" : "delete", "f" : "xx.json"}
					 *
					 */
					$actions =  json_decode($this->urlDecode($_POST['data']));
					if (!($actions->action)){
						$this->throwError("No src manager action found");
						return false;
					}	
					switch ($actions->action){
						case "backup":
							if (!$actions->f)
								$this->throwError("No file found");
							else{
								$file_name = "backups/".date("ymd")."-backup-".$actions->f;
								$data = Jdat::get($actions->f);
								//set @file, @content, @flag=2 : force unique
								if ($f = Jdat::set($file_name, json_encode($data), 2)){
									$this->throwSuccess($data, array(
									"success_msg" => "Backup saved as ".$f
									));
								}else{
									$this->throwError("Could not save backup. Unknown reason.");
								}
								
							}	
							break;
						case "delete":
							if (!$actions->f || !$actions->fallback)
								$this->throwError("No file or fallback file found.");
							if (Login::editConfig(array("src" =>$actions->fallback),Jdat::ROOT_DIR)){
								if (@unlink(Jdat::DATA_DIR."/".$actions->f))
									$this->throwSuccess($actions->f,array("success_msg" => "File Deleted."));
								else
									$this->throwSuccess($actions->f,array("success_msg" => "File Source changed but could not delete file for some reason."));
							}else
								$this->throwError("Could not edit config file.");
							break;
						case "load":
							if (!$actions->f)
								$this->throwError("No file found");
							else{
								if (Login::editConfig(array("src" =>$actions->f),Jdat::ROOT_DIR))
									$this->throwSuccess($actions->f,array("success_msg" => "File loaded."));
								else
									$this->throwError("Could not load file.");	
							}
							break;
						case "new":
							break;
						case "saveAs":
							require_once("lib/Login.class.php");
							if (!$actions->f || !$actions->saveAs)
								$this->throwError("No file or name found");
							else{
								$file_name = $actions->saveAs;
								$data = Jdat::get($actions->f);
								//set @file, @content, @flag=2 : force unique
								if ($f = Jdat::set($file_name, json_encode($data), 1)){
									$data = Login::loadConfig(Jdat::ROOT_DIR);
									$data->src = $f;
									if (Login::saveConfig(Login::sanitize(json_encode($data)), Jdat::ROOT_DIR)){
										$this->throwSuccess($data, array(
										"success_msg" => "Saved new file as ".$f
										));
									}else{
										$this->throwError("Saved a new file but could not update Config.");
									}
								}else{
									$this->throwError("Could not save as $file_name. Name could be already taken. Check and try again.");
								}
								
							}
							break;
						default:
							$this->throwError("Could not find action (".$actions->action.")");
							break;
					}
					break;
				case "getUnconsolidatedFiles":
					$fileAtts = $this->file_atts;
					$data = Jdat::get($_GET['f']);
					$data->mediaFolder = trim($data->mediaFolder);
					$unconsolidated = array();
					foreach ($data->projects as $projId=>$proj){
						//lets look for files
						$count=0;
						foreach ($proj->media as $mediaId=>$media){
							//lets look at atts associated with files 
							$count++;
							foreach ($fileAtts as $key){
								if (trim($media->$key)!=""){
									if (strpos($media->$key, $data->mediaFolder) === false || strpos($media->$key, $data->mediaFolder) !=0){
										if (file_exists(Jdat::ROOT_DIR.$media->$key))
											$unconsolidated[] = array("file" =>$media->$key, "key" =>$key,"mediaId"=> $mediaId, "mediaIndex"=>$count, "projId" => $projId);
									}
								}
							}
						}
					}
					$this->throwSuccess($unconsolidated);
					break;
				case "getUnlinkedFiles":
					$fileAtts = $this->file_atts;
					$data = Jdat::get($_GET['f']);
					$unlinked=array();
					//value 1 is for keeping directory with file
					if (file_exists(Jdat::ROOT_DIR.$data->mediaFolder)){
						$mediaFiles = Jdat::getFileList(Jdat::ROOT_DIR.$data->mediaFolder, 1,1);
					/*
						if (file_exists(Jdat::ROOT_DIR.$data->mediaFolder."thumbnail/"))
							$thumbs = Jdat::getFileList(Jdat::ROOT_DIR.$data->mediaFolder."thumbnail/", 1);
						else
							$thumbs = array();
							
						$unlinked = array_merge($mediaFiles, $thumbs);
					*/
						$unlinked = $mediaFiles;
						
						foreach ($data->projects as $projId=>$proj){
							//lets look for files
							foreach ($proj->media as $mediaId=>$media){
								//lets look at atts associated with files 
								foreach ($fileAtts as $key){
									if ($index = array_search(Jdat::ROOT_DIR.$media->$key, $unlinked)){
										unset($unlinked[$index]);
									}
								}
							}
						}
					}
					$this->throwSuccess($unlinked);
					break;
				case "getFileList":
					$data = json_decode($this->urlDecode($_POST['data']));
					//make sure we recieve folder ex ->folder = '/backups' or '/' for root
					if (!isset($data->folder)){
						$this->throwError("Could not find folder");
						return false;
					}
					//set folder
					$folder = Jdat::DATA_DIR.$data->folder;
					//if folder exists
					if (!dir($folder)){
						$this->throwError("Folder does not exist");
						return false;
					}
					
					//get file list
					if ($files = Jdat::getFileList($folder))			
						$this->throwSuccess($files);
					else
						$this->throwError("Could not load file list.");
					break;
				case "editConfig":
					require_once("lib/Login.class.php");
					$data = Login::loadConfig(Jdat::ROOT_DIR);
					$config =  json_decode($this->urlDecode($_POST['data']));
					$config->data = (array) $config->data;
					$config->config = (array) $config->config;
				
					foreach ($config->data as $key=>$val){
						
					}
					//go through config stuff
					if (!Login::editConfig($config->config, Jdat::ROOT_DIR)){
						$this->throwError("Could not save Config file.");
						return false;
					}else{
						//go through data stuff
						if (!Jdat::edit($_GET['f'], $config->data)){
							$this->throwError("Saved Config, but could not save Data file.");
							return false;
						}else{
							$this->throwSuccess($config->data, array("success_msg"=>"Saved settings."));
						}
						
					}	
					break;
				case "addExternalMedia":
					require_once("lib/ExternalMedia.class.php");
					//grabs thumbs,and other data ..returns that
					if (!$this->dataPosted())
						return false;
					$ret = array();
					//recv [{url : xx.com, type: 'soundcloud'}, {url : yy.xx, type : 'youtube'}]
					$links =  json_decode($this->urlDecode($_POST['data']));
					foreach ($links as $index=>$exMedia){
						//initialize
						$ex =  new ExternalMedia($exMedia->url,$exMedia->type);
						 $thumbUrl = $ex->getThumbUrl();
						//grab info
						$ret[] = array(
							"thumb" =>  ($thumbUrl) ? $thumbUrl : "" ,
							"src" =>  $ex->getSrcUrl(),
							"type" => $exMedia->type
						);
						
					}
					//return array of media item
					$this->throwSuccess($ret);
					break;
				case "loadConfig":
					require_once("lib/Login.class.php");
					$data = Login::loadConfig(Jdat::ROOT_DIR);
					if ($data){
						foreach ($data->users as $user=>$settings){
							$data->users[$user]->password = "";
						}
						$this->throwSuccess($data, array(
						"success_msg" => "Config file loaded."
						));
					}
					else
						$this->throwError("Could not load config file:".Jdat::ROOT_DIR.Login::CONFIG_FILE);
					break;
				case "login":
					if (!$this->dataPosted())
						return false;
					require_once("lib/Login.class.php");
					$creds =  json_decode($this->urlDecode($_POST['data']));
					if (isset($creds->username) && isset($creds->password)){
						$login = new Login($creds->username, $creds->password, 1, Jdat::ROOT_DIR);
						$resp = $login->validate();
						if (isset($resp['error']))
							$this->throwError($resp['error']);
						else
							$this->throwSuccess($resp);
					}else{
						$this->throwError("Username or Password is missing");
					}
					break;
				case "deleteUser":
					if (!$this->dataPosted())
						return false;
					require_once("lib/Login.class.php");
					//we recieve ->user
					$data = json_decode($this->urlDecode($_POST['data']));
					if (isset($_SESSION['username']) && isset($_SESSION['password'])){
						//instantiate cred instance
						$login = new Login($_SESSION['username'], $_SESSION['password'], 0, Jdat::ROOT_DIR);
						//authenticate
						if  (!$login->authenticate()){
							$this->throwError("Could not authenticate your credentials to make changes");
							return false;
						}
						//remove user
						$resp = $login->removeUser($data->user);
						if ($resp['error'])
							$this->throwError($resp['error']);
						else
							$this->throwSuccess($data->user, array("success_msg" => "Deleted User."));
					}else{
						$this->throwError("You are not logged in to make changes.");
					}
					break;	
				case "addUser":
					if (!$this->dataPosted())
						return false;
					require_once("lib/Login.class.php");
					//we recieve username, confirm_pw, new_pw, is_new
					$creds =  json_decode($this->urlDecode($_POST['data']));
					if (isset($_SESSION['username']) && isset($_SESSION['password'])){
						//instantiate cred instance
						$login = new Login($_SESSION['username'], $_SESSION['password'], 0, Jdat::ROOT_DIR);
						//authenticate
						if  (!$login->authenticate()){
							$this->throwError("Could not authenticate your credentials to make changes");
							return false;
						}
						//add user
						$resp = $login->addUser($creds->username, $creds->confirm_pw, $creds->new_pw, $creds->existingUser);
						
						//handle response
						if (isset($resp['error']))
							$this->throwError($resp['error']);
						else
							$this->throwSuccess($resp, array("success_msg" => "Added User."));
					}else{
						$this->throwError("You are not logged in to make changes.");
					}
					break;
				case "sortProjects":
					if (!$this->dataPosted())
						return false;
						//must url custom decode if we working with data
					$list =  json_decode($this->urlDecode($_POST['data']));
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					$rand = array();
					$projectOld = $data->projects;
					$projectNew = array();
						foreach ($list as $i=>$newIndex){
							//gets value and key in an array
							$projInfo = Jdat::getProjectById($newIndex, $data->projects);
							$projectNew[$projInfo['key']] = $projInfo['value'];
							$rand[] = $projInfo['key'];
							unset($projectOld[$projInfo['key']]);
						}
						//extra projects?a add
						foreach ($projectOld as $projId=>$proj){
							$projectNew[$projId] = $proj;
						}
						//apply the thing..
						$data->projects = $projectNew;
						//save
						$this->saveData($_GET['f'], json_encode($data),
						array(
							"noData" => 1
						));
					break;
				case "updateTemplates":
					if (!$this->dataPosted())
						return	false;
					$templates = json_decode($this->urlDecode($_POST['data']));
					$data = Jdat::get($_GET['f']);
					$data->template = $templates;
					$this->saveData($_GET['f'], json_encode($data),array(
						"success_msg" => "Templates saved."
					));
					break;
				case "deleteMediaAttributes":
					/* recv
					 *
					 * {PROJ_2 : {[mediakey] : [key_to_del, key_to_del2]}  
					 *
					 */
					if (!$this->dataPosted())
						return false;
					$projs = json_decode($this->urlDecode($_POST['data']));
					$projs = (array) $projs;
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					foreach ($projs as $projId=>$proj){
						if (isset($data->projects[$projId])){
							foreach ($proj as $mediaKey=>$mediaAttributes){
								if (isset($data->projects[$projId]->media->$mediaKey)){
									foreach ($mediaAttributes as $index=>$key){
										if (isset($data->projects[$projId]->media->$mediaKey->$key)){
											unset($data->projects[$projId]->media->$mediaKey->$key);
										}else{
											$this->throwError("Attribute for media not found.");
											return false;	
										}
									}
								}else{
									$this->throwError("Media key in project not found.");
									return false;
								}
							}
						}else{
							$this->throwError("Project ID not found.");
							return false;
						}
					}
					//save
					$this->saveData($_GET['f'], json_encode($data),array("noData" => 1, "success_msg"=>"Media attribute(s) deleted."));
					break;
				case "deleteAttributes":
					/* recv
					 *
					 * {PROJ_2 : [key_to_del, key_to_del2]}  
					 *
					 */
					if (!$this->dataPosted())
						return false;
					$projs = json_decode($this->urlDecode($_POST['data']));
					$projs = (array) $projs;
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					foreach ($projs as $projId=>$proj){
						foreach ($proj as $index=>$key){
							if (isset($data->projects[$projId])){
								if (isset($data->projects[$projId]->$key))
									unset($data->projects[$projId]->$key);
								else{
									$this->throwError("Attribute ($key) not found.");
									return false;
								}
								
							}else{
								$this->throwError("Project ID not found.");
								return false;
							}
						}
					}
					//save
					$this->saveData($_GET['f'], json_encode($data),array("noData" => 1, "projs"=>$projs,  "success_msg"=>"Attribute(s) deleted."));
					break;
				case "editMediaAttributes":
					/* recv
					 *
					 * {PROJ_2 : {mediaKey : {key: value, key2 : value2}}  
					 *
					 */
					if (!$this->dataPosted())
						return false;
					$projs = json_decode($this->urlDecode($_POST['data']));
					$projs = (array) $projs;
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					foreach ($projs as $projId=>$media){
						foreach ($media as $mediaKey=>$attsObj){
							if (isset($data->projects[$projId])){
								if (isset($data->projects[$projId]->media->$mediaKey)){
									foreach ($attsObj as $k=>$v){
										$data->projects[$projId]->media->$mediaKey->$k = $v;
									}
								}else{
									$this->throwError("Error: Media id (".$mediaKey.") in project (".$projId.") not found.");
									return false;
								}
								
							}else{
								$this->throwError("Error: Project id not found.");
							}
						}
					}
					//save
					$this->saveData($_GET['f'], json_encode($data),array("noData" => 1, "projs"=>$projs,  "success_msg"=>"Media attribute(s) edited."));
					break;
				case "editAttributes":
					/* recv
					 *
					 * {PROJ_2 : {key: value, key2 : value2}}  
					 *
					 */
					if (!$this->dataPosted())
						return false;
					$projs = json_decode($this->urlDecode($_POST['data']));
					$projs = (array) $projs;
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					foreach ($projs as $projId=>$proj){
						if (!isset($data->projects[$projId])){
							$this->throwError("Project id not found.");
							return false;
						}
						foreach ($proj as $k=>$v){
							
								$data->projects[$projId] = (array) $data->projects[$projId];
								$data->projects[$projId][$k] = $v; 
						}
					}
					//save
					$this->saveData($_GET['f'], json_encode($data),array("noData" => 1, "projs"=>$projs,  "success_msg"=>"Attribute(s) edited."));
					break;
				//called on unloaded pages, with unsaved uploads..purges files..
				case "handleUnsavedMedia":
					if (!$this->dataPosted())
						return false;
					//recv..{projId : (mediaObj1, mediaObj2} }
					$toCheck = json_decode($this->urlDecode($_POST['data']));
					//data
					$data = Jdat::get($_GET['f']);
					//to delete
					$toDelete = array();
					foreach ($toCheck as $projIndex => $mediaArr){
						
						switch ($projIndex){
							case "new":
								//its brand new! delete 'em all!
								foreach($mediaArr as $mediaId=>$mediaObj){
									$toDelete[] = $mediaObj;
								}
								break;
							default: 
								//we need to check the saved version of projId
								$proj = Jdat::getProjectById($projIndex, $data->projects);
								$projMedia = $proj['value']->media;
								//lets go through the media and find ones that dont exist
								foreach ($projMedia as $mediaId=>$mediaObj){
									//already existed before...remove
									
									if (isset($mediaArr->$mediaId)){
										unset($mediaArr->$mediaId);
									}
								}
								foreach($mediaArr as $mediaId=>$mediaObj){
									$toDelete[] = $mediaObj;
								}
								break;
						}
						
					}
					//now we have can send the file(s) for deletion..
					$ret = Jdat::deleteMedia($toDelete, array("thumbnail"));	
					$this->throwSuccess($ret, array("success_msg"=>"Media Deleted"));
					break;			
				case "deleteMediaFiles":
					if (!$this->dataPosted())
						return false;
					//delete media and thumbnail
					//recv: [mediaobj, mediaobj2]
					$mediaObjs = json_decode($this->urlDecode($_POST['data']));
					//delete em
					$ret = Jdat::deleteMedia($mediaObjs, array("thumbnail"));	
					
					$this->throwSuccess($ret, array("success_msg"=>"Media Deleted"));
					break;
				case "deleteProjects":
					if (!$this->dataPosted())
						return false;
					$projs = json_decode($this->urlDecode($_POST['data']));
					$projs = (array) $projs;
					$ret = "";
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					foreach ($projs as $projId=>$opts){
						if (isset($data->projects[$projId])){
							if ($opts->deleteMedia==1){
								//delete media and thumbnail
								$ret = Jdat::deleteMedia($data->projects[$projId]->media, array(
									"thumbnail"
								));
							}
							unset($data->projects[$projId]);
							
						}else{
							$this->throwError("Error: Project could not be deleted.");
						}
						
					}
					//save
					$this->saveData($_GET['f'], json_encode($data),array("noData" => 1, "filesToDelete"=>$ret));
					break;
				case "editProject":
					if (!$this->dataPosted())
						return false;
					//must url custom decode if we working with data
					$project = json_decode($this->urlDecode($_POST['data']));
					$data = Jdat::get($_GET['f']);
					$data->projects = (array) $data->projects;
					$found = 0;
					foreach ($data->projects as $projId=>$proj){
						if ($project->id == $proj->id){
							//overwrite prev project
							$data->projects[$projId] = $project;
							//save
							$this->saveData($_GET['f'], json_encode($data),array(
								"project_id" => $id
							));
							$found = 1;
						}	
					}
					if ($found==0)
						$this->throwError("Could not find that project to save. It might have been deleted.");
						return false;
					break;
				case "addProject":
					if (!$this->dataPosted())
						return false;
					//must url custom decode if we working with data
					$project = json_decode($this->urlDecode($_POST['data']));
					$data = Jdat::get($_GET['f']);
					if ($data){
						$arr = Jdat::getProjIdAndCounter($data);
						if (!$arr){
							$this->throwError("Could not generate unique Id");
							return false;
						}else{
							$id = $arr['id'];
							$idName = $arr['idName'];
							$counter =  $arr['counter'];
							//add project
							$project->id = $id;
							
							$cleanUrl =  Jdat::cleanUrl($project->title, 30);
							//find unique number
							$project->cleanUrl = Jdat::getUniqueStr($cleanUrl, Jdat::getAttrValues("cleanUrl", $data->projects));
							$data->projects = (array) $data->projects;
							$data->projects[$idName] = $project;
							//set new counter for next id
							$data->template->project->id->counter = $counter;
							
							//SAVE..add extras to return
							$this->saveData($_GET['f'], json_encode($data),array(
								"project_id" => $id
							));
						}
					}
					else
						$this->throwError("Could not load file.");
					break;
				case "listFiles":
				
					$files = Jdat::getFileList();
					if ($files)
						$this->throwSuccess(Jdat::getFileList());
					else
						$this->throwError("Directory could not be scanned.");
					break;
				case "load":
					require_once("lib/Login.class.php");
					//grab config file..
					$config = Login::loadConfig(Jdat::ROOT_DIR, 1);
					if (!$config || !isset($config->src)){
						$this->throwError("Could not load config file.");
						return false;
					}
					//now get data file..
					$data = Jdat::get($config->src);
					if ($data)
						$this->throwSuccess(array("data" => $data, "config" => $config));
					else
						$this->throwError("Could not load data file.");
					break;
				default:
					$this->throwError("Action ({$_GET['a']}) does not exist.");
					break;
			}
		}
		
		//writes error to resp
		public  function throwError($msg){
			$this->resp = array("error" => $msg);
		}
		
		//saves data .. @f : file,
		public function saveData($f, $data, $extra=false){
			
			$success_arr = array(
					"file"=>$_GET['f'],
					"message"=> "File was saved",
					"data"=>urldecode($data)
			);
			$SET = Jdat::set($f, urldecode($data));
			if ($extra){
				if (isset($extra['noData'])){
					unset($success_arr['data']);
					unset($extra['noData']);
				}
				$success_arr = array_merge($success_arr, $extra);
			}
			if (isset($extra['success_msg']))
				$success_arr['success_msg'] = $extra['success_msg'];
				
			if ($SET)
				$this->throwSuccess($success_arr);
			else
				$this->throwError("Could not save file.");
		}
		
		//check if data posted
		public function dataPosted(){
			if( !isset($_POST['data'])){
				$this->throwError("No content Found / Post:".json_encode($_POST)." Get:".json_encode($_GET));
				return false;
			}else{
				return true;
			}
		}
		
		//custom url decode...to solve single quote problem.
		public function urlDecode($str){
			return str_replace("%27", "'", rawurldecode($str));
		}
		
		//writes success to resp with optional data parameter
		public  function throwSuccess($data="", $extra=""){
			$resp = array("success" => 1, "data" => $data);
			if ($extra)
				$resp = array_merge($resp, $extra);
			$this->resp = $resp;
		}
		
		//renders json response
		public  function renderJsonResponse(){
			echo json_encode($this->resp);
			//echo $this->pretty_print(json_encode($this->resp));
		}
	}
	
	//new ajax instance
	$ajax = new Ajax();
	//render response if not a stream feed
	if (!in_array($_GET['a'], $ajax->streamActions))
		$ajax->renderJsonResponse();
?>