<?php
class Jdat{
	
	//relative to php folder 
	const DEMO_MODE = 0;
	const DATA_DIR = "../../data";  
	const PROJ_ID_PRE = "PROJ_";
	const ROOT_DIR = "../../";
	const CONFIG_FILE = "../../data/config/config.json";
	
	function __construct() {
		//do nothing
	}

	//grab data files
	public static function getFileList($dir="", $keepDir=0, $scanSubDir=0){
		$dir = ($dir) ? $dir : self::DATA_DIR."/" ;
		$remove = array(".", "..");
		if (!is_dir($dir))
			return false;
		$arr = scandir($dir);
		$arrNew = array();
		foreach ($arr as $file){
			//ignore hidden files, and ignore list
			if (!in_array($file, $remove) && substr($file, 0, 1)!=".")
				if (!is_dir($dir.$file)) //normal file
					$arrNew[] = ($keepDir) ? $dir.$file: $file;
				elseif ($scanSubDir==1 && is_dir($dir.$file)) //subdirectory..opt check
					$arrNew = array_merge($arrNew, self::getFileList($dir.$file."/", $keepDir, $scanSubDir));
		}
		
		return $arrNew;
	}
	
	//grabs basic settings..both config and data
	public static function getSettings($dir=self::ROOT_DIR, $getUsers=0){
		require_once($dir."assets/php/lib/Login.class.php");
		$config = Login::loadConfig($dir,!$getUsers);
		//grab data file in config
		$data = self::get($config->src, $dir."data");
		//unset projects and templates
		$toUnset = array("projects", "template");
		foreach ($toUnset as $index=>$key){
			if (isset($data->$key)){
				unset($data->$key);
			}
		}
		$data->thumb = (array)$data->thumb;
		$toCheck = array("max_width", "max_height");
		//check for wildcardss.and remove them
		foreach ($toCheck as $index=>$key){
			if (trim($data->thumb[$key])=="*")
				$data->thumb[$key] = 1200;
		}
		return (object) array("config"=>$config, "data"=>$data);
	}
	
	
	public static function getProjectById($id, $data){
		foreach ($data as $projId=>$proj){
			if ($id == $proj->id){
				//overwrite prev project
				return array(
					"value" => $proj,
					"key" => $projId
				);
			}
		}
		return false;
	}
	
	//get unique project id # and new counter
	public static function getProjIdAndCounter($data){
		$max = 1000;
		$count = 0;
		$counter = $data->template->project->id->counter;
		$proj = (array) $data->projects;
		$id = self::PROJ_ID_PRE.$counter;
		while (isset($proj[$id]) || $count>$max){
			$count++;
			$counter++;
			$id = self::PROJ_ID_PRE.$counter;
		}
		$numberId = $counter;
		$counter++;
		if ($count>$max)
			return false;
		else
			return array(
				"id" => $numberId,
				"idName" => $id,
				"counter" => $counter
			);
	}
	
	//edit Attributes
	public static function edit($f, $newContent){
		$data = self::get($f);
		foreach ($newContent as $key=>$val){
			$data->$key = $val;
		}
		if (self::set($f, self::sanitize(json_encode($data))))
			return true;
		else 
			return false;
	}
	
	//generate a pretty url var
	public static function cleanUrl($str, $length){
			$uristub = $str;
			// set the locale, just once
			setlocale(LC_ALL, 'en_US.UTF8');
			// clean the uristub
			$uristub = iconv('UTF-8', 'ASCII//TRANSLIT', $uristub);
			$uristub = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $uristub);
			$uristub = preg_replace("/[\/_|+ -]+/", '-', $uristub);
			$uristub = strtolower(trim($uristub, '-'));

			// ensure uristub is less than length
			if (strlen($uristub) > $length) {
				// get char at chopped position
				$char = $uristub[$length-1];
				// quick chop (leave room for 9 iterations)
				$uristub = substr($uristub, 0, $length - 1);

				// if we chopped mid word
				if ($char != '-') {
					$pos = strrpos($uristub, '-');
					if ($pos !== FALSE) {
						$uristub = substr($uristub, 0, $pos);
					}
				}
			}
			
			//search others
			
