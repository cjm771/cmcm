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
				border-color: #e7e7e7 !important;
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
				
							
					$(".container-1").html(frunt.widget('layout.vertical', frunt.getProject('soundcloud-test', 'cleanUrl').media, {
					async : false,
					slide_controls : "numbers",
					media_opts : {
						video : {
							mode : "modal",
							modal_group : "vert_js"
						},
						image : {
							mode : "modal",
							modal_group : "vert_js"
						},
						sound : {
							modal_group : "vert_js"
						}
					},
					load : function(d){	
						
					}
					}));
				
				
					$(".container_0").html(frunt.widget('layout.horizontal', frunt.getProject('soundcloud-test', 'cleanUrl').media, {
					async : false,
					slide_controls : "numbers",
					media_opts : {
						video : {
							mode : "modal",	
							modal_group : "horiz_js"
						},
						image : {
							mode : "modal",
							modal_group : "horiz_js"
						},
						sound : {
							modal_group : "horiz_js"
						}
					},
					load : function(d){	
						
					}
					}));
				
				
				//slideshow
					$(".container").html(frunt.widget('layout.slideshow', frunt.getProject('soundcloud-test', 'cleanUrl').media, {
					async : false,
					sort_by : "type_of_project",
					slide_controls : "thumbs",
					transition_effect : "fade",
					autoplay: 5000,
					media_opts : {
						video : {
							mode : "modal"	
						},
						image : {
							mode : "modal"
						}
					},
					load : function(d){	
						
					}
					
				}));
					cmcm.fruntWidget.init();
					
				$(".container_2").html(frunt.widget('menu.horizontal', frunt.getProjects(), {
					async : false,
					sort_by : ["year", "type_of_project"]
				}));
				
				
				$(".container_3").html(frunt.widget('layout.grid', frunt.getProject(0).media, {
					async : false,
					sort_by : "type",
					"media_opts" : {
						"image" : {
							"mode" : "modal-noIcon"
						}
					}
				}));
				
				$(".container_vertMenu").html(frunt.widget('menu.vertical', frunt.getProjects(), {
					async : false,
					sort_by : ["year", "type_of_project"],
					collapse_current : "2018",
					current : "soundcloud-test"
				}));
				
				//simple list
				$(".container_simpleList").html(frunt.widget('simpleList', frunt.getProject(0), {
					async : false,
					default_format : {
						key : function(k){
							return "<b>"+k+"</b>"
						}
					},
					load : function(d){	
					}
					
				}));
				
				//grid
				$(".container_menuGrid").html(frunt.widget('menu.grid', frunt.getProjects(), {
					async : false,
					load : function(d){	
					}
					
				}));


				//modal util
				_img = frunt.getItem( frunt.getProject(0).media, 0 );
				_img.src = frunt.CMCM_URL+_img.src;
				link = $("<a></a>");
				link.html(cmcm.fruntWidget.mediaTypes[_img.type].preview(_img));
				link.attr("href", _img.src);
				link.attr("title", _img.caption);
				link.addClass("frunt-modal");
				link.attr("rel", "singleguy");
				$(".container_preview").append(link);
				
				//preview utils
				$(".container_resp2").append(frunt.widget("preview", _img, {
					mode : "modal",
					modal_group : "resp2"
				}));
				
				$(".container_resp3").append(frunt.widget("preview", _img, {
					fit : "within",
					mode : "modal",
					modal_group : "resp3",
					"sync-parent" : 1
				}));

				cmcm.fruntWidget.init();
				
			
			});
			
			
			
			
			
		</script>
	</head>
	<body>
			<h2>LAYOUT WIDGETS DEMO</h2>
			<div class='intro'>
			<p>The following is a demonstration the possible frunt widgets you might wanna use. The left is using the PHP SDK and the right is using the JS SDK, for the purpose of testing, but differences in features are cross-compatible ('fading' vs. sliding...etc).
				
				<p class='disclaimer'> <i class='glyphicon glyphicon-exclamation-sign'></i> Disclaimer: Neither creators of this template or CMCM claim ownership of images or other media used in demo templates. If you are the owner of an image and wish it to be taken down, please contact us. Thank you.
		
			</p>
				<p class='disclaimer'> <i class='glyphicon glyphicon-exclamation-sign'></i> Note: JS Sdk has issues with sorting numeric based keys in some browsers. This is a browser issue to be fixed in future releae. (see Vertical menu Collapsed)
		
			</p>
			</div>
			<h5>Vertical Scroll <a href='<?=$root?>docs.php#frunt-layoutVertical'>Jump to docs</a></h5>
			<!-- LAYOUT VERT -->
			<div class='php_container'>
			<?
				//print_r($)
			?>
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
		<div class='cntr container-1'>
			
		</div>
		<h5>Horizontal Scroll <a href='<?=$root?>docs.php#frunt-layoutHorizontal'>Jump to docs</a></h5>
			<!-- LAYOUT HORIZ -->
		<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.horizontal", $proj['media'], array(
				"slide_controls" => "dots",
				"autoplay" => 5000,
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
		<div class='cntr container_0'>
			
		</div>
			<h5>Slideshow <a href='<?=$root?>docs.php#frunt-layoutSlideshow'>Jump to docs</a></h5>
			<!-- LAYOUT SLIDESHOW -->
		<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.slideshow", $proj['media'], array(
				"slide_controls" => "thumbs",
				"autoplay" => 5000,
				"media_opts" => array(
					"image" => array(
						"mode" => "modal"
					)
				)
			));
			?>
		</div>
		<div class='container'>
			
		</div>
			<h5>Grid <a href='<?=$root?>docs.php#frunt-layoutGrid'>Jump to docs</a></h5>
				<!-- LAYOUT GRID -->
		<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("layout.grid", $proj['media'], array(
				"sort_by"=>"type",
				"media_opts" => array(
					"image" => array(
						"mode" => "modal-noIcon"
					)
				)
			));
			?>
		</div>
		<div class='cntr container_3'>
			
		</div>
		<!-- MENUS -->
		<h2>MENU WIDGETS</h2>
		<h5>Horizontal menu <a href='<?=$root?>docs.php#frunt-menuHorizontal'>Jump to docs</a></h5>
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("menu.horizontal", $frunt->getProjects(), array(
				"sort_by"=>array("year", "type_of_project")
			));
			?>
		</div>
		<div class='cntr container_2'>
			
		</div>
		<!-- vertical menu-->
		<h5>Vertical menu  <a href='<?=$root?>docs.php#frunt-menuVertical'>Jump to docs</a></h5>
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("menu.vertical", $frunt->getProjects(), array(
				"sort_by" => array("year", "type_of_project"),
				"collapse_current" => 2018,
				"current" => "soundcloud-test",
				"collapse" => true
			));
			?>
		</div>
		<div class='cntr container_vertMenu'>
			
		</div>
		
		<h5>Grid menu  <a href='<?=$root?>docs.php#frunt-menuGrid'>Jump to docs</a></h5>
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("menu.grid", $frunt->getProjects(), array(
				"collapse" => true
			));
			?>
		</div>
		<div class='cntr container_menuGrid'>
			
		</div>
				<!-- simple list-->
		<h2>Simple list  <a href='<?=$root?>docs.php#frunt-widgets-list'>Jump to docs</a></h2>
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("simpleList", $frunt->getProject(0), array(
			));
			?>
		</div>
		<div class='cntr container_simpleList'>
			
		</div>
		<h2>Utils</h2>
		<h5>Modal <a href='<?=$root?>docs.php#frunt-widgets-modal'>Jump to docs</a></h5>
		<div class='cntr container_preview' style='text-align: center'>
		</div>
		<h5>Responsive Ratios <a href='<?=$root?>docs.php#frunt-widgets-responsive'>Jump to docs</a></h5>
		<div class='cntr'>
			<div class='frunt-responsive' style='background: #000; color: #fff; padding: 30px; text-align: center; vertical-align:top; position: relative;' data-ratio='[7,3]' data-bias='parent-width'  data-fit='within' >
					<span class='frunt-16 frunt-absCenter' style='width:24px'>7:3</span>
			</div>
		</div>
		<div class='cntr'>
			<div class='frunt-responsive' style='background: #000; color: #fff; padding: 30px; text-align: center; overflow: hidden; vertical-align:top; position: relative' data-ratio='[6,1]' data-bias='parent-width'  data-fit='within' >
				<span class='frunt-16 frunt-absCenter' style='width:24px'>6:1</span>
			</div>
		</div>
		<h5>Responsive Fill or within <a href='<?=$root?>docs.php#frunt-widgets-responsive'>Jump to docs</a></h5>
		<div class='cntr container_resp2'>
		</div>
		<div class='cntr container_resp3'>
		</div>

	</body>
	
</html>