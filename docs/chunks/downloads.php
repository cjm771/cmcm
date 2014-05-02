{% extends '_scaffold.php' %}

{% block title %}
Downloads
{% endblock %}


{% block head %}
<link rel='stylesheet' href='css/splash.css' />
{% endblock %}

{% block content %}
<div class='bar black center'>
	<div class='bar_wpr'>
		<div class='big'>
			Download the Latest CMCM.
		</div>
			<p>
				Get the latest release of CMCM. To get started, drag the cmcm folder into your server and point your browser to that url.  Please note, that this is just cmcm standalone, no frontend or web template is included. 
			</p>
			<p style='padding: 20px 0'>
				<a class='button big' href='https://github.com/cjm771/cmcm/releases/download/1.0/cmcm.zip'>
					Download CMCM <i class='light'>v1.0</i>
				</a>
			</p>
	</div>
</div>
<div class='bar grey center'>
	<a name='templates'></a>
	<div class='bar_wpr'>
	<div class='big'>
		Or Download a Template  to start off.
	</div>
	<p>
	All of our pre-made templates are bundled with CMCM, and designed with Frunt widgets and responsive design so they'll work on your computer, mobile, and tablets. We designed sites in a portfolio format, but of course you can make blogs or any other type of site you wish. Starting with one of these is a great way to jump into CMCM if your not a developer, OR if you are,fiddle around with these to see how Frunt works.
	</p>
		{{template_gallery}}
	</div>
</div>

{% endblock %}