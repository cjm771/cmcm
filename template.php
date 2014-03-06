<?php require_once("chunks/authenticate.php")?>
<!DOCTYPE html>
<html>
<head>
	<?php include("chunks/assets.php")?>
	<script>
			
		//initiate
		$(document).ready(function(){
			cmcm.init(function(){
				cmcm.renderTemplateEditor();
				cmcm.getDiscrepancies();
			});
			
		});
	</script>
	
</head>
<body>
	<div id="container">
	
		<?php include("chunks/header.html")?>
	
		<?php include("chunks/navbar.php")?>
		<h3>Templates</h3><br><br>
			<h5>Project Template <span id='proj_tmpl_add'><span class='glyphicon glyphicon-plus icon add'></span></span></h5><br>
			
				<table class='cmcm_table' id='projects_tmpl' data-tmpl='project' >
				</table>
			<br><br>
			<h5>Media Template <span id='media_tmpl_add' class='glyphicon glyphicon-plus icon add'></h5><br>
					
				<table class='cmcm_table' id='media_tmpl' data-tmpl='media'>
				</table>
			<br><br>
			<h5>Discrepancies <span id='discrep_stats'></span><span class='glyphicon glyphicon-question-sign hint has_tooltip' title='Discrepancies are attributes that dont sync up with the template and project attributes. These may happen when attributes are added,removed, or edited in the template. Here you can resolve these discrepencies in bulk or individually.'></span></h5><br>
			<div id='discrep_none'></div>
			<table class='cmcm_table' id='discrep'>
			</table>
		<?php include("chunks/footer.html")?>
	</div>
</body>
</html>