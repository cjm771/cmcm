<?
	//config sets up urls and file roots for frunt. it is used for every template
	require_once('config.php');
	
	//grab cover images as media Objecs
	$coverImages = $frunt->getCoverImages($data->projects);
	
	//slideshow splash
	$slideshow = $frunt->widget("layout.slideshow", $coverImages, array(
		'autoplay'=> 5000,
		'transition_effect' => 'slide',
		'next_on_click' => false,
		'no_caption' => true
	
	));
	
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
        "content" => $slideshow,
        "news" => $news
	));
	//we use templates in the adjacent templates/ folder
	echo $engine->render('index.html', $relations);

?>
