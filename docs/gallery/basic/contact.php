<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');
	
	//we use templates in the adjacent templates/ folder
	echo $engine->render('contact.html', $relations);

?>
