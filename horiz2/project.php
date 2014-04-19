<?
	//this page's url is shown as if its in a subdir of actual, so we add this variable for assets
	$EXTRA_URL = "../";
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');
	
	$project = $frunt->getProject($_GET['id'], 'cleanUrl');
	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   
	    'project' => $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array'),
	    'currentPage' => $_GET['id'],
	     //image grid widget
	    'img_gallery' => $frunt->widget('layout.horizontal',  $project['media'], array(
	    	"slide_controls" => "dots", 
	    	"document_scroll" => true,
	    	"no_caption" => false,
	    	"media_opts" => array(
				"image" => array(
					"mode" => "modal-noIcon",
					"bias" => "parent-height",
					"sync_parent" => 2
				),
				"video" => array(
					"mode" => "thumb",
					"bias" => "parent-height",
					"sync_parent" => 2
				),
				"sound" => array(
					"mode" => "thumb",
					"bias" => "parent-height",
					"sync_parent" => 2
				)
			),
	    )),
	    'projectInfo' => $frunt->widget("simpleList", $frunt->convert($frunt->getProject($_GET['id'], 'cleanUrl'), 'array'), array(
			"ignore" => array("title"),
			 "template" => "<span class='key'>{{key}}</span><span class='val'>{{val}}</span>",
			 "custom_format" => array(
	    		"description" => array(
	    			"value" => function($v){return nl2br(html_entity_decode($v));}
	    		)
	    	)	    		
	    ))
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('project.html', $relations);

?>
