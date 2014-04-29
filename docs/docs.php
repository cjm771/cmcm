<?
	//code samples
	include('chunks/codesamples.php');
	

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

     $frunt = new Frunt("cmcm/", "cmcm/", "./", array(
     	"file" => "data.json" 
     ));
     
     $engine = $frunt->twig("chunks/");

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> CMCM - Frunt Docs</title>
		<link rel='stylesheet' href='css/sunburst.css' />
		<link rel='stylesheet' href='cmcm/assets/frunt/css/frunt.widgets.css' />
		<link href="http://fonts.googleapis.com/css?family=Gudea:400,700,400italic" rel="stylesheet" type='text/css'>
		<link rel='stylesheet' href='css/style.css' />
		<script src="cmcm/assets/js/jquery-2.1.0.min.js"></script>
		<script src="cmcm/assets/frunt/js/frunt.js"></script>
		<script src="cmcm/assets/frunt/js/frunt.widgets.js"></script>
		<script src="cmcm/assets/frunt/js/lib/twig.js"></script>
		<script src="js/google-prettify/run_prettify.js?skin=sunburst"></script>
		<script src="js/docs.js"></script>
	</head>
	<body>
		<div id='container'>
			<div id='top'>
				<?=$engine->render("header.php",array(
					 "current" => basename($_SERVER['PHP_SELF'])
				));?>
			</div>
			<div id='main'>
				<div class='submenu'>
					<? include('chunks/submenu.php')?>
				</div>
				<div class='content'>
					<div class='subheader'> Docs </div>
					<div class='section' data-section="cmcm_setup">
				<a name='cmcm-setup'></a>
					<h2>CMCM / Setup</h2>
					<p>
						For a quick intro to setting up and using CMCM, check out the <a href='getstarted.php'>Getting Started</a> Page.
					</p>
					<p>
						<b>Requirement:</b>
						<ul>
						<li>PHP* v.5.3 with cURL and GD2 libraries**</li>
						</ul>
					</p>
					<p>	
						After <a href='downloads.php'>downloading</a> the latest release, simply drag the files into the directory on your server via FTP or protocol of your choice! If you downloaded cmcm with a pre-made template, the admin panel can be accessed via <b><i>[your directory] </i>/cmcm</b>.
					</p>
					<p>
						<i class='light'>*PHP is only required for editing files in the backend. You may design your site in whatever language of your choice. We provide Frunt, an SDK toolkit available in Javascript and PHP to provide useful utilities to access and manipulate your data.</i>
					</p>
					<p>
						<i class='light'>**cURL is needed for grabbing external Media thumbnails (YT, Vimeo, Soundcloud). If you don't have it, not the end of the world, but its pretty common so you probably have it. GD2 is required for creating shrinked thumbnails. It's also pretty common, so likely your host has it.</i>
					</p>
				</div>
					<div class='section' data-section='cmcm_howitworks'>
					<a name='cmcm-howitworks'></a>
					<h2>How it Works</h2>
					<p>CMCM is database-less, JSON-based content manager. It allows you to drag and drop the files to and from any directory on your server without having to migrate any other dependencies (such as SQL/ database structures). The goal was to make a manager that is transparent, flexible, and transportable. As long as your server has PHP, it should work on any server.
						
					</p>
					<p>
						First lets look at the directory structure of the cmcm folder.
						<ul>
							<li><b>data/</b></li>
							<li><b>media/</b></li>
							<li class='superlight'>assets/ </li>
							<li class='superlight'>index.php</li>
							<li class='superlight'>template.php</li>
							<li class='superlight'>config.php</li>
							<li class='superlight'>project.php</li>
						</ul>
						
					</p>
					<p>
						
						The <b>data/ folder</b> is where all your data files are stored. These are .json text files, that store your sites' content. You can have multiple 'backends' that can be loaded/saved/backed up within a single CMCM manager. These text files store project text based content such as title, descriptions, media information like captions. It also stores "template" data for new  projects. You can learn more about templates <a href='getstarted.php#templates'>here</a>.
					</p>
					<p>
						The <b>media/ folder</b> is where uploaded media (i.e. images, videos, sounds) are stored. The name and location of this folder can be changed in the config page of CMCM.	Currently only images are uploaded to the server, but sounds and videos can be implemented via external media (Youtube, Vimeo, Soundcloud).
					</p>
					<p>
					<i class='light'>*Files and diretories  that are greyed are internal CMCM specific resources. Only data/ and media/ are where site data is stored. If you need to update CMCM, for example, you would only replace the grey files and directories. </i>
					</p>
					</div>
					<div class='section' data-section='cmcm_datastructure'>
					<a name='cmcm-datastructure'></a>
					<h2>Data Structure</h2>
					<p>
					You can open up any JSON data file in your data folder (default is usually data.json) in a text editor and this is what you'll see how CMCM organizes your data..pretty readable no?
					<pre class='prettyprint'>
						<?=render($var['cmcm_datastructure'])?>
					</pre>
					</p>
					<p>
						So if you are familiar with JSON or apis, having this structure should already be usable in its raw stand-alone form. With javascript or php you can read these data files and create a site like how you would make api calls.</p><p>However, we provide <a href='#frunt-setup'>Frunt</a>, a toolkit for you to get a jump-start on accessing/organizing your data...but also creating menus, previewers, modals. All our provided site templates use this tool. But of course it is optional, and can be used to the designer's liking :)  
					</p>
				</div>
					<div class='section' data-section="cmcm_templates">
					<a name='cmcm-templates'></a>
					<h2>CMCM Templates</h2>
					<p>
					One of the most unique aspects of CMCM is the ability for you to <b>extend the attributes of your projects</b> and associated media. In the template section of admin panel, you can add, remove, and edit data attributes. So if you decide that all your projects need a <i>"year"</i> and <i>"scale_of_project"</i> attribute, you can simply add those attributes. Maybe you're a photographer and want to add a <i>"film_type"</i> attribute.  For future projects, this attribute will now be added in the form. Perhaps you want all your media to have a "media_type" dropdown attribute with <i>"drawings", "renders", and "diagrams"</i>. 
					</p>
					<p>
						<b>The benefit is that this allows you to have real data to sort, filter, and group your projects/media</b>. Perhaps you want to sort your homepage by "featured" or "not featured", or have your projects media sorted in groups. With flexible project/media templates, a lot is possible :)
					</p>
					<p>
						When adding an attribute, you have options of how the information is asked for and also saved. It can be required or optional, so that when a project is added or edited, it will be validated in a certian manner.
					</p>
					<p>
						<img src='images/template-add.gif' />
						<div class='caption'>Above shows how one can add an extra attribute to projects, in this case a 'featured' toggle.</div>
					</p>
					<p>
						<b>Current available attribute types:</b>
						<ul>
							<li><b>Bool:</b> <i  class='light'>Value 0 or 1, Renders as checkbox</i></li>
							<li><b>String:</b> <i  class='light'>String value, Renders as textbox</i></li>
							<li><b>Int:</b> <i  class='light'>Integer value, Renders as textbox</i></li>
							<li><b>Timestamp:</b> <i  class='light'>String value, Renders as date picker</i></li>
							<li><b>Text:</b> <i  class='light'>String value, Renders as Multiline text</i></li>
							<li><b>Choice:</b> <i  class='light'>String value, Renders as dropdown</i></li>
						</ul>
					</p>
					<p><br><b class='big'>"Ok so I can add new attributes. But what about my existing projects that don't have them?"</b></p>
					<p>
						Its true, anytime you edit your templates for projects or media, this will create an inconsistency for existing projects.</p><p> Likely, this won't break anything for your frontend (unless you design it to)..but its still nice to have a clean and perfect data structure!  So fortunately, CMCM will also show and allow you to resolve existing projects that are missing or have extra attributes in the templates. This is in the discrepancy section of the template page. WOoHoo.
					</p>
						<p>
						<img src='images/discrep.jpg' />
						<div class='caption'>Above depicts the dicrepency manager for resolving disputes between existing projects and templates.</div>
					</p>
				</div>
					<div class='section' data-section="frunt_setup">
				<a name='frunt-setup'></a>
					<h2>Frunt / Setup</h2>
					<p>
					<b>What is Frunt?</b> Well once you upload your images, get your projects in CMCM just right..we still need to make a website for your visitors right? <b>Frunt is just the front-end toolkit bundled with CMCM, specifically designed to make visualizing your data easier!</b>
					</p>
					<div class='box'>
						<b class='big'>"WHOA, I'm not a Web Developer, can't I just use a provided web template?" </b>
						<p class='big'>
							Hey dude, of course no worries!  All of the Frunt Section is for people who customize their Front end beyond basic HTML and CSS. If that's not you, Just check out some of our premade templates <a href='downloads.php#templates'>here</a>.
						</p>
					</div>
					<p> This toolkit is independent of the backend, and intentionally done so to appeal to a wide variety of designers. If you are super web-savvy,  You can obviously work with the data structure just by bringing it in with AJAX (js) or reading the file with a server side language. But most would want a library to get,filter,group projects by certian parameters, and maybe even have some pre-made widgets to quickly generate menus or slideshows, right? That's where frunt comes in :) 
					</p>
					<p>Currently it is supplied for both javascript and PHP languages so you can decide how you want to grab your data and place it on the page. Chances are you will probably just use either serverside (PHP) or clientside (JS) method, but you can also use both if you like.
					</p>
					<h4>Frunt / JS</h4>
					<p>
						Below is how to initialize a frunt instance in js. Insert this into your HEAD tag or at the end of body. We ask for the cmcm root, cmcm url, and site url which is necessary for widgets and templates to have a reference. for the js SDK, we use ajax to read files, so likely the configuration file is locked. Because of this, we have to also specify what file we want to load. Additionally, we have option of loading it asynchronously (working in the background) and specifying a load callback, or loading it synchronously (async: false).
					</p>
					<p>
						<b>Available options and defaults (JS)</b>:
						<ul>
							<li><b>File:</b> <i  class='light'>String Value, Default: [File specified in main args]</i></li>
							<li><b>show_unpublished:</b> <i  class='light'>Bool value,Default: false</i></li>
							<li><b>show_unpublished_media:</b> <i  class='light'>Bool value, Default: false</i></li>
							<li><b>async:</b> <i  class='light'>Bool value, Default: true</i></li>
							<li><b>load:</b> <i  class='light'>Function (callback) value, Default: function(data){
								return false;
								}
							</i></li>
						</ul>
						</pre>
						</ul>
					</p>
					<pre class='prettyprint lang-html'>
					<?=render($var['frunt_setup']['js'])?>
					</pre>
					<h4>Frunt / PHP</h4>
					<p>
						Below is how to initialize a frunt instance in php. We ask for the cmcm root, cmcm url, and site url which is necessary for widgets and templates to have a reference. In php, we can read the configuration file to get the current data file loaded, but of course you can specifically load a file by specifying it in the options array.
					</p>
					<p>
						<b>Available options and defaults (PHP)</b>:
						<ul>
							<li><b>File:</b> <i  class='light'>String Value, Default: [current loaded data file]</i></li>
							<li><b>show_unpublished:</b> <i  class='light'>Bool value,Default: false</i></li>
							<li><b>show_unpublished_media:</b> <i  class='light'>Bool value, Default: false</i></li>
							<li><b>load_widget_lib:</b> <i  class='light'>Bool value, Default: true</i></li>
						</ul>
						</pre>
						</ul>
					</p>
					<pre class='prettyprint lang-html'>
					<?=render($var['frunt_setup']['php'])?>
					</pre>
					<p>
						WOohoo, now were ready to use frunt. The next sections are the available methods and widgets in frunt, enjoy! From now on we'll likely show code sample snippets in the currently selected SDK. So toggle between PHP and JS by clicking on your desired option in the submenu ------>
					</p>
				</div>
					<div class='section' data-section="frunt_core">
					<a name='frunt-core'></a>
					<h2>Frunt / Core</h2>
					<p>
					<b>Frunt core is the basic set of methods that return your data in its raw or manipulated format.</b> Use these to grab, group, filter your projects and media. All the following methods can be used without the need of additional dependencies, just the frunt library.
					</p>
					<p>
						<i class='light'>Frunt widgets are built off these methods.</i>
					</p>
				</div>
					<div class='section' data-section="frunt_core_get">
					<a name='frunt-core-get'></a>
					<h2>Frunt / Core / Get</h2>
					<p>
						Below are methods for retrieving all or segmented parts of the data file. 
					</p>
					<p id='fruntCoreGet'>

					</p>
					<p>
					<h4>Usage Example | 
						<span class='code code_php'>PHP</span>
						<span class='code code_js'>JS</span>
					</h4>
						<pre class='prettyprint code code_php'>
							<?=render($var['frunt_get']['php'])?>
						</pre>
						<pre class='prettyprint code code_js'>
							<?=render($var['frunt_get']['js'])?>
						</pre>
					</p>
				</div>
					<div class='section' data-section="frunt_core_group">
					<a name='frunt-core-group'></a>
					<h2>Frunt / Core / Group</h2>
					<p>
						Group is a method that takes a single set of projects or media, and then breaks it into subgroups of your desired sort attribute(s). Method will nest groups if multiple attributes are given.
					</p>
					<p>
						<h4>Diagram if grouped by a <i>'year'</i> attribute</h4>
						<div class='diagram'>
							<ul>
								<li>Project 1</li>
								<li>Project 2</li>
								<li>Project 3</li>
							</ul>
							<b class='arrow'style='display: inline-block; vertical-align:middle;margin:0 40px;'><span class='glyphicon glyphicon-arrow-right'></span></b>
							<ul>
								<li>2014</li>
								<ul>
									<li>Project 1</li>
								</ul>
								<li>2012</li>
								<ul>
									<li>Project 2</li>
									<li>Project 3</li>
								</ul>
							</ul>
						</div>
					</p>
					<p id='fruntCoreGroup'>

					</p>
					<p>
					<h4>Usage Example | 
						<span class='code code_php'>PHP</span>
						<span class='code code_js'>JS</span>
					</h4>
						<pre class='prettyprint code code_php'>
							<?=render($var['frunt_group']['php'])?>
						</pre>
						<pre class='prettyprint code code_js'>
							<?=render($var['frunt_group']['js'])?>
						</pre>
					</p>
				</div>
					<div class='section' data-section="frunt_core_filter">
					<a name='frunt-core-filter'></a>
					<h2>Frunt / Core / Filter</h2>
					<p>
						Filter is a method that takes a single set of projects or media, and based on rule(s) given, will remove projects that don't satisfy those rules. A full list of the rules can be seen in method doc below.
					</p>
					<p>
						<h4>Diagram if project set is filtered by  rule <i>'id' LESS THAN 4</i> </h4>
						<div class='diagram'>
							<ul>
								<li>Project 1</li>
								<li>Project 2</li>
								<li>Project 3</li>
								<li>Project 4</li>
								<li>Project 5</li>
								<li>Project 6</li>
							</ul>
							<b class='arrow'style='display: inline-block; vertical-align:middle;margin:0 40px;'><span class='glyphicon glyphicon-arrow-right'></span></b>
							<ul>
								<li>Project 1</li>
								<li>Project 2</li>
								<li>Project 3</li>
							</ul>
						</div>
					</p>
					<p id='fruntCoreFilter'>
					
					</p>
					<p>
					<h4>Usage Example | 
						<span class='code code_php'>PHP</span>
						<span class='code code_js'>JS</span>
					</h4>
						<pre class='prettyprint code code_php'>
							<?=render($var['frunt_filter']['php'])?>
						</pre>
						<pre class='prettyprint code code_js'>
							<?=render($var['frunt_filter']['js'])?>
						</pre>
					</p>
				</div> <!--end section-->
					<div class='section' data-section="frunt_core_convert">
						<a name='frunt-core-convert'></a>
						<h2>Frunt / Core / Convert</h2>
						<p>
							Convert simply takes one thing and converts it to another data type / format.
						</p>
						<p id='fruntCoreConvert'>
						
						</p>
						<p>
						<h4>Usage Example | 
							<span class='code code_php'>PHP</span>
							<span class='code code_js'>JS</span>
						</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_convert']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_convert']['js'])?>
							</pre>
						</p>
					</div> <!--end section-->
					<div class='section' data-section="frunt_core_sort">
						<a name='frunt-core-sort'></a>
						<h2>Frunt / Core / Sort</h2>
						<p>
							Sort data in a variety of ways.
						</p>
						<p id='fruntCoreSort'>
						
						</p>
						<p>
						<h4>Usage Example | 
							<span class='code code_php'>PHP</span>
							<span class='code code_js'>JS</span>
						</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_sort']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_sort']['js'])?>
							</pre>
						</p>
					</div> <!--end section-->
					<div class='section' data-section="frunt_core_twig">
						<a name='frunt-core-twig'></a>
						<h2>Frunt / Core / Twig</h2>
						<p>
							Twig is the Template engine that Frunt uses for it's widgets and can also be used as a means of smartly organizing your front end. Twig was chosen due to its robust features, cross-language libraries (php/js),  and excellent documentation. For more information on twig check out <a href='http://twig.sensiolabs.org/documentation' target="_blank">Twig Documentation</a> homepage. 
						</p>
						<p>
							A template engine basically allows you to seperate out the specific coding language that your using from basic HTML and CSS. That way the layouts are clear,generic, and reusable. Ex.. 'hey {{dude}}' with params dude = 'bob' will equate to 'hey bob'
						</p>
						<p id='fruntCoreTwig'>
						
						</p>
						<p>
						<h4>Usage Example | 
							<span class='code code_php'>PHP</span>
							<span class='code code_js'>JS</span>
						</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_twig']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_twig']['js'])?>
							</pre>
						</p>
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets">
						<a name='frunt-widgets'></a>
						<h2>Frunt / Widgets</h2>
						<p>
							<b>Frunt's widgets are a set of reusable components to help you build out a basic website quickly!</b> These are basically an extension of the core set of functions, where we now implement those functions to create self-contained ready-to-use html blocks.
						</p>
						<p>
							 <b>A widget takes care of the markup (html), the interactive (js) and initial stylings (css) of elements</b>...examples would include menus, slideshows, previewers, scrollers, modals (enlargement windows), and more. After including a widget, you can  of course add additional css to customize the look and feel of the widgets.
						</p>
							<h4>Requirements:</h4>
						<p>
							For frunt widget's you need to include the <b>frunt.widgets.js</b> and <b>frunt.widgets.css</b> files, regardless of the sdk. For js sdk only, you need to make sure your including the <b>twig.js</b> file.
						</p>
						<p>So all dependencies would be the following..
							<ul>
								<li><a href='#frunt-setup'>[Frunt SDK, JS or PHP]</a></li>
								<li><b>jquery</b></li>
								<li><b>twig.js*</b></li>
								<li><b>frunt.widgets.js</b></li>
								<li><b>frunt.widgets.css</b></li>
							</ul>
							<div class='light'><i>*Only required for Frunt JS SDK.</i></div>
						</p>
						<p>
							<pre class='prettyprint'>
								<?=render($var['frunt_widgets'])?>
							</pre>
						</p>
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_basic">
						<a name='frunt-widgets-basic'></a>
						<h2>Frunt / Widgets / Basic</h2>
						<p>
							All widgets are used by calling the same  <i>widget</i> method, just with different params. THis section will go over the widget method, and then the following specific widget sections will simply display the widget name, data type, and available options.
						</p>
						<p id='fruntWidgetsBasic'>
							
						</p>
						<p>
							<h4>Usage Example | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_widgetsBasic']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_widgetsBasic']['js'])?>
							</pre>
						</p>
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_menu">
					<a name='frunt-widgets-menu'></a>
						<h2>Frunt / Widgets / Menus</h2>
						<p>
							The Menu Widget allows you to display your project lists in a variety of ways. For data input, it takes all or a subset of projects.  Actual <i>widget</i> method structure covered <a href='#frunt-widgets-basic'>here</a>
						</p>
						<a name='frunt-menuVertical'></a>
						<h3 class='widgetName'>menu.vertical</h3>
						<p>Vertical Menu (menu.vertical) accepts a <b>set of project objects</b>, below are the available options..</p>
						<ul>
							<li><b>identifier : </b>  <span class='light'>String, Identifier type for url..cleanUrl or id </span> / <i>Default : 'cleanUrl'</i></li>
							<li><b>current : </b> <span class='light'>False or Int/String, Current project identifier to highlight on menu</span> / <i>Default : false</i></li>
							<li><b>url_rewrite : </b> <span class='light'>String, desired link url to project followed by identifier</span> / <i>Default : 'projects/'</i></li>
							<li><b>ascOrDesc : </b> <span class='light'>String, Sort direction</span> / <i>Default : 'desc'</i></li>
							<li><b>extras : </b> <span class='light'>false or Object, Additional links to append to bottom of menu ex. {about : "about.html", contact : "contact.html"} </span> / <i>Default : false</i></li>
							<li><b>extras_location : </b> <span class='light'>String "top" or "bottom", Location of additional links in reference to projects</span> / <i>Default : "bottom"</i></li>
							<li><b>headers : </b> <span class='light'>false or Array-String(2), optional headers above extras and projects..ex ['projects', 'info']</span> / <i>Default : false</i></li>
							<li><b>sort_by : </b> <span class='light'>False, String or Array, Create nested subgroups in menu </span> / <i>Default : false</i></li>
							<li><b>collapse : </b> <span class='light'>True or False, if sort_by, collapse groups or not</span> / <i>Default : true</i></li>
							<li><b>collapse_multiple_fans : </b> <span class='light'>True or False,if sort_by, Allow multiple groups to be open at same time or not</span> / <i>Default : false</i></li>
							<li><b>collapse_current : </b> <span class='light'>False or String/int, subgroup  to open on load..so if sorted by year, one could specify '2014' to show</span> / <i>Default : false</i></li>
						</ul>
						</p>
						<p>
							<h4>Usage Example | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_menuVert']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_menuVert']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
						<?php
						
						echo $frunt->widget("menu.vertical", $frunt->getProjects(), array(
								"sort_by" => "year",
								"current" => "soundcloud-test",
								"extras" => array(
									"about" => "about.php",
									"contact" => "contact.php"
								)
						));
						
						?>
						</div>
						<a name='frunt-menuHorizontal'></a>
						<h3 class='widgetName'>menu.horizontal</h3>
						<p>Horizontal Menu (menu.horizontal) accepts a <b>set of project objects</b>, below are the available options..</p>
						<ul>
							<li><b>identifier : </b>  <span class='light'>String, Identifier type for url..cleanUrl or id </span> / <i>Default : 'cleanUrl'</i></li>
							<li><b>current : </b> <span class='light'>False or Int/String, Current project identifier to highlight on menu</span> / <i>Default : false</i></li>
							<li><b>url_rewrite : </b> <span class='light'>String, desired link url to project followed by identifier</span> / <i>Default : 'projects/'</i></li>
							<li><b>ascOrDesc : </b> <span class='light'>String, Sort direction</span> / <i>Default : 'desc'</i></li>
							<li><b>extras : </b> <span class='light'>false or Object, Additional links to append to bottom of menu ex. {about : "about.html", contact : "contact.html"} </span> / <i>Default : false</i></li>
							<li><b>sort_by : </b> <span class='light'>False, String or Array, Create nested subgroups in menu </span> / <i>Default : false</i></li>
							<li><b>collapse : </b> <span class='light'>True or False, if true, will collapse first column into 'projects'</span> / <i>Default : false</i></li>
						</ul>
						</p>
						<p>
							<h4>Usage Example | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_menuHoriz']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_menuHoriz']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
						<?php
						
						echo $frunt->widget("menu.horizontal", $frunt->getProjects(), array(
								"sort_by" => "year",
								"collapse" => true,
								"current" => "soundcloud-test",
								"extras" => array(
									"about" => "about.php",
									"contact" => "contact.php"
								)
						));
						
						?>
						</div>
						<a name='frunt-menuGrid'></a>
						<h3 class='widgetName'>menu.grid</h3>
						<p>Grid Menu (menu.grid) accepts a <b>set of project objects</b>, below are the available options..</p>
						<ul>
							<li><b>identifier : </b>  <span class='light'>String, Identifier type for url..cleanUrl or id </span> / <i>Default : 'cleanUrl'</i></li>
							<li><b>current : </b> <span class='light'>False or Int/String, Current project identifier to highlight on menu</span> / <i>Default : false</i></li>
							<li><b>url_rewrite : </b> <span class='light'>String, desired link url to project followed by identifier</span> / <i>Default : 'projects/'</i></li>
							<li><b>ascOrDesc : </b> <span class='light'>String, Sort direction</span> / <i>Default : 'desc'</i></li>
							<li><b>extras : </b> <span class='light'>false or Object, Additional links to append to bottom of menu ex. {about : "about.html", contact : "contact.html"} </span> / <i>Default : false</i></li>
							<li><b>sort_by : </b> <span class='light'>False or String, Create subgroups in menu </span> / <i>Default : false</i></li>
							<li><b>no_title : </b> <span class='light'>True or False, No title on thumb </span> / <i>Default : false</i></li>
							<li><b>force_cols : </b> <span class='light'>False or Int, Force a certian amount of thumbs in each column..makes this element responsive, and converts thumbs dimensions to percentages..make sure you use relative padding and margin if you use this feature</span> / <i>Default : false</i></li>
							<!-- "no_title" => false, //dont show title 
			"force_cols" => false, //false or int force break after x, change media_wpr dimension to percentages -->
						</ul>
						</p>
						<p>
							<h4>Usage Example | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_menuGrid']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_menuGrid']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
						<?php
						
						echo $frunt->widget("menu.grid", $frunt->getProjects(), array(
								"current" => "soundcloud-test"
						));
						
						?>
						</div>
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_preview">
					<a name='frunt-widgets-preview'></a>
						<h2>Frunt / Widgets / Preview</h2>
						<p>
							The Preview Widget is a quick way for you to display your media objects. For data input, it takes a media object. Most options are frunt-responsive related (fitting, ratios, relative dimensions to parent). See <a href='#frunt-responsive'>Responsive</a> util doc for more info on this.
						</p>
						<p>
							Actual <i>widget</i> method structure covered <a href='#frunt-widgets-basic'>here</a>
						</p>
						<a name='frunt-preview'></a>
						<h3 class='widgetName'>preview</h3>
						<p>Preview (preview) accepts a <b>media object</b>, below are the available options..</p>
						<p>
							<ul>
								<li><b>mode : </b>  <span class='light'>String, Preview mode...</span> / <i>Default : 'none'</i>
								<p>
									Below are the Possible values for 'mode' option:
									<ul>
										<li><b>none : </b><i class='light'> Just show the thumb or src (if image)</i></li>
										<li><b>modal : </b><i class='light'>  On click of an overlayed icon, modal popup will show enlarged full media</i></li>
										<li><b>modal-noIcon : </b><i class='light'> On click of thumbnail, modal popup will show enlarged full media</i></li>
										<li><b>thumb : </b><i class='light'>  [sound,video only] On clck of an overlayed icon, thumb will be replaced with external media embed</i></li>
										<li><b>direct_embed : </b><i class='light'>  [sound,video only] Directly embed external media</i></li>
									</ul>
								</p>
								</li>
								<li><b>modal_group : </b>  <span class='light'>String, group to put in for modal (Enlarge, shadowbox)...</span> / <i>Default : 'modal'</i></li>
								<li><b>use_thumb : </b>  <span class='light'>[images only] True of False,  use 'thumb' or actual image...</span> / <i>Default : false</i></li>
								<li><b>autoplay : </b>  <span class='light'>[sound,video only] True of False, play on embed</span> / <i>Default : false</i></li>
								
								<li style='margin-top: 10px'><b>responsive : </b> <span class='light'>True or false,  Toggler. See <a href='#frunt-responsive'>Responsive</a> Utils for more info.</span> / <i>Default : true</i></li>
								<li><b>fit : </b> <span class='light'>String, 'within' or 'fill'..  <a href='#frunt-responsive'>Responsive</a> option. </span> / <i>Default : 'fill'</i></li>
								<li><b>real_fit : </b> <span class='light'>[if mode=thumb] String, 'within' or 'fill', extra setting for embed data</span> / <i>Default : 'within'</i></li>
								<li><b>bias : </b> <span class='light'>False or String... <a href='#frunt-responsive'>Responsive</a> option. </span> / <i>Default : false</i></li>
								<li><b>sync_parent : </b> <span class='light'>False or Int... <a href='#frunt-responsive'>Responsive</a> option. </span> / <i>Default : false</i></li>
								<li><b>no_ratio : </b>  <span class='light'>[sound,video only] True or false...we have [1,1] for sounds and [9,6] for videos..use this to disable those default ratios and just use parent</span> /  <i>Default : false</i></li>
				
							</ul>
						</p>
						
							<h4> CSS </h4>
							<p>
								You'll likely want to overwrite the dimensions of the wpr and thumb css depending on your site design. By default the stand alone preview-wpr is inline-block, and auto both width and height. In this case though, we wanted to fill width wise (100% by auto).
							</p>
							<p>
							<pre class='prettyprint'>
