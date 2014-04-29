<?
    // frunt php
    require_once('cmcm/assets/frunt/php/frunt.php');

     $frunt = new Frunt("cmcm/", "cmcm/", "./", array(
     	"file" => "web_templates.json" 
     ));

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> CMCM - Download</title>
		<link rel='stylesheet' href='css/sunburst.css' />
		<link rel='stylesheet' href='cmcm/assets/frunt/css/frunt.widgets.css' />
		<link href="http://fonts.googleapis.com/css?family=Gudea:400,700,400italic" rel="stylesheet" type='text/css'>
		<link rel='stylesheet' href='css/style.css' />
		<script src="cmcm/assets/js/jquery-2.1.0.min.js"></script>
		<script src="cmcm/assets/frunt/js/frunt.js"></script>
		<script src="cmcm/assets/frunt/js/frunt.widgets.js"></script>
		<script src="cmcm/assets/frunt/js/lib/twig.js"></script>
		<script src="js/google-prettify/run_prettify.js?skin=sunburst"></script>
		<script src="js/script.js"></script>
		<style>
			body,html{
				padding: 0px;
				margin: 0px;
				height: 100%;
			}
			#container{
				width: auto;
				padding: 0px;
				height: 100%;
			}
			#top{
				margin: 0px;
				padding: 10px 30px 20px 30px;
			}
			#main{
				width: 100%;

			}
			.bar{
				width: 100%;
				background: #333;
				padding: 30px 20px;
				color: #c0c0c0;
			}
			.bar.black{
				background: #000;
			}
			.bar.center{
				text-align: center;
			}
			.bar_wpr{
				max-width: 800px;
				margin: 0px auto;
			}
			.content{
				width: auto;
				position: relative;
			}
			#bottom{
				margin: 0px;
				width: 100%;
				background: #fff;
				border: 0;
				padding: 30px;
			}
			.frunt-menu-grid{
				padding: 20px 0px;
			}
			.frunt-menu-grid .thumb_wpr{
				width: 220px;
				height: 170px;
				margin-left: 0px;
				
				/*
-webkit-box-shadow: 3px 3px 8px 0px rgba(50, 50, 50, 0.75);
-moz-box-shadow:    3px 3px 8px 0px rgba(50, 50, 50, 0.75);
box-shadow:         3px 3px 8px 0px rgba(50, 50, 50, 0.75);		
				*/
			}
			
			.frunt-menu-grid .thumb_wpr .media_wpr{
				width: 220px;
				height: 150px;
				display: block;
				padding: 1px;
				border: 1px solid #c0c0c0;

			}
			.frunt-menu-grid .thumb_wpr .title_wpr{
				background: none;
				color: #fff;
			}
			.big{
				font-family: helvetica, arial, sans-serif;
				font-size: 32px;
				padding: 30px 0px 0px 0;
				color: #fff;
			}
			
			.button{
				padding: 10px;
				font-weight: bold;
				font-family: helvetica, arial, sans-serif;
				border: 1px solid #c0c0c0;
				display: inline-block;
				cursor: pointer;
			}
			.button.big{
				font-size: 14px;
				padding: 15px 24px;
				
			}
			.button .light{
				font-size: .8em;
				font-weight: normal;
			}
			.button:hover{
				border-color: #00a3ff;
				background-color: #00a3ff;
			}
			
			
		</style>
	</head>
	<body>
		<div id='container'>
			<div id='top'>
				<? include('chunks/header.php')?>
			</div>
			<div id='main'>
				<div class='content'>
					<div class='bar black center'>
						<div class='bar_wpr'>
							<div class='big'>
								Download the Latest CMCM.
							</div>
								<p>
									Get the latest release of CMCM. To get started, drag the cmcm folder into your server and point your browser to that url.
								</p>
								<p style='padding: 20px 0'>
									<div class='button big'>
										Download CMCM <i class='light'>v1.0</i>
									</div>
								</p>
						</div>
					</div>
					<div class='bar grey center'>
						<div class='bar_wpr'>
						<div class='big'>
							Or Download a Template  to start off.
						</div>
						<p>
						All of our pre-made templates are bundled with CMCM, and designed with Frunt widgets and responsive design so they'll work on your computer, mobile, and tablets. We designed sites in a portfolio format, but of course you can make blogs or any other type of site you wish. Starting with one of these is a great way to jump into CMCM if your not a developer, OR if you are,fiddle around with these to see how Frunt works.
						</p>
						<?
							echo $frunt->widget("menu.grid", $frunt->getProjects(), array(
								"url_rewrite" => "templates/"
							));
							
						?>
						</div>
					</div>
				</div> <!--end content -->
					<div id='bottom'>
						<div id='footer'>
							2014 &copy; Chris Malcolm
						</div>
					</div>
			
					
			</div> <!--end main -->
		
		</div> <!--end  container -->
	</body>
</html>