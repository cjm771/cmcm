<?php require_once("chunks/authenticate.php")?>
<!DOCTYPE html>
<html>
<head>
	<?php include("chunks/assets.php")?>
	<script>
			
		//initiate
		$(document).ready(function(){
			cmcm.init(function(){
				//project info
				cmcm.formatProject();
				//image uploader
				cmcm.formatMediaUploader("#proj_tmpl_add");
				
				//save button
				$('#project_save').on("click", function(e){
					if (!$(this).hasClass("disabled")){
						cmcm.validator({
							errorBox : '#project_errorBox',
							successBox : '#project_successBox',
						});	
					}
				}); 
				$(document).on("dragover", function(e){
					if (!$("body").find('.black_bg').length){
						backdrop = $("<div class='black_bg'></div>");
						backdrop.hide();
						dropMsg = $("<div class='drop_msg'>Drop files to upload</div>");
						backdrop.append(dropMsg);
						$("body").append(backdrop);
						backdrop.fadeIn(500);
					}
					dropMsg = $("body").find('.black_bg').find(".drop_msg");
					buffer = {
						x : -60,
						y : 30
					}
					dropMsg.css({
						position: "absolute",
						top : window.event.pageY-$(document).scrollTop()+buffer.y+"px",
						left : window.event.pageX-buffer.x+"px"
					});
					//console.log(dropMsg.css("top")+" "+ dropMsg.css("left"));
				});
				$(document).on("dragleave", function(e){
					 if( window.event.pageX == 0 || window.event.pageY == 0 ) {
						if ($("body").find('.black_bg').length)
							 $("body").find(".black_bg").remove();
					}
				}); 
				$(document).on("drop", function(e){
						if ($("body").find('.black_bg').length)
							 $("body").find(".black_bg").remove();
				}); 
				  	   	
			});
		});
	</script>
	
</head>
<body>
	<div id="container">
	
		<?php include("chunks/header.html")?>
	
		<?php include("chunks/navbar.php")?>
		<h3 id='project_head_wpr'><div id='project_title'></div> <div id='project_panel'><div id='project_save' class='button'>Save</div></div></h3><br><br>
			<div id='project_errorBox' class='errorBox'>
			</div>
			<div id='project_successBox' class='successBox'>
			</div>
			<h5>Project Information</h5><br>
				<table id='proj_info' class='project_table'>
				</table>
				<br><br>
				
			<h5>Media <span id='proj_tmpl_add' class='glyphicon glyphicon-plus icon add'></span></h5><br>
			<div id='media_box'>
				<div id='media_files'>
				</div>				
			</div>

		<?php include("chunks/footer.html")?>
	</div>
</body>
</html>