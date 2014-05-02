<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');

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
	
	 
	//extra variables to grab for this template, for defaults check config.php
	$relations = array_merge($relations, array(
	   "news" => array_splice($news, 0, 5),
	   "content" => $frunt->widget("menu", $frunt->convert($data->projects, 'array'), array(
        	"type" => "grid",
        	"current" => (isset($_GET['id'])) ? $_GET['id'] : basename($_SERVER['PHP_SELF']), //indicate current page..project or file
        	 "sort_by" => "", //sort by..creates headers
        	 "ascOrDesc" => "desc", //show list descending
        	 "collapse" => true,//collapse by header
        	 "no_title" => true, //dont show title 
        	 "force_cols" => 4, //force amount of columns
        ))
	    
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('index.html', $relations);

?>
