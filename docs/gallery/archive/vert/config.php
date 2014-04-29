<?php
	
	//<-------EDIT THIS FOR YOUR SITE---------------->
	
	/*
	$CMCM_ROOT = $_SERVER['DOCUMENT_ROOT']."/"; //abs link from this script to cmcm FILE ROOT
	$CMCM_URL = "/"; //abs link to CMCM URL ROOT ex http://chris-malcolm.com/cmcm/
	$SITE_URL = "/vert/"; //abs link to your SITE URL ROOT ex http://chris-malcolm.com/cmcm/home/
	*/
	
	$CMCM_ROOT = "../"; //parent: cmcm file root (filesystem)..can be relative or abs (file)
	$CMCM_URL = "../"; //parent: cmcm root (url)..can be relative or abs (url)
	$SITE_URL = "./"; //current: site root (url)..can be relative or abs (url)
	
	
	//<-------/EDIT THIS FOR YOUR SITE---------------->
	
	
	//include frunt-php sdk
	require_once($CMCM_ROOT."assets/frunt/php/frunt.php");
	//prepended to vars above, if supplied by specific page
	$EXTRA_URL = (isset($EXTRA_URL)) ? $EXTRA_URL : ""; 
	
	//instantiate new object while pointing to cmcm dir
	$frunt = new Frunt($CMCM_ROOT, $CMCM_URL, array(
		//"file" => "data.json", //set another file thats not current
	));
	
	//grab all data of current config file
	$data = $frunt->getData();

	//initiate twig templater
	$engine = $frunt->twig();
	
	//DEFAULT: stuff to include for every page
	$relations = array(
		'cmcm' => $CMCM_ROOT,
		'site_url' => $EXTRA_URL.$SITE_URL,
		'cmcm_url' => $EXTRA_URL.$CMCM_URL,
        'title' => $data->title,
        'subtitle' => $data->subtitle,
        'description' => $data->description,
        'projects' => $frunt->convert($data->projects, 'array')
     );
?>