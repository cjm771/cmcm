	<div id='menu'>
		<a href='index.php'>Manage Projects</a>
		<a href='project.php'>Add Project</a>
		<a href='template.php'>Edit Template</a>
		<a href='config.php'>Configuration</a>
		
		<?php if (isset($_SESSION['username'])){ ?>
		<div id='userPanel'>
			Hi, <span id='my_username'><?=$_SESSION['username']?></span><a href='login.php?a=logout'>Logout</a>
		</div>
		<?php } ?>
	</div>

	