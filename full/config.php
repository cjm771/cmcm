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
		"file" => "data.json", //set another file thats not current
	));
	
	
	//news 
	//instantiate new object while pointing to cmcm dir
	$frunt2 = new Frunt($CMCM_ROOT, $EXTRA_URL.$CMCM_URL, $EXTRA_URL.$SITE_URL, array(
		"file" => "news.json", //set another file thats not current
	));
	//lets format the dates
	$news = $frunt2->getProjects();
	foreach ($news as $newsId=>&$newsObj){
		$news[$newsId]['added'] = $frunt2->convert( $newsObj['added'],'date', "m/d/y");
	}
	//<--end news
	
	
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
        'news' => $news
     );
?>