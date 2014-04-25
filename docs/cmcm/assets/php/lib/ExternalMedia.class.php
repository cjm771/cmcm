<?php
/*
 * ExternalMedia v0.5 for use with CMCM
 * http://chris-malcolm.com/projects/cmcm
 *
 * Copyright 2014, Chris Malcolm
 * http://chris-malcolm.com/
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 *
 *	Currently supports youtube, vimeo, soundcloud
 *
 */

class ExternalMedia{
	//<------OPTIONS..ADJUST If necessary ------>//
	
	//soundcloud client id
	const SNDCLD_CLIENT_ID = "35da902b15c13a915bee8c4e36336593";
	//cmcm root directory, relative from php folder
	const ROOT_DIR = "../../";

	//<-------------DONT EDIT BELOW-------------->//
	
	//external url and type
	private $url = "";
	private $type = "";
	//regex, should match with javascript file.
	private static $regex = array(
		"youtube" => "/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9_\-]+)/i",
		"vimeo" => "/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i",
		"soundcloud" => "/\/\/(?:www\.)?(?:api.soundcloud.com|soundcloud.com|snd.sc)\/(.*)$/i"
	);
	private $data = "";
	private $upload_dir; //upload dir folder ex. ../../images/
	private $upload_url; //display url for return from ajax ex. images/
	private $thumbOpts = array();
	
	function __construct($url, $type, $root=self::ROOT_DIR) {
		//for grabbing config/data settings..
		require_once($root."assets/php/lib/Jdat.class.php");
		$settings = Jdat::getSettings($root);
		$this->thumbOpts = $settings->data->thumb;
		//we upload to thumbnail directory of mediafolder
		$this->upload_dir = $root.$settings->data->mediaFolder."thumbnail/";
		$this->upload_url = $settings->data->mediaFolder."thumbnail/";
		
		
		//external media construct
		$this->url = $url;
		$this->type = $type;
		//save data if api is essential
		switch ($this->type){
			case "soundcloud":
				//is this resolve or actual?
				if (strpos($this->url, "http://api.soundcloud.com/tracks/")===false)
					$api = "http://api.soundcloud.com/resolve?format=json&url=".$this->url."&client_id=".self::SNDCLD_CLIENT_ID;
				else
					$api = $this->url.".json?client_id=".self::SNDCLD_CLIENT_ID;
				$resp = $this->get_external_data($api);
				$this->data = json_decode($resp);
				break;
		}
	}
	
	//returns parsed id
	private function parseId(){
		preg_match(self::$regex[$this->type], $this->url, $matches);
		return $matches[1];
	}
	
	//grab regex to determine what it is
	public static function getRegex(){
		return self::$regex;
	}
	//maybe we can find what it i
	public static function getExternalMediaType($url){
		$regex = self::getRegex();
		foreach ($regex as $type=>$regex){
			if (preg_match($regex, $url)){
				return $type;
			}
		}
		return false;
	}
	
	//grab url of src for media (usually same as url) 
	public function getSrcUrl(){
		switch ($this->type){
			case "soundcloud":
				$respObj = $this->data;
				 //this is the streaming url
				if (isset($respObj->uri))
					return $respObj->uri;
				else
					return $this->url;
			break;
			//we can easily use id from existing url for 
			//youtube and vimeo
			default:
				return $this->url;
			break;
		}
		return false;
	}
	
