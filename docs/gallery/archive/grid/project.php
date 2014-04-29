<?
	//this page's url is shown as if its in a subdir of actual, so we add this variable for assets
	$EXTRA_URL = "../";
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   
	    'project' => $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array'),
	    'currentPage' => $_GET['id'],
	    'projectInfo' => $frunt->widget("simpleList", $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array'), array(
	    	"ignore" => array("title"), //array or false, ignor certian atts
	    	"only" => false, //array or false, sepcify only certian atts 
	    	"showDefaultIgnores" => false, //true or false, show default ignoreS?
	    	"custom_format" => array(
	    		/*
	    		"published" => array(
	    			"key" => function($k){return "is $k";}, //specify custom  key formattings
	    			"value" => function($v){return ($v) ? "yes" : "no";} //specify custom value formattings
	    		)
	    		*/
	    		
	    	)
	    ))
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('project.html', $relations);

?>
