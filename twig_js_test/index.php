<!DOCTYPE html>
<html>
	<head>
		<script type='text/javascript' src='../assets/js/jquery-2.1.0.min.js'></script>
		<script type='text/javascript' src='js/twig.js'></script>
		<script>
		var data = "";
		var template = "";
		
		//$(document).ready(function(){
				
						
	
			
				
				$.ajax("../data/data.json", {
					success : function(resp){
						data = resp;
						
						//ajax test

						menu = twig({
						    id: "menu",
						    templateFolder: "../assets/frunt/php/templates/",
						    href: "../assets/frunt/php/templates/menu_basic.php",
						    // for this example we'll block until the template is loaded
						    async: false,
						    cache: false
						    // The default is to load asynchronously, and call the load function 
						    //   when the template is loaded.
			
						     
						});
						
						menuObj = menu.render({
										projects : data.projects,
										site_url : "../horiz/",
										cmcm_url : "../../",
										current : "hi-yoooo",
										url_rewrite : "projects/",
										cmcm_root : "../",
									});
						
						
						
						projInfo = twig({
						    id: "projInfo",
						    templateFolder: "../assets/frunt/php/templates/",
						    href: "../assets/frunt/php/templates/simple_list.php",
						    // for this example we'll block until the template is loaded
						    async: false,
						    cache: false
						    // The default is to load asynchronously, and call the load function 
						    //   when the template is loaded.
			
						     
						});
						
						projInfoObj = projInfo.render({
										items : data.projects["PROJ_4"],
										site_url : "../horiz/",
										cmcm_url : "../../",
										current : "hi-yoooo",
										url_rewrite : "projects/",
										cmcm_root : "../",
									});
						
						
						
						
						
						template = twig({
						    id: "main",
						    templateFolder: "../horiz2/templates/",
						    href: "../horiz2/templates/project.html",
						    // for this example we'll block until the template is loaded
						    async: false,
						    cache: false
						    // The default is to load asynchronously, and call the load function 
						    //   when the template is loaded.
			
						     
						});
						
						content =  template.render({
									title : data.title,
									subtitle : data.subtitle,
									description : data.description,
									project : data.projects["PROJ_4"],
									site_url : "../horiz2/",
									cmcm_url : "../../",
									cmcm_root : "../",
									menu : menuObj,
									projectInfo : projInfoObj
								});
						//content = content.replace("<script", "\x3Cscript");
						//$(".container").html(content);
						//console.log($(".container").find("body").length);
					//	$("body").replaceWith($(content).find("body"));
					//	$("head").replaceWith($(content).find("head"));		
						//$("body").html(content);
						document.write(content);
						document.close();
						
						/*
						//menu test
						var template = twig({
						    id: "posts",
						    href: "../assets/frunt/php/templates/menu_basic.php",
						    // for this example we'll block until the template is loaded
						    async: true,
						
						    // The default is to load asynchronously, and call the load function 
						    //   when the template is loaded.
						
						     load: function(template) { 
							     $(".container").html(template.render({
									title : data.title,
									subtitle : data.subtitle,
									description : data.description,
									site_url : "../horiz2/",
									url_rewrite : "projects/",
									cmcm_url : "../",
									cmcm_root : "../",
									projects : data.projects
								}));
						     }
						});
						*/
						
				
						

						
												/*
						//inline template
						$.ajax("../horiz2/templates/partial/_header.html", {
							success : function(resp){
								template = resp;
								
								var template = twig({
								    data: template
								});
								
								$(".container").html(template.render({
									title : "BLAH BLAH",
									subtitle : "wjwjkdwakjda",
									description : "sdsadasdsasdsadsadas das das"
								}));
								
								
							}
						});
						*/
					
					}
				});
				/*
				var template = twig({
				    data: 'The {{ baked_good }} is a lie.'
				});
				
				console.log(
				    template.render({baked_good: 'cupcake'})
				);
				*/
		//});

		</script>
		<style>
			.container{
				display: none;
			}
			body,html{
				height: 100%;
			}
			.ratioBox_w{
				background: #c0c0c0;
				height: 0;
				padding-bottom: 20%;
				width: 50%;
			}
			.ratioBox_h{
				background: #c0c0c0;
				height: 50%;
				width: 0;
				padding-left: 100%;
			}
		</style>
	</head>
	<body>
	<div class='container'></div>
	</body>
	
</html>