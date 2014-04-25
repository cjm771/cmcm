<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	    "page_title" => "Contact",
	    "content" => "	<div class='genericContainer'>
	<h4>Contact</h4>
	<p>
	For inquiries, please feel free to contact me:
	</p>
	<p>
	<a href='mailto:chris@example.com'>chris@example.com</a>
	</p>
	</div>"
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('blank.html', $relations);

?>
