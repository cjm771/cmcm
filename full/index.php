<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');
	
	//grab cover images as media Objecs
	$coverImages = $frunt->getCoverImages($data->projects);
	
	//slideshow splash...we are only showing coverimages cuz its pretty here..
	$slideshow = $frunt->widget("menu.grid", $frunt->filter($data->projects, array("coverImage", "NOT EQUALS", false)), array(
	));

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
        "content" => $slideshow
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('index.html', $relations);

?>
