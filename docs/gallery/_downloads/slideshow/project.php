<?
	//this page's url is shown as if its in a subdir of actual, so we add this variable for assets
	$EXTRA_URL = "../";
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

	//grab the project
	$project =  $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array');

	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	    'project' => $project,
	    'currentPage' => $_GET['id'],
	    //image grid widget
	    'img_gallery' => $frunt->widget('layout',  $project['media'], array(
	    	"type" => "slideshow",
	    	"transition_effect" => "slide",
	    	"autoplay" => false,
	    	"loop_slides" => true,
	    	"slide_controls" => "numbers", 
	    	"no_caption" => false,
	    	"next_on_click" => true,
	    	"media_opts" => array(
				"image" => array(
					"mode" => "modal",
					"fit" => "fill",
					'responsive' => true
				),
				"video" => array(
					"mode" => "modal",
					"fit" => "fill",
					"real_fit" => "fill",
					"no_ratio" => true,
					'responsive' => true
				),
				"sound" => array(
					"mode" => "modal",
					"fit" => "fill",
					"real_fit" => "fill",
					"no_ratio" => true,
					'responsive' => true
				)
			),
	    )),
	    //simple attribute list for project info
	    'projectInfo' => $frunt->widget("simpleList", $project, array(
	    	"ignore" => array("title"), //array or false, ignor certian atts
	    	"only" => false, //array or false, sepcify only certian atts 
	    	"showDefaultIgnores" => false, //true or false, show default ignoreS?
	    	"custom_format" => array(
	    		"description" => array(
	    			"value" => function($v) use ($frunt){
	    				return $frunt->convert($v, "html,breaks,cmcm_code");
	    			}
	    		)    		
	    	)
	    ))
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('project.html', $relations);

?>
