{% set list = {
	
	'Intro':'index.php',
	'Getting Started':'getstarted.php',
	'Docs':'docs.php',
	'Download':'downloads.php',
	'Donate':'donate.php',
	'About' :'about.php'




	} 
%}

<div id='header'>
	<span class='title'>
		<img class='cmcm-icon-logo' src='{{dir}}images/graphics_pack/cmcm-icon-black.png'/> CMCM
	</span>
	<span class='subtitle'>
		A dope content manager for designers.
		</span>
	
</div>
<ul id='menu'>
	{% for name,link in list %}
	<li {% if current==link %}class='active'{% endif %}><a href='{{dir}}{{link}}'>{{name}}</a></li>
	{% endfor %}
</ul>