<?php
	
	$root  = "../../";
	$cmcm_url = "../../cmcm/";
	
	require_once($cmcm_url."assets/frunt/php/frunt.php");
	
	$frunt = new Frunt($cmcm_url, $cmcm_url, "./", array(
		"file" => "data.json"
	));
	//$data = $frunt->getData();	
	$data = $frunt->filter($frunt->getprojects(), array(
		array("year", "WITH ANY TAGS", "2014,2012")
	), "all");
	$proj = $frunt->getProject(0);
	$proj2 = $frunt->getProject('soundcloud-test', 'cleanUrl');
	
	
?>
<!DOCTYPE html>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,200italic' rel='stylesheet' type='text/css'>
		<style>
			h2{
				padding: 20px;
				margin: 0px;
			}
			h5{
				margin: 0;
				padding-left: 20px;
				
			}
			h5 a{
				text-decoration: inherit;
				font-weight: normal;
				margin-left: 5px;
				font-size: 10px;
				
				
			}
			.jump_to.dot{
			}
			body{
				white-space: nowrap;
			}
			.php_container,
			.container,
			.pre,
			.cntr,
			.intro{
				display: inline-block;
				width: 45%;
				vertical-align: top;
				padding: 20px;
				background: #e7e7e7;
				margin: 20px;
				word-break: break-word;
				white-space: normal;
				border: 1px solid #c0c0c0;
			}
			.intro{
				width: 95%;
				background: #f4f8ff;
				border-color: #c4c4f9;
				font-size: 12px;
				border-radius: 10px;
			}
			.frunt-layout-vertical .slide img{
				width: 100%;
				height: auto;
			}
			.container_preview img{
				width: 100%;
				height: auto;
			}
			
			.container_resp2 .frunt-preview-wpr,
			.container_resp3 .frunt-preview-wpr{
				height: 200px;
				overflow: hidden;
				padding: 0px;
			}
			.container_resp2 .frunt-preview-wpr img.frunt-responsive{
				position: absolute;
				top: 0;
				left: 0;
				bottom: 0;
				right: 0;
				margin: auto;
				display: block;
			}
			.php_container{
				position: absolute;
				top:0;left:0;right: 0; bottom:0;
				margin: auto;
				width: 700px;
				bottom: -100px;
				overflow: visible; 
				background: none;
				border: none;
				max-height: 500px;
			}
			.php_container .widgetName{
				font-family: 'Source Sans Pro', sans-serif;
				font-weight: 300; 
				text-align: center;
				position: absolute;
				top: -100px;
				bottom: 0px;
				right:0px;
				margin: auto;
				left:0px;
				font-size: 32px;
				color: #000;
			}
			
		</style>
		<link rel='stylesheet' href='<?=$cmcm_url?>assets/frunt/css/frunt.widgets.css' />
		
		<script src="<?=$cmcm_url?>assets/js/jquery-2.1.0.min.js"></script>
		<script src="<?=$cmcm_url?>assets/frunt/js/frunt.js"></script>
		<script src="<?=$cmcm_url?>assets/frunt/js/frunt.widgets.js"></script>
		<script src="<?=$cmcm_url?>assets/frunt/js/lib/twig.js"></script>
		<script>
			frunt = new Frunt("<?=$cmcm_url?>", "<?=$cmcm_url?>", "./", "data.json", {
				async : false
			});
			console.log('finished init');
			///console.log(frunt.data);
			$(document).ready(function(){
				cmcm.fruntWidget.init();
				current = -1;
				timeToWait = 2000;
					function next(){
						$(".php_container").fadeOut();
						if (current!=-1)
							$(".php_container:eq("+current+")").fadeIn();
						if (current==0)
							timeToWait = 5000;
						cmcm.fruntWidget.onResize();
						current++;
						setTimeout(function(){
							next();
						}, timeToWait);
					}
					next();
			});
			
		</script>
	</head>
	<body>

			<!-- LAYOUT VERT -->
			<div class='php_container'>
			<div class='widgetName'>
				layout.vertical
			</div>
			<?=$frunt->widget("layout.vertical", $proj2['media'], array(
				"slide_controls" => "dots",
				"media_opts" => array(
					"image" => array(
						"mode" => "modal",
						"sync_parent" => 2,
						"fit" => "within",
						"bias" => "parent-width"
					)
				)
			));
			?>
		</div>

			<!-- LAYOUT HORIZ -->
		<div class='php_container'>
			<div class='widgetName'>
				layout.horizontal
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.horizontal", $proj['media'], array(
				"slide_controls" => "dots",
				"media_opts" => array(
					"image" => array(
						"mode" => "modal",
						"sync_parent" => 2,
						"bias" => "parent-height"
					)
				)
			));
			?>
		</div>


			<!-- LAYOUT SLIDESHOW -->
		<div class='php_container'>
			<div class='widgetName'>
				layout.slideshow
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.slideshow", $proj['media'], array(
				"slide_controls" => "thumbs",
				"transition_effect" => "fade",
				"media_opts" => array(
					"image" => array(
						"mode" => "modal"
					)
				)
			));
			?>
		</div>

	
				<!-- LAYOUT GRID -->
		<div class='php_container'>
			<div class='widgetName'>
				layout.grid
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.grid", $proj2['media'], array(
				"sort_by"=>"type",
				"no_caption" => true,
				"media_opts" => array(
					"image" => array(
						"mode" => "modal-noIcon"
					)
				)
			));
			?>
		</div>

		<!-- MENUS -->
			<div class='php_container'>
				<div class='widgetName'>
				menu.horizontal
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("menu.horizontal", $frunt->getProjects(), array(
				"sort_by"=>array("year", "type_of_project")
			));
			?>
		</div>

		<!-- vertical menu-->
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
				<div class='widgetName'>
				menu.vertical
			</div>
			<?=$frunt->widget("menu.vertical", $frunt->getProjects(), array(
				"sort_by" => array("year", "type_of_project"),
				"collapse" => true
			));
			?>
		</div>

			<div class='php_container'>
				<div class='widgetName'>
				menu.grid
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("menu.grid", $frunt->getProjects(), array(
				"collapse" => true
			));
			?>
		</div>
				<!-- simple list-->
			<div class='php_container'>
				<div class='widgetName'>
				simpleList
			</div>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("simpleList", $frunt->getProject(0), array(
			));
			?>
		</div>
	
</html>