/* when using preview, all things are wrapped with this wpr */
/* adjust to your liking ... */
.frunt-preview-wpr{
	display: inline-block;
	width: 100%;
	height: 100%;
}
/* actual media..img or iframe generally..*/
/*dimensions overridden if responsive : true */
/* adjust to your liking ... */
.frunt-preview-wpr .frunt-preview-thumb{
	display : block;
	width: 100%;
	height: auto;
}
							</pre>
						</p>
						<p>
							<h4>Usage Example #1: Image Modal | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_preview']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_preview']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
							<?php
								//grab project with id : 0
								$proj =$frunt->getProject(28);
								
								//display first media object
								echo $frunt->widget("preview", $frunt->getItem($proj['media'], 0), array(
									"mode" => "modal",
									//we use this because our height is not defined so ignore parent height as a constraint
									"bias" => "parent-width", 
									"modal_group" => "preview widget example"
								));
							?>
						</div>
							<p>
							<h4>Usage Example #2: Video Thumb | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_preview2']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_preview2']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
							<?php
								//grab a project
								$proj = $frunt->getProject("soundcloud-test", "cleanUrl");

								//display first media object which is a video
								echo $frunt->widget("preview", $frunt->getItem($proj['media'], 3), array(
									"mode" => "thumb",
									//we use this because our height is not defined so ignore parent height as a constraint
									"bias" => "parent-width", 
									"autoplay" => true
								));
							?>
						</div>
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_layout">
					<a name='frunt-widgets-layout'></a>
						<h2>Frunt / Widgets / Layouts</h2>
						<p>
							The Layout Widgets is a quick way for you to display multiple media items in a scroller,slideshow, or grid. For data input, it takes a set of media objects.
						</p>
						<p>
							Actual <i>widget</i> method structure covered <a href='#frunt-widgets-basic'>here</a>
						</p>
						<p>
						<h4>Events / Methods</h4>
						<p>
							For any layout that has controls (next/prev/etc), you can optionally create custom controls by using the following event ,<b>frunt.slider.change</b>, and method, <b>slideshow_goto</b> in javascript.
						<pre class='prettyprint'>
							<?=render($var['frunt_layoutEvents'])?>
						</pre>	
						</p>
						</p>
						<a name='frunt-layoutHorizontal'></a>
						<h3 class='widgetName'>layout.horizontal</h3>
						<p>
							Horizontal layout (layout.horizontal) accepts a  <b>set of media objects</b>, below are the available options..
						</p>
						<p>
							<ul>
								<li>
								<b>slide_controls : </b>  <span class='light'>false or String, Slide controls display mode...options are 'numbers', 'dots', 'thumbs'</span> / <i>Default : false</i>
								</li>
								<li>
								<b>document_scroll : </b>  <span class='light'>true or false, If slide controls should track document scroll or actual frunt-slider scroll.</span> / <i>Default : false</i>
								</li>
								<li>
									<b>media_opts : </b>  <span class='light'>Set of Objects, options for each media type.. </span> / <i>Default : {image : media opts, sound : media opts, video : media opts}</i>
									<p>
										Each media type can have seperate options.
										<ul>
											<li><b>image :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>sound :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>video :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
										</ul>
									</p>
								</li>
								
								<li><b>no_caption : </b> <span class='light'>True or false, turn on or off caption display. </span> / <i>Default : false</i></li>
				
						</ul>
						</p>
						
						<p>
						<h4> CSS Defaults</h4>
						These are the default dimensions of the slider wrapper and individual slide. Depending on your design, you'll likely want to override one or both of these. In our example, we actually use the responsive option of sync-parent, so the slide dimensions get synced up to the media..the wrapper we leave as is (auto x 450px)
							
						</p>
						<p>
							<pre class='prettyprint'>
									<?=render($var['frunt_horizScroll_css'])?>
							</pre>
						</p>
						<p>
							<h4>Usage | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_horizScroll']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_horizScroll']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?php
								//grab project
								$proj =$frunt->getProject("soundcloud-test", "cleanUrl");
								
								//display  media objects
								echo $frunt->widget("layout.horizontal", $proj['media'], array(
									"slide_controls" => "numbers",
									"media_opts" => array(
										"sound" => array(
											"mode" => "thumb",
											"modal_group" => "horizontal scroll",
											 //take parent-height, ignore parent-width
											"bias" => "parent-height",
											 //sync media dimensions to two parents up (img > wpr > slide)
											"sync_parent" => 2, //
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
											"sync_parent" => 2,
												
										)
									)
								));
							?>
						</div>
						<a name='frunt-layoutVertical'></a>
						<h3 class='widgetName'>layout.vertical</h3>
						<p>
							Vertical layout (layout.vertical) accepts a  <b>set of media objects</b>, below are the available options..
						</p>
						<p>
							<ul>
								<li>
								<b>slide_controls : </b>  <span class='light'>false or String, Slide controls display mode...options are 'numbers', 'dots', 'thumbs'</span> / <i>Default : false</i>
								</li>
								<li>
								<b>document_scroll : </b>  <span class='light'>true or false, If slide controls should track document scroll or actual frunt-slider scroll.</span> / <i>Default : false</i>
								</li>

								<li>
									<b>media_opts : </b>  <span class='light'>Set of Objects, options for each media type.. </span> / <i>Default : {image : media opts, sound : media opts, video : media opts}</i>
									<p>
										Each media type can have seperate options.
										<ul>
											<li><b>image :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>sound :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>video :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
										</ul>
									</p>
								</li>
								
								<li><b>no_caption : </b> <span class='light'>True or false, turn on or off caption display. </span> / <i>Default : false</i></li>
				
						</ul>
						</p>
						
						<p>
						<h4> CSS Defaults</h4>
						These are the default dimensions of the slider wrapper and individual slide. Depending on your design, you'll likely want to override one or both of these. In our example, we actually use the responsive option of sync-parent, so the slide dimensions get synced up to the media..the wrapper we leave as is (auto x 450px)
							
						</p>
						<p>
							<pre class='prettyprint'>
									<?=render($var['frunt_vertScroll_css'])?>
							</pre>
						</p>
						<p>
							<h4>Usage | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_vertScroll']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_vertScroll']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?php
								//grab project
								$proj =$frunt->getProject("soundcloud-test", "cleanUrl");
								
								//display  media objects
								echo $frunt->widget("layout.vertical", $proj['media'], array(
									"slide_controls" => "thumbs",
									"media_opts" => array(
										"sound" => array(
											"mode" => "thumb",
											"modal_group" => "vertical scroll",
											 //take parent-height, ignore parent-width
											"bias" => "parent-width",
											
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
							?>
						</div>
						<a name='frunt-layoutSlideshow'></a>
						<h3 class='widgetName'>layout.slideshow</h3>
						<p>
							Slideshow layout (layout.slideshow) accepts a  <b>set of media objects</b>, below are the available options..
						</p>
						<p>
							<ul>
								<li>
								<b>autoplay : </b>  <span class='light'>false or Int ,Number in milliseconds for delay ex. 5000 = 5 seconds</span> / <i>Default : false</i>
								</li>
								<li>
								<b>transition_effect : </b>  <span class='light'>String, "slide" or "fade" transition effect</span> / <i>Default : "slide"</i>
								</li>
								<li>
								<b>transition_length : </b>  <span class='light'>int, length of transition</span> / <i>Default : 400</i>
								</li>
								<li>
								<b>next_on_click : </b>  <span class='light'>true or false, clicking on slide goes next or previous</span> / <i>Default : true</i>
								</li>
								<li>
								<b>loop_slides : </b>  <span class='light'>true or false, if should return to beginning after last slide</span> / <i>Default : true</i>
								</li>

								<li>
								<b>slide_controls : </b>  <span class='light'>false or String, Slide controls display mode...options are 'numbers', 'dots', 'thumbs'</span> / <i>Default : false</i>
								</li>
								
								<li>
									<b>media_opts : </b>  <span class='light'>Set of Objects, options for each media type.. </span> / <i>Default : {image : media opts, sound : media opts, video : media opts}</i>
									<p>
										Each media type can have seperate options.
										<ul>
											<li><b>image :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>sound :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>video :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
										</ul>
									</p>
								</li>
								
								<li><b>no_caption : </b> <span class='light'>True or false, turn on or off caption display. </span> / <i>Default : false</i></li>
				
						</ul>
						</p>
						
						<p>
						<h4> CSS Defaults</h4>
						These are the default dimensions of the slider wrapper and individual slide. Depending on your design, you'll likely want to override one or both of these. In this example, the slider wrapper we leave as is (auto x 450px)
							
						</p>
						<p>
							<pre class='prettyprint'>
									<?=render($var['frunt_slideshow_css'])?>
							</pre>
						</p>
						<p>
							<h4>Usage | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_slideshow']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_slideshow']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?php
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
							?>
						</div>
						<a name='frunt-layoutGrid'></a>
						<h3 class='widgetName'>layout.grid</h3>
						<p>
							Grid layout (layout.grid) accepts a  <b>set of media objects</b>, below are the available options..
						</p>
						<p>
							<ul>
								<li>
								<b>sort_by : </b>  <span class='light'>false or String, Attribute to create subgroups with</span> / <i>Default : false</i>
								</li>
								<li><b>force_cols : </b> <span class='light'>False or Int, Force a certian amount of thumbs in each column..makes this element responsive, and converts thumbs dimensions to percentages..make sure you use relative padding and margin if you use this feature</span> / <i>Default : false</i></li>
								<li><b>ascOrDesc : </b> <span class='light'>String, Sort direction</span> / <i>Default : 'desc'</i></li>
								<li>
									<b>media_opts : </b>  <span class='light'>Set of Objects, options for each media type.. </span> / <i>Default : {image : media opts, sound : media opts, video : media opts}</i>
									<p>
										Each media type can have seperate options.
										<ul>
											<li><b>image :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>sound :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
											<li><b>video :</b> See <a href='#frunt-preview'>Preview widget</a> options.</li>
										</ul>
									</p>
								</li>
								
								<li><b>no_caption : </b> <span class='light'>True or false, turn on or off caption display. </span> / <i>Default : false</i></li>
				
						</ul>
						</p>
						
						<p>
						<h4> CSS Defaults</h4>
						Editing the thumbnail size happens in CSS. Below are defaults, override to your liking. Check out frunt.widgets.css for more css options to override.
							
						</p>
						<p>
							<pre class='prettyprint'>
									<?=render($var['frunt_layoutGrid_css'])?>
							</pre>
						</p>
						<p>
							<h4>Usage | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_layoutGrid']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_layoutGrid']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?php
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
							?>
						</div>


					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_list">
					<a name='frunt-widgets-list'></a>
						<h2>Frunt / Widgets / List</h2>
						<p>
							The List Widget is a quick way for you to display your attributes of projects and media in text. Mainly a text based widget and you can format keys and values of each, ignore certian elements, etc. 
						</p>
						<p>
							Actual <i>widget</i> method structure covered <a href='#frunt-widgets-basic'>here</a>
						</p>
						</p>
						<a name='frunt-simpleList'></a>
						<h3 class='widgetName'>simpleList</h3>
						<p>
							SimpleList layout (simpleList) accepts a  <b>a single media or project Object</b>, below are the available options..
						</p>
						<p>
							<ul>
								<li>
								<b>template : </b>  <span class='light'>String,Desired Template for each list item</span><br> / <i>Default : &quot;&lt;span class='key'&gt;{{key}}&lt;/span&gt;: &lt;span class='val'&gt;{{val}}&lt;/span&gt;&quot;,</i>
								</li>
								<li>
									<b>default_format : </b>  <span class='light'>Object containing 'key' and 'value' functions for default formatting.. </span> 
									<p>
										<i class='light'>key and value can have Seperate functions.</i>
										<ul>
											<li><b>key : </b><span class='light'>Function,  Desired default formatting for keys..current replaces underscores with spaces</span>  / Default : function(k){return k.replace(/_/gi, " ");}</li>
											<li><b>value : </b><span class='light'>Function,  Desired default formatting for values..</span>  / Default :  function(v){return v;} </li>
										</ul>
									</p>
								</li>
								<li>
									<b>custom_format : </b>  <span class='light'>false or set of Objects containing 'key' and 'value' functions for custom formatting.. / each object key would be name of attribute to apply to. ex. {'title' : {key: func , value : func}, 'year' : {key: func, value: func }} </span> / Default :  false
								</li>
								<li><b>list_type : </b> <span class='light'>What kind of data attributes, 'project' or 'media' </span> / <i>Default : 'project'</i></li>
								<li>
									<b>ignore_default : </b>  <span class='light'>Object with ignore defaults for both project and media objects.. </span> 
									<p>
										Each item has an array of ignored attributes.
										<ul>
											<li><b>project : </b><span class='light'>Array, project attributes ignored</span>  / <i >Default :['id','published','added','cleanUrl','coverImage','media']</i></li>
											<li><b>media : </b><span class='light'>Array, media attributes ignored..</span>  / <i >Default :  ['visible','src','type','thumb'] </i></li>
										</ul>
									</p>
								</li>
								<li><b>showDefaultIgnores : </b> <span class='light'>True or False, show attributes on the default ignore list </span> / <i>Default : false</i></li>
								<li><b>ignore : </b> <span class='light'>False or Array of additional attributes to ignore</span> / <i>Default : false</i></li>
								<li><b>only : </b> <span class='light'>False or Array of attributes that will only be shown.</span> / <i>Default : false</i></li>
				
						</ul>
						</p>
						<p>
							<h4>Usage Example #1 : Description HTML | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_list']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_list']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?
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
							?>
						</div>
						<p>
							<h4>Usage Example #2 : Ignore title,description | 
								<span class='code code_php'>PHP</span>
								<span class='code code_js'>JS</span>
							</h4>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_list2']['php'])?>
							</pre>
							<pre class='prettyprint code code_js'>
								<?=render($var['frunt_list2']['js'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='horizOutputExample box'>
							<?
								echo $frunt->widget("simpleList", $frunt->getProject(0), array(
									"template" => "<span class='key'>[{{key}}]</span> <span class='val'>{{val}}</span>",
									"ignore" => array("title","description")
								));
							?>
						</div>
						
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_modal">
					<a name='frunt-widgets-modal'></a>
						<h2>Frunt / Widgets / Modal</h2>
						<p>
							The Modal Widget is a lightbox/shadowbox/pop-up enlargement window that can work as a link "frunt-modal". It is used by other widgets, but can be used seperately as well. Supports any media object source (sound,video,image).
						</p>
						<p>
							<span class='glyphicon glyphicon-exclamation-sign'></span> Remember to run <b>cmcm.fruntWidget.init()</b> in javascript if modal links are added post-loaded document.
						</p>
						<a name='frunt-modal'></a>
						<h3 class='widgetName'>Modal</h3>
						<h4>Link Method</h4>
						<p>For link-based modal, the link must have the <b>.frunt-modal</b> class. below are <b>available attributes.</b></p>
						<p>
					
						<ul>
							<li><b>href</b>:<i class='light'> Media source (if relative link, make sure to include extra  path to CMCM)</i></li>
							<li><b>title</b>: <i class='light'>Media caption</i></li>
							<li><b>rel</b>: <i class='light'>Modal group</i> <i> / Default : 'modal'</i> </li>
						</ul>
						</p>
						<p>
							<h4>Usage Example #1 : Modal Link 
							</h4>
							<pre class='prettyprint'>
								<?=render($var['frunt_modal'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class=' box'>
							<?
								$proj = $frunt->getProject(-5);
								$media = $frunt->getItem($proj['media'],0);
								echo "<a href='{$frunt->CMCM_URL}{$media['src']}' class='frunt-modal' title='modal demo' rel='modal demo 1'>Modal Demo</a>"
							?>
						</div>
						<p>
							<h4>Usage Example #2 : Modal Groups
							</h4>
							<pre class='prettyprint'>
								<?=render($var['frunt_modal2'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box modalGroupExample'>
							<?
								$proj = $frunt->getProject(28);
								foreach ($proj['media'] as $mediaId=>$media){
								if ($media['type']=="image")	
									echo "<a href='{$frunt->CMCM_URL}{$media['src']}' class='frunt-modal' title='caption!' rel='modal group demo'><img src='{$frunt->CMCM_URL}{$media['thumb']}'></a>";
								}
							?>
						</div>
						
					</div> <!--end section-->
					<div class='section' data-section="frunt_widgets_responsive">
					<a name='frunt-widgets-responsive'></a>
						<h2>Frunt / Widgets / Responsive</h2>
						<p>
							Responsive Widget, <i>frunt-responsive</i> is more of a utility then a widget, used to facilitate intrinsic ratios, cropping (filled) vs. bounding Box (within) situations, and overall scaling of elements when it's dimensions depend on the parent. It uses javascript as a scaling technique, and on document resize is when it will update elements.
						</p>
						<a name='frunt-responsive'></a>
						<h3 class='widgetName'>Responsive</h3>
						<p>Used to have elements scale in certian ways, when parent has unknown or relative dimension(s). This utility can be used by adding the <b>.frunt-responsive</b> class to an HTML element and using the following <b>data attributes</b> as options.
						</p>
						<p>
					
						<ul>
							<li><b>data-ratio</b> : <i class='light'>false or String, width x height ratio ..ex.. "[1,5]" or "[9,6]"..if if element is an image, this will inherit those dimensions.</i> <i> / Default : false or [if image] inherited</i></li>
							<li><b>data-bias</b>: <i class='light'>false or String, bias is used to tell us if we should ignore one dimension of the parent or not, when scaling the element..and then the ratio + the bias will be used to scale. If this method is used, then data-fit, becomes inessential. </i><i> / Default : false</i>
							<p>
							Possible String values for data-bias attribute.
							<ul>
								<li><b>[default]</b> : <i class='light'> If not specified, <u>both the parent width and height</u> will be used as bounding dimensions. (make sure your parents have dimensions in this case)</i></li>
								<li><b>"parent-width"</b> : <i class='light'> <u>Parent width</u> will be used, and parent height is ignored. example is when width is a constraint (vertical scroll)</i></li>
								<li><b>"parent-height"</b> : <i class='light'> <u>Parent height</u> will be used, and parent width is ignored. example is when height is a constraint (horizontal scroll)</i></li>
								<li><b>"height"</b> : <i class='light'>[rare case] <u>Element height</u> is constraint, and width is ignored. Used when scaling method is not related to parent.</i></li>
								<li><b>"width"</b> : <i class='light'>[rare case] <u>Element width</u> is constraint, and height is ignored. Used when scaling method is not related to parent.</i></li>
							</ul>
							</p>
							</li>
							<li><b>data-fit</b>: <i class='light'>false or String, how we should fit the element within the parent.</i><i> / Default : "fill"</i>
							<p>
									Possible String values for data-fit attribute.
								<ul>
								<li><b>"fill"</b> : <i class='light'> Element will fill all of parent constraints, so will likely be cropped and no additional space will be seen.</i></li>
								<li><b>"within"</b> : <i class='light'> Element will not exceed parent constraints, so additional space may be seen.</i></li>
								
								</ul>	
							</p>
							</li>
							<li><b>data-sync-parent</b>: <i class='light'>false or Int, Should we sync (match) the parent(s) dimensions to this element after dimensions are determined. You can match first parent by specifying 1, or multiple by specifying the number of parents (2, 3, 10!). If there is a parent bias (parent-width, parent-height), then that dimension obviously won't change. This could be useful in certian situations where one or several parent dimensions are irrelevant (scrolling sites). </i><i> / Default : false</i></li>
						</ul>
						</p>
						<p>
							<h4>Usage Example #1 : Ratios 
							</h4>
							<p>
							Ratios are useful when you want to constrain dimensions as such, instead of dimensions. With images, this is an easy task solved with css, but other elements such as Divs and iframes, its difficult to do. So our ratio technique makes it the same for all! If this box was in a parent that was scaleable it would always retain it's same ratio. 
							</p>
							<pre class='prettyprint'>
								<?=render($var['frunt_responsive'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box' style='height: 300px'>
							<div id='fruntResponsiveRatioExample' class='frunt-responsive' ratio='[7,3]' data-fit='within'>
								<span class='ratioText'>7:3</span>
							</div>
						</div>
						<p>
							<h4>Usage Example #2 : Fitting "fill" vs. "within"
							</h4>
								<p>
							Fitting is useful when you need to either fill a parent completely (ex. crop, thumbnail, background-look) or use the parent as max constraints (ex. whole image, light box enlarged, no crop). With frunt-responsive you can do either or. In these two examples we'll show the difference. <i class='light'>YES, with images you can achieve this with css more or less, but this is to show just a simple example. Also, this full crop (fill) will work with portrait images too, something css would need an additional class for. </i>
							</p>
							<pre class='prettyprint code code_php'>
								<?=render($var['frunt_responsive2'])?>
							</pre>
						</p>
						<h4>Output </h4>
						<div class='box'>
							<div style='margin-bottom:20px'><b>Within vs. Fill</b></div>
							<div class='exampleParent' style='height: 100px; width: 40%;'>
								<img src="../media/Boston City Flow.jpg" class='frunt-responsive' data-fit='within' >
							</div>
							<div class='exampleParent' style='height: 100px; width: 40%;'>
								<img src="../media/Boston City Flow.jpg" class='frunt-responsive' data-fit='fill' >
							</div>
						</div>
						<p>
							<h4>Usage Example #3 : Data Biases and parent sync
							</h4>
								<p>
									Check out the <a href='#frunt-layoutHorizontal'>horizontal</a> and  <a href='#frunt-layoutVertical'>vertical</a> scroller layout examples. They're perfect to show a situation needing a  parent dimension bias, and syncing.
								</p>
						</p>
					
					</div> <!--end section-->
					<div id='bottom'>
						<div id='footer'>
							2014 &copy; Chris Malcolm
						</div>
					</div>
				</div> <!--end content -->
				
			
					
			</div> <!--end main -->
		
		</div> <!--end  container -->
	</body>
</html>