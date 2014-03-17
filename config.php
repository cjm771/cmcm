<?php require_once("chunks/authenticate.php")?>
<!DOCTYPE html>
<html>
<head>
	<?php include("chunks/assets.php")?>
	<script>
			
		//initiate
		$(document).ready(function(){
			cmcm.init(function(){
				cmcm.loadConfig();
			});
			//save button
			$('#config_save').on("click", function(e){
				if (!$(this).hasClass("disabled")){
					cmcm.configValidator();	
				}
			});   
				
		});
	</script>
	
</head>
<body>
	<div id="container">
	
		<?php include("chunks/header.html")?>
	
		<?php include("chunks/navbar.php")?>
		<h3 id='project_head_wpr'>
			<div id='project_title'>Configuration</div> 
			<div id='project_panel'>
				<div id='config_save' class='button'>Save</div>
			</div>
		</h3>
		<br><br>
		
		<div id='config_errorBox' class='errorBox'></div>
		<div id='config_successBox' class='successBox'></div>
		<div class="panel-group" id="accordion">
		   <!--START PANEL-->
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				  General <span class='caret'></span>
				</a>
			  </h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
			  <div class="panel-body">
					<table border=0 class='project_table config_table'>
						<tr>
							<td class='name' style='padding-bottom:20px'>Data Source</td>
							<td class='val'>
							
								<span id='config_changeSrc'>
									<span id='config_changeSrc_button' class='button has_tooltip' title='Click for source options'>
										<span class='src_name' data-config-attr='src' data-type='plain' ></span> <span class='caret'></span>
									</span>
								</span>
							</td>
						</tr>
						<tr>
							<td class='name'>Title</td>
							<td class='val'><input type='text' class='input form-control' data-attr='title'></td>
						</tr>
						<tr>
							<td class='name'>Subtitle</td>
							<td class='val'><input type='text' class='input form-control' data-attr='subtitle'></td>
						</tr>
						<tr>
							<td class='name'>Description</td>
							<td class='val'><textarea class='input form-control' data-attr='description'></textarea></td>
						</tr>
						<tr id='config_sort'>
							<td class='name'>Sort</td>
							
							<td class='val' id='config_sortOptions'>
							
							By: <select class='input' data-attr="sort['by']"></select> Direction: <select class='input' data-attr="sort['direction']"></select>
							<div id='config_sortMode_wpr'>
							Mode: <div id='config_sortMode' style='display:inline-block'></div>
							</div>
							</td>
						</tr>
					</table>
			  </div>
			</div>
		  </div>
		  <!--END PANEL-->
		  <!--START PANEL-->
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				   Media <span class='caret'></span>
				</a>
			  </h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
			  <div class="panel-body">
					<table border=0 class='project_table config_table'>
						<tr >
							<td class='name'>Media Utils</td>
							<td class='val'>
								<span id='config_mediaUtils'>
									<div class='button has_tooltip' title="Click for media utilities" id='config_mediaUtils_button'>Media Utils <span class='caret'></span></div>
								</span>
								<br><br>
							</td>
						</tr>
						<tr>
							<td colspan="2"><div id='config_mediaUtils_content'></div></td>
						</tr>
						<tr>
							<td class='name'>Media Folder</td>
							<td class='val'><input type='text' data-attr='mediaFolder' class='input form-control'></td>
						</tr>
						<tr>
							<td class='name'>Thumbnail</td>
							<td class='val config_thumbWpr'><input type='text' data-attr="thumb['max_width']"  class='input form-control short'> By <input type='text' class='input form-control short'  data-attr="thumb['max_height']" >  <span class='config_crop_wpr'>Crop? <input type='checkbox'  data-attr="thumb['crop']" data-type='bool'> </span></td>
						</tr>
						
					</table>
			  </div>
			</div>
		  </div>
		   <!--END PANEL-->
		  <!--START PANEL-->
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
				  Users  <span class='caret'></span>
				</a>
			  </h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse">
			  <div class="panel-body">
					<table border=0 class='project_table config_table'>
						<tr>
							<td class='name'>Login Required?</td>
							<td class='val'><input type='checkbox' data-config-attr='loginEnabled' data-type='bool'>
							<br><br>
							</td>
							
						</tr>
						<tr>
							
							<td class='name' colspan=2>Users <span id='config_addUser' class='glyphicon glyphicon-plus icon'></span>
							
								<table id='config_userTable' class='cmcm_table'>
									<tr>
										<th>User</th>
										<th>Password</th>
										<th></th>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				  </div>
				</div>
			  </div>
			  <!--END PANEL-->
			   <!--START PANEL-->
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
				  Advanced  <span class='caret'></span>
				</a>
			  </h4>
			</div>
			<div id="collapseFour" class="panel-collapse collapse">
			  <div class="panel-body">
					<table border=0 class='project_table config_table'>
						<tr>
							<td class='name'>Reset setup mode?</td>
							<td class='val'><input type='checkbox' data-config-attr='setupMode' data-type='bool'>
						</tr>
					</table>
				  </div>
				</div>
			  </div>
			  <!--END PANEL-->
		</div>

		<?php include("chunks/footer.html")?>
	</div>
</body>
</html>