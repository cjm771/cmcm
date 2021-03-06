{% extends '_scaffold.php' %}

{% block title %}
Template Gallery - {{template_name}}
{% endblock %}


{% block head %}
<link rel='stylesheet' href='{{dir}}css/splash.css' />
<script>
	$(document).ready(function(){
		$(".frunt-layout-slideshow .frunt-slider").attr({
			"data-ratio" : "[1420,965]",
			"data-fit" : "within",
			"data-bias" : "width"
		});
		$(".frunt-layout-slideshow .frunt-slider").addClass("frunt-responsive");
	});
</script>
{% endblock %}

{% block content %}
<div class='bar grey center'>
		{{template_slideshow}}
		<div class='template_info'>
		<div class='returnLink'>
				<a href='{{dir}}downloads.php#templates'>Return to Template Gallery</a>
			</div>
			<div class='big'>{{template_name}}</div>
				<div class='disclaimer'> <i class='glyphicon glyphicon-exclamation-sign'></i> Disclaimer: Neither creators of this template or CMCM claim ownership of images or other media used in demo templates. If you are the owner of an image and wish it to be taken down, please contact us. Thank you.
			</div>
			{{template_info}}
		
			<div class='buttons'>
			<a class='button big' href='{{dir}}{{template.demo_url}}' target="_blank">View</a>
			<a class='button big' href='{{dir}}downloads?t=t&f={{template.download}}' target="_blank">Download</a>
			</div>
			
		</div>
</div>

{% endblock %}