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

);

function render($var){
	return htmlentities(preg_replace('/\t/', '    ',$var));
}

?>