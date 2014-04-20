<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('index.html', $relations);

?>
