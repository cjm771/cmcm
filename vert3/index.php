<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');
	
	//grab cover images as media Objecs
	$coverImages = $frunt->getCoverImages($data->projects);
	
	//slideshow splash
	$slideshow = $frunt->widget("layout.slideshow", $coverImages, array(
		'autoplay'=> 5000,
		'transition_effect' => 'fade',
		'next_on_click' => false,
		'no_caption' => true
	
	));
	
	
	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   /*
	   "content" => $frunt->widget("menu", $frunt->convert($data->projects, 'array'), array(
        	"type" => "grid",
        	"current" => (isset($_GET['id'])) ? $_GET['id'] : basename($_SERVER['PHP_SELF']), //indicate current page..project or file
        	 "sort_by" => "", //sort by..creates headers
        	 "ascOrDesc" => "desc", //show list descending
        	 "collapse" => true,//collapse by header
        	 "no_title" => true, //dont show title 
        	 "force_cols" => 4, //force amount of columns
        ))
        */
        "content" => $slideshow
        
        
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('index.html', $relations);

?>
