{% extends '_scaffold.php' %}

{% block title %}
A Simple Content Manager for Designers
{% endblock %}


{% block head %}
<link rel='stylesheet' href='css/splash.css' />
<style>
#top{
	position: absolute;
	-webkit-box-shadow: 0px 10px 18px 0px rgba(50, 50, 50, 0.3);
	-moz-box-shadow:    0px 10px 18px 0px rgba(50, 50, 50, 0.3);
	box-shadow:         0px 10px 18px 0px rgba(50, 50, 50, 0.3);
}
#main{
	padding-top: 100px;
}
</style>
<link rel='stylesheet' href='css/video-js.min.css' />
<script type='text/javascript' src='js/video.js'> </script>
<script>
 videojs.options.flash.swf = "video/video-js.swf"
$(document).ready(function(){
	var myPlayer = videojs('introVid');
	myPlayer.controls(false);
	myPlayer.on("click", function(){
		if (!myPlayer.paused())
			myPlayer.pause();
		else
			myPlayer.play();
	});
	videojs("introVid").ready(function(){
	  var myPlayer = this;
	
	  // EXAMPLE: Start playing the video.
	  myPlayer.play();
	
	});
});
</script>

{% endblock %}

{% block content %}
<div id='videoSplash' class='bar black center'>
<!--
<iframe width="100%" height="100%" src="http://www.youtube.com/embed/k301LpT0h3o?autoplay=1&color1=0xFF0099&color2=0xFFFFFF&showinfo=0&iv_load_policy=3&controls=0&loop=1&playlist=k301LpT0h3o" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
-->
<!--
<iframe src="//player.vimeo.com/video/93567492?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1&amp;loop=1" class='frunt-responsive' data-ratio='[16,9]' width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

-->
<video id="introVid" class="video-js vjs-default-skin"
  controls preload="auto" width=100% height="100%"
  poster="images/CMCM-BIG-youtube-art.jpg"
  data-setup='{"example_option":true}' autoplay loop>
 <source src="videos/CMCM.mp4" type='video/mp4'/>
</video>
</div>
<div class='bar black center'>
	<div class='bar_wpr'>
	<div class='big'>
		Introducing CMCM</span> with <img class='frunt-logo' src='{{dir}}images/graphics_pack/frunt-white.png' />.
	</div>
	<hr class='hr'>
	<div class='step_group cmcm_frunt_group'>
			<div class='step'>
				<div class='step_img_wpr'>
					<img src='images/graphics_pack/cmcm-icon-white.png' />
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					Your Backend.
					</div>
					<div class='caption'>
					CMCM is your backend, allowing you to manage your work, upload media, and design what attributes you want for your projects. Get a <a href='getstarted.php'>tour</a> now.
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
						<img src='images/graphics_pack/frunt-icon-white.png' />
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					Frontend tools.
					</div>
					<div class='caption'>
					Frunt (included as part of CMCM) is an optional set of tools and widgets available in PHP and JS to help you build out a personal site easier. It includes core functions as well as menus, slideshows, and more. Check out the <a href='gallery/_widgetsdemo/' target="_blank">widgets available</a>, and check out the <a href='docs.php#frunt-setup'>docs</a> for more info. 
					</div>
				</div>
			</div>
			<br><br>
		
	</div>
	</div>
</div>


<div class='bar grey center'>
	
	<div class='bar_wpr'>
		<div class='big'>
		 A Simple JSON based content manager for designers.
		</div>
		<hr class='hr' />
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
					<span class='diagram' id='json_icon'>{...}</span>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Transparent
					</div>
					<div class='caption'>
					CMCM is built on JSON text files, to avoid SQL or other databases.The intention was to have data storage  simple and transparent, where even a non-developer can understand <a href='docs.php#cmcm-datastructure'>how data is being structured</a>.
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
						<span class='diagram glyphicon glyphicon-fullscreen'></span>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Extendible
					</div>
					<div class='caption'>
					A common manager has title,description,tags, and uploads. Built into cmcm is the ability to add additional attributes of your projects and even individual media uploads. 
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<span class='diagram glyphicon glyphicon-export'></span>
				</div>
				<div class='step_title'>
				3. Transportable
				</div>
				<div class='caption'>
					Because CMCM is self contained, you can drag-and-drop to install. And also, move it to other directories or servers without worrying about migrations.
				</div>
			</div>
		</div> <!--end group -->
		<br><br>
	</div>
</div>

<div class='bar black center'>
	<div class='bar_wpr'>
	<div class='big'>
		Features + Requirements.
	</div>
	<hr class='hr'>
		{% include 'features.php' %}
	</div>
</div>
<div class='bar grey center'>
	<div class='bar_wpr'>
	<a name='share'></a>
	<div class='big'>
		Show us some Love.
	</div>
	<p>
If you care to like us on the social circuit..
	</p>
	{% include 'social.php' %}
	</div>
</div>
{% endblock %}