<?php
	
	require_once("../assets/frunt/php/frunt.php");
	
	$frunt = new Frunt("../", "../", "./");
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
			.jump_to.dot{
				border-color: #e7e7e7 !important;
			}
			body{
				white-space: nowrap;
			}
			.php_container,
			.container,
			.pre,
			.cntr{
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
			.frunt-layout-vertical .slide img{
				width: 100%;
				height: auto;
			}
			
		</style>
		<link rel='stylesheet' href='../assets/frunt/css/frunt.widgets.css' />
		
		<script src="../assets/js/jquery-2.1.0.min.js"></script>
		<script src="../assets/frunt/js/frunt.js"></script>
		<script src="../assets/frunt/js/frunt.widgets2.js"></script>
		<script src="../assets/frunt/js/lib/twig.js"></script>
		<script>
			frunt = new Frunt("../", "../", "./", "data.json", {
				/*
				onLoad : function(){
					$(".container").html(JSON.stringify(frunt.data, null, 4));
				},
				*/
				async : false
			});
			console.log('finished init');
			///console.log(frunt.data);
			$(document).ready(function(){
				/*
				//DATA
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.data, null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getData())?>, null, 4));
				*/
				
				/*
				//SETTINGS
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getSettings(null, null, false), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getSettings())?>, null, 4));
				*/
				
				/*
				//INFO
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getInfo(), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getInfo())?>, null, 4));
				*/
				
				/*
				//projects
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getProjects(), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getProjects())?>, null, 4));
				*/
				
				/*
				//project
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getProject('soundcloud-test', 'cleanUrl'), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getProject('soundcloud-test', 'cleanUrl'))?>, null, 4));
				*/
				
				/*
				//templates
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getTemplates('media'), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getTemplates('media'))?>, null, 4));
				*/
				
				/*
				//get  attributes
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getAttributes(['id','title', 'added'], frunt.getProject(0), "asc"), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getAttributes(array('id','title', 'added'), $frunt->getProject(0), "asc"))?>, null, 4));
				*/
				
				/*
				//filter
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.filter( frunt.getProjects(), [
					//['year', 'WITH ANY TAGS', "2018,2014"],
					['title', 'CONTAINS', "project"],
					['title', "CONTAINS", "test"]
					
				]), null, 4));
				*/
				
				/*
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->filter( $frunt->getProjects(), 
				array(
				//	array('year', 'WITH ANY TAGS', "2018,2014"),
					array('title', "CONTAINS", "project"),
					array('title', "CONTAINS", "test")
				)
				
				) )?>, null, 4));
				*/
				
				/*	
				//filter
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.filter( frunt.getProjects(), [
					//['year', 'WITH ANY TAGS', "2018,2014"],
					['year', 'EQUALS', "2001"]
					
				]), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->filter($frunt->getProjects(),array(
					array('year', 'EQUALS', '2001')
				)))?>, null, 4));
				*/
				
				/*
				//groups 
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify( frunt.group(['year', 'type_of_project'], frunt.getProjects()), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->group(array('year','type_of_project'), $frunt->getProjects()))?>, null, 4));
				*/
				/*
				//convert
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.convert(frunt.getProject(0).description, 'html,breaks'), null, 4));
				*/
				
				
				/*
				//get existing values by conditional
				$(".container").html("JAVASCRIPT\n=========\n\n"+JSON.stringify(frunt.getExistingValuesByCond('id', ['id', 'MORE THAN', 3], frunt.getProjects(), "asc"), null, 4));
				$(".php_container").html("PHP\n==========\n\n"+JSON.stringify(<?=json_encode($frunt->getExistingValuesByCond('id', array('id', 'MORE THAN', 3), $frunt->getProjects(), "asc"))?>, null, 4));
				*/
				
				/*
				//init twig
				$(".container").html(frunt.twig({
					data : 'Hi {{there}}',
					params : {
						there : "bob"
					}
				}));
				*/
				
				/*
				//twig foreign
				$(".container").html(frunt.twig({
					location : 'templates/',
					file : 'test.twig',
					async : false,
					params : {
						there : "bob",
						header : "blsadsa"
					}
				}));
				*/
				
				/*
				//horiz
				$(".container").html(frunt.widget('menu.horizontal', frunt.getProjects(), {
					sort_by : ["year","type_of_project"],
					async : false,
					load : function(d){	
					}
					
				}));
				*/
				
				/*
				//simple list
				$(".container").html(frunt.widget('simpleList', frunt.getProject(0), {
					async : false,
					ignore: ['title'],
					default_format : {
						key : function(k){
							return "<b>"+k+"</b>"
						}
					},
					load : function(d){	
					}
					
				}));
				*/
				
				
							
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
			<h2>LAYOUT WIDGETS</h2>
			<h5>Vertical Scroll</h5>
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
		<h5>Horizontal Scroll</h5>
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
			<h5>Slideshow</h5>
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
			<h5>Grid</h5>
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
		<h5>Horizontal menu</h5>
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
		<h5>Vertical menu</h5>
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
		
		<h5>Grid menu</h5>
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
		<h2>Simple list</h2>
			<div class='php_container'>
			<!--<?=$frunt->convert($proj['description'], 'html,breaks')?>-->
			<?=$frunt->widget("simpleList", $frunt->getProject(0), array(
			));
			?>
		</div>
		<div class='cntr container_simpleList'>
			
		</div>
		<h2>Utils</h2>
		<h5>Modal</h5>
		<div class='cntr container_preview' style='text-align: center'>
		</div>
		<h5>Responsive Ratios</h5>
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
		<h5>Responsive Fill or within</h5>
		<div class='cntr container_resp2' style='height: 200px;overflow: hidden; padding: 0px'>
		</div>
		<div class='cntr container_resp3' style='height: 200px;overflow: hidden;'>
		</div>

	</body>
	
</html>