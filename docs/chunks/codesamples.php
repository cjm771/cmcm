<?php
$var = array( 
//CODE SNIPPET
"cmcm_datastructure" => <<<'TXT'

{
	//<----YOUR SITE DATA---->//
	"mediaFolder": "media/",
	"title": "My Projects",
	"subtitle": "Bob Somebody",
	"description": "These are my projects",
	"sort": {
		"by": "",
		"direction": "descending",
		"mode": "grid"
	},
	"thumb": {
		"max_width": 200,
		"max_height": 200,
		"crop": 1
	},
	"template": {
		"project": {
			//template object for new project
		},
		"media": {
			//template object for new media
		}
	},
	//<----YOUR PROJECTS--->//
	"projects": {
		//One project
		 "PROJ_0": {
			"id": 0,
			"cleanUrl": "test-project",
			"published": 1,
			"added": "15 February 2014 - 06:28 pm",
			"title": "Test Project",
			"coverImage": "blahblah",
			"description": "This is a Description",
			"media": {
				//An image
				"blahblah": {
					"src": "media/image-005.jpg",
					"visible": 1,
					"type": "image",
					"caption": "This is image 1",
					"thumb": "media/thumbnail/(5)image-005.jpg"
				}
			}
		},
		//Another project :)
		"PROJ_1": {
			"id": 1,
			"cleanUrl": "another-project",
			"published": 1,
			"added": "13 February 2014 - 07:30 pm",
			"title": "Another Project",
			"coverImage": "c3Bpcm4tMDEuanBn",
			"description": "blammo",
			"media": {
				//An image
				"c3Bpcm4tMDEuanBn": {
					"src": "media/spirn-01.jpg",
					"visible": "1",
					"type": "image",
					"caption": "",
					"thumb": "media/thumbnail/(5)spirn-01.jpg"
				},
				//Another image
				"c3Bpcm4tMTQuanBn": {
					"src": "media/spirn-14.jpg",
					"visible": "1",
					"type": "image",
					"caption": "",
					"thumb": "media/thumbnail/(5)spirn-14.jpg"
				}
			}
		}
	}
}
TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_setup" => array(
"php" => <<<'TXT'

<?php
	// frunt php
	require_once('cmcm/assets/frunt/php/frunt.php');
	
	/*******************
	 * 
	 * new frunt instance!
	 * args are as follows:
	 * 
	 * CMCM_ROOT: cmcm directory, relative or abs
	 * CMCM_URL: cmcm url, relative or abs
	 * SITE_URL: site url, relative or abs
	 * OPTS: object of additional options 
	 *
	 *******************/

	 $frunt = new Frunt("cmcm/", "cmcm/", "./", array());
?>

TXT
,
"js" => <<<'TXT'

<!-- frunt js -->
<script src="cmcm/assets/frunt/js/frunt.js"></script>
<!-- twig js (opts, if using widgets) -->
<script src="cmcm/assets/frunt/js/lib/twig.js"></script>

<script>
/*******************
 * 
 * new frunt instance!
 * args are as follows:
 * 
 * CMCM_ROOT: cmcm directory, relative or abs
 * CMCM_URL: cmcm url, relative or abs
 * SITE_URL: site url, relative or abs
 * DATA FILE: filename
 * OPTS: object of additional options 
 *
 *******************/

	frunt  = new frunt("cmcm/", "cmcm/", "./", "data.json", {
		async : false
	});
</script>

TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"fruntwidgets_setup" => array(
"php" => <<<'TXT'

<?php

	
?>

TXT
,
"js" => <<<'TXT'

<!-- (any) jquery --> 
<script src="cmcm/assets/js/jquery-2.1.0.min.js"></script>

<!-- frunt js -->
<script src="cmcm/assets/frunt/js/frunt.js"></script>
<!-- twig js (opts, if using widgets) -->
<script src="cmcm/assets/frunt/js/lib/twig.js"></script>

<!-- frunt widgets js (opt) -->	
<script src="cmcm/assets/frunt/js/frunt.widgets2.js"></script>
<!-- frunt widgets css (opt) -->
<link rel="stylesheet" type="text/css" href='cmcm/assets/frunt/css/frunt.widgets.css' />


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_layoutGrid" => array(
"php" => <<<'TXT'

$HTML =  $frunt->widget("layout.grid", $proj['media'], array(
	"sort_by"=>"type",
	"media_opts" => array(
		"image" => array(
			"mode" => "modal-noIcon"
		)
	)
));
TXT
,
"js" => <<<'TXT'

frunt.widget("layout.grid", proj.media, {
	sort_by : "type",
	media_opts : {
		image : {
			mode : "modal-noIcon"
		}
	},
	load : function(HTML){
		//do something with it
	}
});
TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_get" => array(
"php" => <<<'TXT'

//get all projects
$projs =  $frunt->getProjects();
//display their titles
foreach ($projs as $projID=>$proj){
	$HTML .= "<span>".$proj['title']."</span>, ";
}
echo $HTML;

///get project with id : 1
$proj =  $frunt->getProject(1);
///OR get project with by cleanUrl
$proj =  $frunt->getProject("my-awesome-project", 'cleanUrl');

//display it's title
echo $proj['title'];

TXT
,
"js" => <<<'TXT'

//get all projects
projs =  frunt.getProjects();
//display their titles
for (i in projs){
	$("#container").append("<span>"+projs[i].title+", </span>");
}

///get project with id : 1
proj =  frunt.getProject(1);
///OR get project with by cleanUrl
proj =  frunt.getProject("my-awesome-project", 'cleanUrl');

//display it's title
$("#container").append("<span>"+proj.title+"</span>");


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_group" => array(
"php" => <<<'TXT'

//get all projects
$projs =  $frunt->getprojects();

//group by 'year'
$group = $frunt->group('year', $projs);
//OR multi-group by 'year' and then 'type_of_project'
$group = $frunt->group(array('year', 'type_of_project'), $projs);

TXT
,
"js" => <<<'TXT'

//get all projects
projs =  frunt.getprojects();

//group by 'year'
group = frunt.group('year', projs);
//OR multi-group by 'year' and then 'type_of_project'
group = frunt.group(['year', 'type_of_project'], projs);

TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_filter" => array(
"php" => <<<'TXT'

//get all projects
$projs =  $frunt->getprojects();

//SINGLE RULE
//filter  projs where 'year' = 2014
$filteredProjs = $frunt->filter($projs, array('year', 'EQUALS', 2014));

//MULTIPLE RULES
//filter  projs with 3 rules
$filteredProjs = $frunt->filter($projs, array(
	array('year', 'EQUALS', 2014),
	array('description', 'CONTAINS', 'Architecture'),
	array('tags', 'HAS ANY TAGS', 'architecture,design')
));

//CUSTOM RULE
//grab projects that were added within last 10 days
$filteredProjs = $frunt->filter($projs, array('added', 'CUSTOM', 
	function($v){
		//convert 'added' to unix numeric timestamp
		$v = $frunt->convert($v, 'timestamp');
		return (time()-(10*24*3600)<=$v) ? true : false;
	}
));

TXT
,
"js" => <<<'TXT'

//get all projects
projs =  frunt.getprojects();

//SINGLE RULE
//filter  projs where 'year' = 2014
filteredProjs = frunt.filter(projs, ['year', 'EQUALS', 2014]);

//MULTIPLE RULES
//filter  projs with 3 rules
filteredProjs = frunt.filter(projs, [
	['year', 'EQUALS', 2014],
	['description', 'CONTAINS', 'Architecture'],
	['tags', 'HAS ANY TAGS', 'architecture,design']
]);

//CUSTOM RULE
//grab projects that were added within last 10 days
filteredProjs = frunt.filter(projs, ['added', 'CUSTOM', 
	function(v){
		//convert 'added' to unix numeric timestamp
		v = frunt.convert(v, 'timestamp');
		return (time()-(10*24*3600)<=v) ? true : false;
	}
]);


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_convert" => array(
"php" => <<<'TXT'

$proj = $frunt->getProject(0);

//convert description to working html  with \n as breaks
//ex. "This is a &lt;b&gt;Bold &lt;/b&gt; Description!" -------> "This is a <b>Bold </b> Description!"
$proj['description'] = $frunt->convert($proj['description'], 'html,breaks');

//convert added to timestamp 
// ex. "15 February 2014 - 06:28 pm" ------>  1392485280
$proj['added'] = $frunt->convert($proj['added'], 'timestamp');

//convert added to formatted date
// ex. "15 February 2014 - 06:28 pm" ------>  "2/15/14"
$proj['added'] = $frunt->convert($proj['added'], 'date', 'm/d/y');

TXT
,
"js" => <<<'TXT'

proj = frunt.getProject(0);

//convert description to working html with \n as breaks
//ex. "This is a &lt;b&gt;Bold &lt;/b&gt; Description!" -------> "This is a <b>Bold </b> Description!"
proj.description = frunt.convert(proj.description, 'html,breaks');

//convert added to timestamp 
// ex. "15 February 2014 - 06:28 pm" ------>  1392485280
proj.added = frunt.convert(proj.added, 'timestamp');

//OR convert added to formatted date
// ex. "15 February 2014 - 06:28 pm" ------>  "2/15/14"
proj.added = frunt.convert(proj.added, 'date', 'm/d/y');


TXT
)
// END CODE SNIPPET,
,
//CODE SNIPPET
"frunt_sort" => array(
"php" => <<<'TXT'

$var = array(
	'a' => 4,
	'd' => 14,
	'b' => 10,
	'c' => 5
);

//sort by VALUES
//result keys : d, b, c, a
$frunt->sort($var, 'desc');

//sort by KEYS
//result keys : d, c, b, a
$frunt->sort($var, 'desc', 1);

$var = array(
	'a' => array('type' => 'Architecture'),
	'd' => array('type' => 'Installation'),
	'b' => array('type' => 'Design'),
	'c' => array('type' => 'Photography'),
);

//sort by ATTRIBUTE
//result keys : c, d, b, a
$frunt->sort($var, 'desc', 2, 'type');

//sort by CUSTOM
//result keys : a,c,d,b
$frunt->sort($var, 'desc', 3, array('a','c','d','b'));

TXT
,
"js" => <<<'TXT'

_var = {
	a : 4,
	d : 14,
	b : 10,
	c : 5
};

//sort by VALUES
//result keys : d, b, c, a
_new = frunt.sort(_var, 'desc');

//sort by KEYS
//result keys : d, c, b, a
_new = frunt.sort(_var, 'desc', 1);

_var = {
	 a : { type : 'Architecture' },
	 d : { type : 'Installation'},
	 b : { type :  'Design'},
	 c : { type : 'Photography'},
};

//sort by ATTRIBUTE
//result keys : c, d, b, a
_new = frunt.sort(_var, 'desc', 2, 'type');

//sort by CUSTOM
//result keys : a,c,d,b
_new = frunt.sort(_var, 'desc', 3, ['a','c','d','b']);


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_twig" => array(
"php" => <<<'TXT'

//initalize a twig instance
$engine = $frunt->twig();

//assume templates/template.html contains 'hey {{name}}'

//render it! will display 'hey bob'
echo $engine->render('template.html', array(
	'name' => 'bob'
));


TXT
,
"js" => <<<'TXT'


/********************************************
 *
 * EX 1: basic inline template
 *
 * assume templates/template.html contains 'hey {{name}}'
 *
 ********************************************/
 
html = frunt.twig({
	async : false,
	data : "hey {{name}}",
	params : {
		name : "bob"
	}
});
//the following will wait until above is completed.
//displays 'hey bob' in console
console.log(html);


/********************************************
 *
 * EX 2: synchronous external template
 *
 * assume templates/template.html contains 'hey {{name}}'
 *
 ********************************************/

html = frunt.twig({
	async : false,
	location : 'templates/',
	file : 'template.html',
	params : {
		name : "bob"
	}
});
//the following will wait until above is completed.
//displays 'hey bob' in console
console.log(html);


/********************************************
 *
 * EX 3: asynchronous external template
 *
 * assume templates/template.html contains 'hey {{name}}'
 *
 ********************************************/
frunt.twig({
	async : true,
	location : 'templates/',
	file : 'template.html',
	params : {
		name : "bob"
	},
	//this will be called once is done...nothing is interrupted.
	load : function(html){
		//displays 'hey bob' in console
		console.log(html);
	}
});

TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_widgets" => <<<'TXT'

<!--ADDITIONAL RESOURCES TO INCLUDE FOR WIDGETS -->

<!-- (any) jquery..if you didn't include it before --> 
<script src="cmcm/assets/js/jquery-2.1.0.min.js"></script>
<!-- twig js (JS SDK ONlY..if you didn't include it before) -->
<script src="cmcm/assets/frunt/js/lib/twig.js"></script>

<!--- WIDGET SPECIFIC BELOW -->

<!-- frunt.widgets js -->
<script src="cmcm/assets/frunt/js/frunt.widgets.js"></script>
<!-- frunt.widgets css -->
<link rel='stylesheet' href='cmcm/assets/frunt/css/frunt.widgets.css' />

TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_widgetsBasic" => array(
"php" => <<<'TXT'

//grab projects
$projs = $frunt->getProjects();
//display a vertical menu, with various options..
echo $frunt->widget('menu.vertical', $projs, array(
	"collapse" => false,
	"current" => "soundcloud-test",
	"sort_by" => "year"
));

TXT
,
"js" => <<<'TXT'

//grab projects
projs = frunt.getProjects();
//display a vertical menu, with various options..
html = frunt.widget('menu.vertical', projs, {
	async : false,
	collapse : false,
	current : "soundcloud-test",
	sort_by : "year"
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_menuVert" => array(
"php" => <<<'TXT'
	
echo $frunt->widget("menu.vertical", $frunt->getProjects(), array(
		"sort_by" => "year",
		"current" => "soundcloud-test",
		"extras" => array(
			"about" => "about.php",
			"contact" => "contact.php"
		)
));
						
TXT
,
"js" => <<<'TXT'

//display a vertical menu, with various options..
html = frunt.widget('menu.vertical', frunt.getProjects(), {
	async : false,
	sort_by : "year",
	current : "soundcloud-test",
	extras : {
		about : 'about.php',
		contact : 'contact.php'
	}
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_menuHoriz" => array(
"php" => <<<'TXT'
	
//display a horizontal menu, with various options..
echo $frunt->widget("menu.horizontal", $frunt->getProjects(), array(
		"sort_by" => "year",
		"collapse" => true,
		"current" => "soundcloud-test",
		"extras" => array(
			"about" => "about.php",
			"contact" => "contact.php"
		)
));

						
TXT
,
"js" => <<<'TXT'

//display a horizontal menu, with various options..
html = frunt.widget('menu.horizontal', frunt.getProjects(), {
	async : false,
	sort_by : "year",
	collapse : true,
	current : "soundcloud-test",
	extras : {
		about : 'about.php',
		contact : 'contact.php'
	}
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_menuGrid" => array(
"php" => <<<'TXT'
	
//display a grid menu, with various options..
echo $frunt->widget("menu.grid", $frunt->getProjects(), array(
	"current" => "soundcloud-test"
));
						
TXT
,
"js" => <<<'TXT'

//display a grid menu, with various options..
html = frunt.widget('menu.grid', frunt.getProjects(), {
	current : "soundcloud-test"
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_preview" => array(
"php" => <<<'TXT'
	
//grab project with id : 0
$proj = $frunt->getProject(0);

//display first media object which is an image
echo $frunt->widget("preview", $frunt->getItem($proj['media'], 0), array(
	"mode" => "modal",
	//following used because our height is not defined so ignore parent height as a constraint
	"bias" => "parent-width", 
	"modal_group" => "preview widget example"
));
						
TXT
,
"js" => <<<'TXT'

//grab project with id : 0
proj = frunt.getProject(0);

//display first media object which is an image
html =  frunt.widget("preview", frunt.getItem(proj.media, 0), {
	async : false,
	mode : "modal",
	//following used because our height isn't defined so ignore it as a constraint
	bias : "parent-width",
	modal_group : "preview widget example"
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();
		


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_preview2" => array(
"php" => <<<'TXT'
	
//grab a project
$proj = $frunt->getProject("soundcloud-test", "cleanUrl");

//display a media object which is a video
echo $frunt->widget("preview", $frunt->getItem($proj['media'], 3), array(
	"mode" => "thumb",
	//following used because our height is not defined so ignore parent height as a constraint
	"bias" => "parent-width", 
	"autoplay" => true
));
						
TXT
,
"js" => <<<'TXT'

//grab a project 
proj = frunt.getProject("soundcloud-test", "cleanUrl");

//display a media object which is an image
html =  frunt.widget("preview", frunt.getItem(proj.media, 3), {
	async : false,
	mode : "thumb",
	//following used because our height isn't defined so ignore it as a constraint
	bias : "parent-width",
	autoplay : true
});
//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();
		


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_horizScroll" => array(
"php" => <<<'TXT'
	
//grab project
$proj =$frunt->getProject("soundcloud-test", "cleanUrl");

//display  media objects
echo $frunt->widget("layout.horizontal", $proj['media'], array(
	"slide_controls" => "numbers",
	//we use thumb mode for sound and video, and modal mode for images
	"media_opts" => array(
		"sound" => array(
			"mode" => "thumb",
			"modal_group" => "horizontal scroll",
			 //take parent-height, ignore parent-width
			"bias" => "parent-height",
			 //sync media dimensions to two parents up (img > wpr > slide)
			"sync_parent" => 2
		),
		"video" => array(
			"mode" => "thumb",
			"modal_group" => "horizontal scroll",
			"bias" => "parent-height",
			"sync_parent" => 2
		),
		"image" => array(
			"mode" => "modal",
			"modal_group" => "horizontal scroll",
			"bias" => "parent-height",
			"sync_parent" => 2
				
		)
	)
));
						
TXT
,
"js" => <<<'TXT'

//grab project
proj = frunt.getProject("soundcloud-test", "cleanUrl");

//display  media objects
html = frunt.widget("layout.horizontal", proj['media'], {
	slide_controls : "numbers",
	//we use thumb mode for sound and video, and modal mode for images
	media_opts : {
		sound : {
			mode : "thumb",
			modal_group : "horizontal scroll",
			 //take parent-height, ignore parent-width
			bias : "parent-height",
			 //sync media dimensions to two parents up (media > wpr > slide)
			sync_parent : 2
		},
		video : {
			mode : "thumb",
			modal_group : "horizontal scroll",
			bias : "parent-height",
			sync_parent : 2
		},
		image : {
			mode : "modal",
			modal_group : "horizontal scroll",
			bias : "parent-height",
			sync_parent : 2
				
		}
	}
});

//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();

TXT
)
// END CODE SNIPPET
,
//START CODE SNIPPET
"frunt_horizScroll_css" => <<<'TXT'

//Scroller wrapper defaults.. 
//adjust dimensions to your liking
.frunt-layout-horizontal  .frunt-slider{
	width : auto;
	height: 450px;
	
}

//Individual slide defaults..
//adjust dimensions to your liking
.frunt-layout-horizontal .slide{
	width: 100%;
	height: 100%;
}

TXT
// END CODE SNIPPET
,
//START CODE SNIPPET
"frunt_vertScroll_css" => <<<'TXT'

//Scroller wrapper defaults.. 
//adjust dimensions to your liking
 .frunt-layout-vertical  .frunt-slider{
	 width: auto;
	 height: 450px;
}

//Individual slide defaults..
//adjust dimensions to your liking
.frunt-layout-vertical .slide{
	width: 100%;
	height: 100%;
}

TXT
// END CODE SNIPPET
,
//START CODE SNIPPET
"frunt_slideshow_css" => <<<'TXT'

//Scroller wrapper defaults.. 
//adjust dimensions to your liking
 .frunt-layout-slideshow  .frunt-slider{
	 width: auto;
	 height: 450px;
}

//Individual slide defaults..
//adjust dimensions to your liking
.frunt-layout-slideshow .slide{
	width: 100%;
	height: 100%;
}

TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_vertScroll" => array(
"php" => <<<'TXT'
	
//grab project
$proj =$frunt->getProject("soundcloud-test", "cleanUrl");

//display  media objects
echo $frunt->widget("layout.vertical", $proj['media'], array(
		"slide_controls" => "thumbs",
		"media_opts" => array(
			"sound" => array(
				"mode" => "thumb",
				"modal_group" => "vertical scroll",
				 //take parent-width, ignore parent-height
				"bias" => "parent-width",
				 //sync media two parents up (media > wpr > slide)
				"sync_parent" => 2
			),
			"video" => array(
				"mode" => "thumb",
				"modal_group" => "vertical scroll",
				"bias" => "parent-width",
				"sync_parent" => 2
			),
			"image" => array(
				"mode" => "modal-noIcon",
				"modal_group" => "vertical scroll",
				"bias" => "parent-width",
				"sync_parent" => 2
					
			)
		)
));
						
TXT
,
"js" => <<<'TXT'

//grab project
proj = frunt.getProject("soundcloud-test", "cleanUrl");

//display  media objects
html = frunt.widget("layout.vertical", proj['media'], {
	slide_controls : "thumbs",
	//we use thumb mode for sound and video, and modal mode for images
	media_opts : {
		sound : {
			mode : "thumb",
			modal_group : "vertical scroll",
			 //take parent-width, ignore parent-height
			bias : "parent-width",
			 //sync media two parents up (media > wpr > slide)
			sync_parent : 2
		},
		video : {
			mode : "thumb",
			modal_group : "verrtical scroll",
			bias : "parent-width",
			sync_parent : 2
		},
		image : {
			mode : "modal-noIcon",
			modal_group : "vertical scroll",
			bias : "parent-width",
			sync_parent : 2
				
		}
	}
});

//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();

TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_slideshow" => array(
"php" => <<<'TXT'
	
//grab project
$proj =$frunt->getProject("soundcloud-test", "cleanUrl");

//display  media objects
echo $frunt->widget("layout.slideshow", $proj['media'], array(
	"autoplay" => 5000,
	"transition_effect" => "fade",
	"slide_controls" => "dots",
	"media_opts" => array(
		"sound" => array(
			"mode" => "thumb",
			"modal_group" => "ss"
			
		),
		"video" => array(
			"mode" => "thumb",
			"modal_group" => "ss"
		),
		"image" => array(
			"mode" => "modal",
			"modal_group" => "ss"
				
		)
	)
));
						
TXT
,
"js" => <<<'TXT'

//grab project
proj = frunt.getProject("soundcloud-test", "cleanUrl");

//display  media objects
html = frunt.widget("layout.slideshow", proj['media'], {
	autoplay : 5000,
	transition_effect : "fade",
	slide_controls : "dots",
	media_opts : {
		sound : {
			mode : "thumb",
			modal_group : "ss"
		},
		video : {
			mode : "thumb",
			modal_group : "ss"
		},
		image : {
			mode : "modal",
			modal_group : "ss"
				
		}
	}
});

//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();

TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_layoutGrid_css" =>  <<<'TXT'

.frunt-layout-grid .thumb_wpr{
	width: 100px;
	display: inline-block;
	 vertical-align: top;
	height: 100px;
	overflow: hidden;
	padding: 0px;
	margin: 5px;
	position: relative;
	opacity: 1;
}

TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_layoutEvents" =>  <<<'TXT'

//slide change event
$("#myContainer .frunt-layout").on("frunt.slider.change", function(e){
	console.log("slide changed! "+e.index+"!")
});

//goto slide function
index = 3;
cmcm.fruntWidget.slideshow_goto("#myContainer .frunt-layout", index);
							
TXT
// END CODE SNIPPET
,						
//CODE SNIPPET
"frunt_layoutGrid" => array(
"php" => <<<'TXT'
	
//grab project
$proj =$frunt->getProject("soundcloud-test", "cleanUrl");
		
//display  media objects
echo $frunt->widget("layout.grid", $proj['media'], array(
	"sort_by" => "type",
	"media_opts" => array(
		"sound" => array(
			"mode" => "modal",
			"modal_group" => "layout-grid"
			
		),
		"video" => array(
			"mode" => "modal",
			"modal_group" => "layout-grid"
		),
		"image" => array(
			"mode" => "modal",
			"modal_group" => "layout-grid"
				
		)
	)
							
));
						
TXT
,
"js" => <<<'TXT'

//grab project
proj = frunt.getProject("soundcloud-test", "cleanUrl");

//display  media objects
html = frunt.widget("layout.grid", proj['media'], {
	sort_by : "type",
	media_opts : {
		sound : {
			mode : "modal",
			modal_group : "layout-grid"
		},
		video : {
			mode : "modal",
			modal_group : "layout-grid"
		},
		image : {
			mode : "modal",
			modal_group : "layout-grid"
				
		}
	}
});

//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();

TXT
)
// END CODE SNIPPET
,						
//CODE SNIPPET
"frunt_list" => array(
"php" => <<<'TXT'

//convert description value to working html	
echo $frunt->widget("simpleList", $frunt->getProject(0), array(
	"custom_format" => array(
		"description" => array(
			//convert description value to html
			"value" => function($v) use ($frunt){
				return $frunt->convert($v,'html');
			}
		)
	)
));
						
TXT
,
"js" => <<<'TXT'

//convert description value to working html	
html = frunt.widget("simpleList", frunt.getProject(0), {
	custom_format : {
		description : {
			//convert description value to html
			value : function(v){
				return frunt.convert(v,'html');
			}
		}
	}
});

//put it in the dom
$("#container").html(html);

//if this is after page load, make sure to reinit frunt.widgets.js
cmcm.fruntWidget.init();

TXT
)
// END CODE SNIPPET
,						
//CODE SNIPPET
"frunt_list2" => array(
"php" => <<<'TXT'

//ignore some attributes and change template
echo $frunt->widget("simpleList", $frunt->getProject(0), array(
	"template" => "<span class='key'>[{{key}}]</span> <span class='val'>{{val}}</span>",
	"ignore" => array("title","description")
));
						
TXT
,
"js" => <<<'TXT'

//ignore some attributes and change template
html = frunt.widget("simpleList", frunt.getProject(0), {
	template : "<span class='key'>[{{key}}]</span> <span class='val'>{{val}}</span>",
	ignore : ["title","description"]
});

//put it in the dom
$("#container").html(html);


TXT
)
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_modal" => <<<'TXT'

<a href="cmcm/media/image-005.jpg" class="frunt-modal" title="modal demo" rel="modal demo 1">Modal Demo</a>
					
TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_modal2" => <<<'TXT'

<a href="cmcm/media/Pensive Parakeet.jpg" class="frunt-modal" title="caption!" rel="modal group demo">
	<img src="cmcm/media/thumbnail/Pensive Parakeet.jpg">
</a>
<a href="cmcm/media/Costa Rican Frog.jpg" class="frunt-modal" title="caption!" rel="modal group demo">
	<img src="cmcm/media/thumbnail/Costa%20Rican%20Frog.jpg">
</a>
<a href="cmcm/media/Boston City Flow.jpg" class="frunt-modal" title="caption!" rel="modal group demo">
	<img src="cmcm/media/thumbnail/Boston City Flow.jpg">
</a>
									
TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_responsive" => <<<'TXT'

<div class='box' style='height: 300px'>
	<div id='fruntResponsiveRatioExample' class='frunt-responsive' ratio='[7,3]' data-fit='within'>
		<span class='ratioText'>7:3</span>
	</div>
</div>
				
TXT
// END CODE SNIPPET
,
//CODE SNIPPET
"frunt_responsive2" => <<<'TXT'

<!-- WITHIN OPTION -->
<div class='exampleParent' style='height: 100px; width: 40%;'>
	<img src="cmcm/media/Boston City Flow.jpg" class='frunt-responsive' data-fit='within' >
</div>
<!-- FILL OPTION -->
<div class='exampleParent' style='height: 100px; width: 40%;'>
	<img src="cmcm/media/Boston City Flow.jpg" class='frunt-responsive' data-fit='fill' >
</div>	
TXT
// END CODE SNIPPET
				

);

function render($var){
	return htmlentities(preg_replace('/\t/', '    ',$var));
}

?>