	//grab url of thumb for media 
	public function getThumbUrl($overwrite=0){
		$url = false;
		switch ($this->type){
			case "soundcloud":
				$respObj = $this->data;
				if (isset($respObj->artwork_url)){
					$url = str_replace("large.jpg", "t500x500.jpg", $respObj->artwork_url);
					if ($this->url_exists($url)){
						$url = $this->saveThumbUrl($url,$overwrite);
					}else{
						$url = false;
					}
				}
				break;
			case "vimeo":
				$v = $this->parseId();
				$api = "http://vimeo.com/api/v2/video/".$v.".json";
				$resp = $this->get_external_data($api);
				$respObj = json_decode($resp);
				if (is_array($respObj))
					$respObj = $respObj[0];
				if (isset($respObj->thumbnail_large)){
					$url = $respObj->thumbnail_large;
					if ($this->url_exists($url)){
						$url = $this->saveThumbUrl($url,$overwrite);
					}else{
						$url = false;
					}
				}
					
				break;
			case "youtube":
				$v = $this->parseId();
				$url =  "http://img.youtube.com/vi/".$v."/sddefault.jpg";
				if ($this->url_exists($url)){
					$url = $this->saveThumbUrl($url,$overwrite);
				}else{
					$url = false;
				}
				break;
		}
		return $url; 
	}
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
	
	function url_exists($url){
	    $ch = curl_init($url);    
	    curl_setopt($ch, CURLOPT_NOBODY, true);
	    curl_exec($ch);
	    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	    if($code == 200){
	       $status = true;
	    }else{
	      $status = false;
	    }
	    curl_close($ch);
	   return $status;
	}

	
	//save thumb into tmp folder
	private function saveThumbUrl($imgurl, $overwrite=0){
		if (!$overwrite){
			$tmpDir = $this->upload_dir;
			$file = explode("?", basename($imgurl)); //remove uri
			$imgName = $file[0];
			$f = $tmpDir.$imgName;
			//grab filename with directory that is unique
			$newFilePath = $this->getUnique($f);
		}else{
			//were just overwriting
			$newFilePath = $overwrite;
			
		}
		if ($newFilePath){
		$image = $this->getimg($imgurl); 
			if (!file_exists($tmpDir))
				mkdir($tmpDir);
			//save to temp folder
			file_put_contents($newFilePath,$image); 
			//make thumbnail
			if (!isset($this->thumbOpts['max_width'])) $this->thumbOpts['max_width'] = 1200;
			if (!isset($this->thumbOpts['max_height'])) $this->thumbOpts['max_height'] = 1200;
			$this->make_thumb($newFilePath, $newFilePath, $this->thumbOpts['max_width'], $this->thumbOpts['max_height'], $this->thumbOpts['crop']);
			//make response filepath
			$newFilePath = $this->upload_url.basename($newFilePath);
			return $newFilePath;
		}else{
			return false;
		}
	}
	//download image
	private function getimg($url) {         
	    $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
	    $headers[] = 'Connection: Keep-Alive';         
	    $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
	    $useragent = 'php';         
	    $process = curl_init($url);         
	    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
	    curl_setopt($process, CURLOPT_HEADER, 0);         
	    curl_setopt($process, CURLOPT_USERAGENT, $useragent);         
	    curl_setopt($process, CURLOPT_TIMEOUT, 30);         
	    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
	    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
	    $return = curl_exec($process);         
	    curl_close($process);         
	    return $return;     
	} 

