<?php
	
	//<-------EDIT THIS FOR YOUR SITE---------------->
	
	$CMCM_ROOT = "../"; //parent: cmcm file root (filesystem)..can be relative or abs (file)
	$CMCM_URL = "../"; //parent: cmcm root (url)..can be relative or abs (url)
	$SITE_URL = "./"; //current: site root (url)..can be relative or abs (url)
	
	
	//<-------/EDIT THIS FOR YOUR SITE---------------->
	
	
	//include frunt-php sdk
	require_once($CMCM_ROOT."assets/frunt/php/frunt.php");
	
	//prepended to vars above, if supplied by specific page
	$EXTRA_URL = (isset($EXTRA_URL)) ? $EXTRA_URL : ""; 

	//instantiate new object while pointing to cmcm dir
	$frunt = new Frunt($CMCM_ROOT, $EXTRA_URL.$CMCM_URL, $EXTRA_URL.$SITE_URL, array(
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
        //we use the menu widget..cuz its dope
        'menu' => $frunt->widget("menu.vertical", $frunt->convert($data->projects, 'array'), array(
        	"current" => (isset($_GET['id'])) ? $_GET['id'] : basename($_SERVER['PHP_SELF']), //indicate current page..project or file
        	 "sort_by" => "year", //sort by..creates headers
        	 "ascOrDesc" => "desc", //show list descending
        	 'headers' => array('Projects', 'Info'),
        	 "collapse" => true,//collapse by header
        	 "collapse_multiple_fans" => false,  //only allow one fan at a time
        	 "collapse_current" => false, //default group to show
        	"extras" => array(  //add additonal links
        		"Contact" => "contact.php",
        		"About" => "about.php",
        		
        	)
        ))
     );
?>