			return $uristub;

	}
	
	//!!!!!delete media @$files = mediaObj, not filepath
	public static function deleteMedia($files, $otherSizes=false){	
		if (!is_array($files))
			$files = array($files);
		$files = (array) $files;
		$ret = array();
		foreach ($files as $i=>$f){
			//delete images..
			switch($f->type){
				case "image":
					$path_parts = pathinfo($f->src);
					if (file_exists(self::ROOT_DIR.$f->src)){
						$tmppath = self::ROOT_DIR.$f->src;
						if (@unlink($tmppath))
							$ret[] = $tmppath;
						//other sizes? thumbs? deletteee
						if ($otherSizes){
							foreach($otherSizes as $j=>$folder){
								$tmppath = self::ROOT_DIR.$path_parts['dirname']."/".$folder."/".$path_parts['basename'];
								if(@unlink($tmppath))
									$ret[] = $tmppath;
							}
						}
					}
					break;
				case "video":
				case "sound":
					//these only have a thumb..
					if (file_exists(self::ROOT_DIR.$f->thumb)){
						$tmppath = self::ROOT_DIR.$f->thumb;
						if (@unlink($tmppath))
							$ret[] = $tmppath;
					}
					break;
					
			}
		}
		return $ret;
	
	}
	
	public static function getUnconsolidatedFiles($f, $fileAtts, $root=self::ROOT_DIR){
		$data = self::get($f);
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
							if (file_exists($root.$media->$key))
								$unconsolidated[] = array("file" =>$media->$key, "key" =>$key,"mediaId"=> $mediaId, "mediaIndex"=>$count, "projId" => $projId);
						}
					}
				}
			}
		}
		return $unconsolidated;

	}
	
	
	public static function consolidateFiles($fileSrc, $data, $fileAtts, $root=self::ROOT_DIR){
		$unconsolidated = array();
		if (!is_string($data)){
			/*
			 * recv: mediaId, projId, key
			 */	
			 $unconsolidated[] = $data;
			 $data = self::get($fileSrc);
		}else{
			$data = self::get($fileSrc);
			$unconsolidated = self::getUnconsolidatedFiles($fileSrc, $fileAtts, $root);
		}
		$ret = array(); 
		foreach ($unconsolidated as $i=>$f){
			/*
			 * recv: file: src, key: thumb or src, mediaId: mediaId, mediaIndex : count, projId : x
			 */
			 
			 $f =  (object) $f;
			 $key = $f->key;
			 $mediaId = $f->mediaId;
			 $projId = $f->projId;
			 
			 $mediaDir = ($key == "thumb") ?  $data->mediaFolder."thumbnail/" : $data->mediaFolder;
			 
			 $dest =  self::getUnique($root.$mediaDir.basename($f->file));
			 //if move set in thing
			 if (@copy($root.$f->file, $dest)){
			 	@unlink($root.$f->file);
			 	$data->projects->$projId->media->$mediaId->$key = $mediaDir.basename($dest);
		 		if (!isset($ret["success"]))
					$ret["success"] = array();
				$ret["success"][] = $f->file;
			}else{
				if (!isset($ret["error"]))
						$ret["error"] = array();
					$ret["error"][] = $root.$f->file." to ".$dest;
			}
			
		}
		if (!self::set($fileSrc, json_encode($data), 0, $root."data/")){
			if (!isset($ret["error"]))
				$ret["error"] = array();
			$ret["error"][] = "Also could not save data file.";
		}
			
		return $ret;
	}
	
	
	//simple delete file function
	public static function deleteFiles($files, $root=self::ROOT_DIR){
		$ret = array();
		foreach ($files as $i=>$f){
			if (file_exists($root.$f)){
				if (@unlink($root.$f)){
					if (!isset($ret["success"]))
						$ret["success"] = array();
					$ret["success"][] = $f;
				}
				else{
					if (!isset($ret["error"]))
						$ret["error"] = array();
					$ret["error"][] = $f;
				}
			}
		}
		return $ret;
	}	
	
	
	//search keys to get unique
	public static function getUniqueStr($str, $search_arr){
		$count = 0;
		$final = $str;
		while (in_array($final, $search_arr)){
			$count++;
			$final = $str."-".$count;
		}
		return $final;
	}
	
	//grab values of a specifc attribute
	public static function getAttrValues($key, $data){
		$res = array();
		foreach ($data as $proj){
			foreach ($proj as $k=>$v){
				if ($key==$k)
					$res[] = $v;
			}
		}
		return $res;
	}

	//get unique filename given desired path
	public static function getUnique($f){
		
		$dirPath = dirname($f);
		$orig = basename($f);
		$count = 0;
		$f = $orig;
		$count_max=1000;
		while (file_exists($dirPath."/".$f)){
			$count++;
			$prefix = "($count)";
			$f = $prefix.$orig;
			if ($count>$count_max)
				return false;
		}
		$f = $dirPath."/".$f;
		return $f;
	}
	
	//clean up json string	
	public static function sanitize($content){

			//strip single quote slashes
			$content =  str_replace("\\'", "'", $content);
			//pretty print
			$content = self::pretty_print($content);
			//strip foward slash slashes
			$content =  str_replace("\/", "/", $content);
			return $content;
	}
	
	/* cstore file (save)..content should be json string
	* 
	*	@flag = 0 (nothing), 1 (dont overwrite, throw error), 2 (dont overwrite, add postfix)
	*
	*/
	public static function set($f, $content, $flag="", $dataDir=""){
		if (self::DEMO_MODE)
			return false;
		if ($dataDir=="") $dataDir = self::DATA_DIR."/";
		//check unique, return false if not
		if ($flag==1){
			if (self::exists($f)){
				return false;
			}
		//force unique
		}else if ($flag==2){
			$dirPath = dirname($f);
			$orig = basename($f);
			$count = 0;
			$f = $orig;
			$count_max=1000;
			while (self::exists($dirPath."/".$f)){
				$count++;
				$prefix = "($count)";
				$f = $prefix.$orig;
				if ($count>$count_max)
					return false;
			}
			$f = $dirPath."/".$f;
		}
		//dir should be
		$path = ($dataDir.$f);
		
		if (file_put_contents($path, self::sanitize($content))){
			return $f;
		}else{
			return false;
		}
	}
	public static function exists($f){
		return file_exists(self::DATA_DIR."/".$f);
	}
	//retrieve file (load) $dir=directory or 1 for $f=full path
	public static function get($f, $dir=self::DATA_DIR){
		if ($dir==1)
			$path = $f;
		else
			$path = $dir."/".$f;
		if (!file_exists($path)){
			return false;
		}else{
			$data = file_get_contents($path);
			//convert to array
			return json_decode($data);
		}
	}
	public static function jdat_array_search($search, $arr){
		foreach($arr as $key=>$value){
			if (trim($search)==trim($value))
				return $key;
		}
		return -1;
	}
	//pretty print, for readable json
	public function pretty_print($json)
	{
		    $tab = "		";
		    $new_json = "";
		    $indent_level = 0;
		    $in_string = false;
		
		    $json_obj = json_decode($json);
		
		    if($json_obj === false)
		        return false;
		
		    $json = json_encode($json_obj);
		    $len = strlen($json);
		
		    for($c = 0; $c < $len; $c++)
		    {
		        $char = $json[$c];
		        switch($char)
		        {
		            case '{':
		            case '[':
		                if(!$in_string)
		                {
		                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
		                    $indent_level++;
		                }
		                else
		                {
		                    $new_json .= $char;
		                }
		                break;
		            case '}':
		            case ']':
		                if(!$in_string)
		                {
		                    $indent_level--;
		                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
		                }
		                else
		                {
		                    $new_json .= $char;
		                }
		                break;
		            case ',':
		                if(!$in_string)
		                {
		                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
		                }
		                else
		                {
		                    $new_json .= $char;
		                }
		                break;
		            case ':':
		                if(!$in_string)
		                {
		                    $new_json .= ": ";
		                }
		                else
		                {
		                    $new_json .= $char;
		                }
		                break;
		            case '"':
		                if($c > 0 && $json[$c-1] != '\\')
		                {
		                    $in_string = !$in_string;
		                }
		            default:
		                $new_json .= $char;
		                break;                   
		        }
		    }
		
		    return $new_json;
		}	

	
}
?>