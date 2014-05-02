<!DOCTYPE HTML>
<html>
	<head>
		<title>CMCM - {% block title %}{% endblock %}</title>
		{% include 'meta.php' %}
		{% include 'assets.php' %}
		{% block head %}
		
		{% endblock %}
	</head>
	<body>
		<div id='container'>
			<div id='top'>
				{% include 'header.php' %}
			</div>
			<div id='main'>
				<div class='content'>
				{% block content %}
		
				{% endblock %}
				</div> <!--end content -->
					<div id='bottom'>
						<div id='footer'>
							{% include 'footer.php' %}
						</div>
					</div>
			
					
			</div> <!--end main -->
		
		</div> <!--end  container -->
	</body>
</html>