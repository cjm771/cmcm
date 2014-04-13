<?
	//code samples
	include('chunks/codesamples.php');
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> CMCM - Frunt Docs</title>
		<link rel='stylesheet' href='css/sunburst.css' />
		<link rel='stylesheet' href='../assets/frunt/css/frunt.widgets.css' />
		<link href="http://fonts.googleapis.com/css?family=Gudea:400,700,400italic" rel="stylesheet" type='text/css'>
		<link rel='stylesheet' href='css/style.css' />
		<script src="../assets/js/jquery-2.1.0.min.js"></script>
		<script src="../assets/frunt/js/frunt.js"></script>
		<script src="../assets/frunt/js/frunt.widgets2.js"></script>
		<script src="../assets/frunt/js/lib/twig.js"></script>
		<script src="js/google-prettify/run_prettify.js?skin=sunburst"></script>
		<script src="js/script.js"></script>
	</head>
	<body>
		<div id='container'>
			<div id='top'>
				<? include('chunks/header.php')?>
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
						For a quick intro to setting up CMCM, check out the <a href='/gettingstarted.php'>Getting Started</a> Page.
					</p>
					<p>
						<b>Requirement:</b>
						<ul>
						<li>PHP* v.5.3 with cURL and GD2 libraries**</li>
						</ul>
					</p>
					<p>	
						After <a href='/download.php'>downloading</a> the latest release, simply drag the files into the directory on your server via FTP or protocol of your choice! If you downloaded cmcm with a pre-made template, the admin panel can be accessed via <b><i>[your directory] </i>/cmcm</b>.
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
						
						The <b>data/ folder</b> is where all your data files are stored. These are .json text files, that store your sites' content. You can have multiple 'backends' that can be loaded/saved/backed up within a single CMCM manager. These text files store project text based content such as title, descriptions, media information like captions. It also stores "template" data for new  projects. You can learn more about templates <a href='#'>here</a>.
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
						So if you are familiar with JSON or apis, having this structure should already be usable in its raw stand-alone form. With javascript or php you can read these data files and create a site like how you would make api calls.</p><p>However, we provide <a href='#'>Frunt</a>, a toolkit for you to get a jump-start on accessing/organizing your data...but also creating menus, previewers, modals. All our provided site templates use this tool. But of course it is optional, and can be used to the designer's liking :)  
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
							Hey dude, of course no worries!  All of the Frunt Section is for people who customize their Front end beyond basic HTML and CSS. If that's not you, Just check out some of our premade templates <a href='#'>here</a>.
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
					
					<!--<pre class='prettyprint code code_php'>
					<?=render($var['frunt_layoutGrid']['php'])?>
					</pre>
					<pre class='prettyprint code code_js'>
					<?=render($var['frunt_layoutGrid']['js'])?>
					</pre>-->
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