<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
error_reporting(E_ALL | E_STRICT);
//root
$root = "../../../";
//class
require('UploadHandler.php');
//for grabbing config/data settings..
require($root."assets/php/lib/Jdat.class.php");
$settings = Jdat::getSettings($root);
//print_r($settings);
//set settings
$opts = array(
	"upload_dir" => $root.$settings->data->mediaFolder, //upload dir folder ex. ../../images/
	 "upload_url" => $settings->data->mediaFolder, //display url for return from ajax ex. images/
	 "image_versions" => array(
	 	'' => array('auto_orient' => true), //normal size
	 	'thumbnail' => $settings->data->thumb //thumb settings
	 )
);
//init
$upload_handler = new UploadHandler($opts);
