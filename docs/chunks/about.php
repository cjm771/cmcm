{% extends '_scaffold.php' %}

{% block title %}
About
{% endblock %}


{% block head %}
<link rel='stylesheet' href='css/splash.css' />
<script>
function gen_contact(lhs,rhs,subject,body)
{
	document.write("<A class='button big' HREF=\"mailto");
	document.write(":" + lhs + "@");
	document.write(rhs + "?subject=" + subject +"&body="+encodeURI("\n\n\n"+"My Browser: {{browser}}")+"\">Contact<\/A>"); 
} 
</script>
{% endblock %}

{% block content %}
<div class='bar black center'>
	<div class='bar_wpr'>

	<p>
		<img src='images/graphics_pack/cmcm-icon-white.png'>
	</p>
	<p><b>CMCM</b> is a simple sexy content manager for designers, built with PHP and Javascript, and a database-less storage tactic--A JSON-based content manager. It allows you to drag and drop the files to and from any directory on your server without having to migrate any other dependencies (such as SQL/ database structures). The goal was to make a manager that is transparent, flexible, and transportable. As long as your server has PHP, it should work on any server. </p>
			<p>
		<img src='images/graphics_pack/frunt-icon-white.png'>
	</p>
			<p><b>Frunt</b> is the Front end counterpart to CMCM, included as a subcomponent of CMCM. It is a toolkit for developers and template makers to quickly develop dope boy websites. Frunt includes core access methods to your CMCM files, but also useful widgets like menus, slideshows, scrollers, modals, and more. Consider it the bootstrap for cmcm.</p>
				<hr class='hr'>
			<div class='big'>
				Contact? Contribute? Bugs?
			</div>
			<p>
		Yo get at me! :)
			</p>
		<div class='button_group block'>
			<script>
				gen_contact("chrisishere", "gmail.com", "[cmcm.io] - Inquiry");
			</script>
			<br>
			<a class='button big' href='donate.php'>
				Donate
			</a><br>
			<a class='button big' href='donate.php#share'>
				Share
			</a>
		</div>
		<hr class='hr'>
	<div class='big'>
		What does CMCM stand for?
	</div>
	<p><b>CMCM</b> stands for <b>Chris Malcolm's Content Manager</b>. I'm Chris Malcolm, an architect and developer.  I made this because I wanted a content manager that I would actually use..something flexible,fully customizable, and easily implemented without having to succumb to designing dreaded template plugins or worse. With CMCM you can decide how much control you want to have with your site,-Use just the backend (CMCM) or use Frunt widgets to quickly create a composition. But no matter what HTML/CSS becomes the main way of designing the front end, which developers want and is great for beginner's to get started!</p>
	<p>
		Peep my site: <a href='http://chris-malcolm.com'>chris-malcolm.com</a>
	</p>
	<div class='big'>
		Yo that music tho.
	</div>
	<p>During development I had a teaser page where I would update music pretty frequently. A few people have asked about the tracklist so access to that is below, as well as the playlist.</p>
	<p>
		<a href='#'>Original Teaser Site</a><br>
		<a href='#'>Playlist</a>
	</p>


	</div>
</div>

<div class='bar grey center'>
	<div class='bar_wpr'>
	<div class='big'>
		News and Stuff.
	</div>
		{% if news!= false %}
		{{news}}
		{% else %}
			<p>
			<i>No news available yet.</i>
			</p>
		{% endif %}
	</div>
</div>
{% endblock %}