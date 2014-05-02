<?php require_once("assets/chunks/authenticate.php")?>
<!DOCTYPE html>
<html>
<head>
	<?php include("assets/chunks/assets.php")?>
	<script>
		
		//initiate
		$(document).ready(function(){
			cmcm.init(function(){
				cmcm.renderProjectsGrid();	
				cmcm.setupSortBy();			
			});
			
		});
	</script>
	
</head>
<body>
	<div id="container">
	
		<?php include("assets/chunks/header.html")?>
	
		<?php include("assets/chunks/navbar.php")?>
			<h3>Projects <span id='index_opts'><span class='glyphicon glyphicon-cog icon' style='font-size:12px;margin-left:3px;'></span></span></h3><br><br>
			<div class='img_grid'>
			</div>
			
		<?php include("assets/chunks/footer.html")?>
	</div>
</body>
</html>