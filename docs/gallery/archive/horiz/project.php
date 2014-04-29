<?
	//this page's url is shown as if its in a subdir of actual, so we add this variable for assets
	$EXTRA_URL = "../";
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   
	    'project' => $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array'),
	    'currentPage' => $_GET['id'],
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('project.html', $relations);

?>
