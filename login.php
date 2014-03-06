<?php require_once("chunks/authenticate.php")?>
<!DOCTYPE html>
<html>
<head>
	<?php include("chunks/assets.php")?>
	<style>
		body{
			background: #000;
			color: #fff;
		}
		hr{
			border-color: #fff;
		}
		#container{
			width: 100%;
			height: 100%;
		}
		#header{
			width: 300px;
			text-align: left;
			margin:0px auto;
			padding-bottom: 30px;
			margin-top:200px;
		}
		#header h1{
			float:inherit;
		}
		#loading_wpr{
			display:none;
		}
		#footer{
			position: fixed;
			right: 20px;
			bottom:20px;
			
		}
		.button{
			background: #666;
			display: inline-block;
			float: left;
		}
	</style>
	<script>
			
		//initiate
		$(document).ready(function(){
			cmcm.formatLogin({
				submitButton : "#login_submit",
				successBox : "#login_successBox",
				errorBox : "#login_errorBox"
			});
		});
	</script>
	
</head>
<body>
	<div id="container">
	
		<?php include("chunks/header.html")?>
		
		<div class='loginBox'>
		<div id='login_errorBox' class='errorBox'>
			</div>
			<div id='login_successBox' class='successBox'>
			</div>
			<form role="form">
			  <div class="form-group">
				<input type="email" class="form-control" id="login_username" placeholder="Username">
			  </div>
			  <div class="form-group">
				<input type="password" class="form-control" id="login_pw" placeholder="Password">
			  </div>
			  <div id="login_submit" class='button'>Login</div>
			</form>
		</div>
		
		<?php include("chunks/footer.html")?>
	</div>
</body>
</html>