	public static function make_thumb( $image_path, $thumb_path, $max_width = 1200, $max_height = 1200, $force_size, $software='gd2') {

		/*		  
		 if (extension_loaded('imagick')) {
			 $software = "im";
		}
		*/
		$thumbQuality = 95;
		list( $image_width, $image_height, $image_type ) = GetImageSize( $image_path );
		
		//if aspect ratio is to be constrained set crop size
		if ( $force_size ) {
			$newAspect = $max_width/$max_height;
			$oldAspect = $image_width/$image_height;

			if ( $newAspect > $oldAspect ) {
				$cropWidth = $image_width;
				$cropHeight = round( $oldAspect/$newAspect * $image_height );
			} else {
				$cropWidth = round( $newAspect/$oldAspect * $image_width );
				$cropHeight = $image_height;
			}
		//else crop size is image size
		} else {
			$cropWidth = $image_width;
			$cropHeight = $image_height;
		}

		//set cropping offset
		$cropX = floor( ( $image_width-$cropWidth )/2 );
		$cropY = floor( ( $image_height-$cropHeight )/2 );

		//compute width and height of thumbnail to create
		if ( $cropWidth >= $max_width && ( $cropHeight < $max_height || ( $cropHeight > $max_height && round( $cropWidth/$cropHeight * $max_height ) > $max_width ) ) ) {
			$mes = "1st thing";
			$thumbWidth = $max_width;
			$thumbHeight = round( $cropHeight/$cropWidth * $max_width );
		} elseif ( $cropHeight >= $max_height ) {
			$mes = "cropheight larger then max_height";
			$thumbWidth = round( $cropWidth/$cropHeight * $max_height );
		    $thumbHeight = $max_height;
		} else {
			//image is smaller than required dimensions so output it and exit
			//cmcm: actually either one or two dimensions are actually smaller
			$mes = "image is smaller then req dimensions";
			
			$thumbWidth = $cropWidth;
			$thumbHeight = $cropHeight;
			if ($force_size){
				$thumbWidth = $max_width;
				$thumbHeight = $max_height;
			}
			
		}

		switch( $software ) {
			case 'im' : //use ImageMagick
				// hack for square thumbs;
				if ( ( $thumbWidth == $thumbHeight ) or $force_size ) {
					$thumbsize = $thumbWidth;
					if ( $image_height > $image_width ) {
						$cropY = -($thumbsize / 2);
						$cropX = 0;
						$thumbcommand = '{$thumbsize}x';
					} else {
						$cropY = -($thumbsize / 2);
						$cropX = 0;
						$thumbcommand = 'x{$thumbsize}';
					}
				} else {
					$thumbcommand = $thumbWidth.'x'.$thumbHeight;
				}
				$cmd  = '"'.$convert_path.'"';
				if ( $force_size ) $cmd .= ' -gravity center -crop {$thumbWidth}x{$thumbHeight}!+0+0';
				$cmd .= ' -resize {$thumbcommand}';
				if ( $image_type == 2 ) $cmd .= ' -quality $thumbQuality';
				$cmd .= ' -interlace Plane';
				$cmd .= ' +profile "*"';
				$cmd .= ' '.escapeshellarg( $image_path ).' '.escapeshellarg( $thumb_path );
				exec( $cmd );
				break;
			case 'gd2' :
			default : //use GD by default
				//read in image as appropriate type
				switch( $image_type ) {
					case 1 : $image = ImageCreateFromGIF( $image_path ); break;
					case 3 : $image = ImageCreateFromPNG( $image_path ); break;
					case 2 :
					default: $image = ImageCreateFromJPEG( $image_path ); break;
				}
	
				//create blank truecolor image
				$thumb = ImageCreateTrueColor( $thumbWidth,$thumbHeight );
	
				 // Handle transparency in GIF and PNG images:
		        switch ($image_type) {
		            case 1:
		            case 3:
		                imagecolortransparent($image, imagecolorallocate($image, 0, 0, 0));
		            case 3:
		                imagealphablending($image, false);
		                imagesavealpha($image, true);
		                break;
		        }
				
				//resize image with resampling
				ImageCopyResampled( $thumb, $image, 0, 0, $cropX, $cropY, $thumbWidth, $thumbHeight, $cropWidth, $cropHeight );
	
				//set image interlacing
				ImageInterlace( $thumb, 1 );
				//output image of appropriate type
				switch( $image_type ) {
					case 1 :
					ImageGIF($thumb,$thumb_path );
					case 3 :
					 ImagePNG( $thumb,$thumb_path );
					break;
					case 2 :
					default:
					 ImageJPEG( $thumb,$thumb_path,$thumbQuality );
					break;
				}
				ImageDestroy( $image );
				ImageDestroy( $thumb );
				//readfile( $thumb_path );
				break;
		} //<--end switch software
			return array(
				"mes" => $mes,
				"software"=>$software,
				"width" => $thumbWidth,
				"height" => $thumbHeight,
				"origWidth" => $image_width,
				"origHeight" => $image_height,
				"image_path" => $image_path,
				"thumb_path" => $thumb_path
			);
	}
	private function get_external_data($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
}

?>