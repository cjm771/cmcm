{% set list = {
	
	'Intro':'index.php',
	'Getting Started':'getstarted.php',
	'Docs':'docs.php',
	'Download':'downloads.php',
	'Donate':'donate.php',
	'About' :'about.php'




	} 
%}


{% for name,link in list %}
<li {% if current==link %}class='active'{% endif %}><a href='{{dir}}{{link}}'>{{name}}</a></li>
{% endfor %}