{% extends '_scaffold.php' %}

{% block title %}
Get Started
{% endblock %}


{% block head %}
<link rel='stylesheet' href='css/splash.css' />
<!--lazy load-->
<script type='text/javascript' src='js/jquery.lazyload.min.js'></script>
<script type='text/javascript' src='js/getstarted.js'></script>
{% endblock %}

{% block content %}
<div class='bar black center'>
	<div class='bar_wpr' style='max-width: 1200px'>
	
		<div class='big'>
			Lets set this baby up.
		</div>
		<p>
	Drag and drop Install. No databases here...We gotch u.
					</p>
			<hr class='hr'>
			{% include 'features.php' %}
			<hr class='hr'>
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/upload.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Upload CMCM to your server.
					</div>
					<div class='caption'>
					After <a href='{{dir}}downloads.php'>downloading</a> CMCM, unzip it and upload to your server. There's a variety of methods you can use, but we use FTP (Fetch Mac OS X) in this case. If you don't have hosting and don't feel like paying for a shared hosting plan, just google 'free hosting' and they're plenty of options. 
					<p><i>If you downloaded cmcm with a template,remember to move all the files in the zip!</i></p>
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/setup2.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Point your browser to the cmcm directory
					</div>
					<div class='caption'>
					If my site is <b>site.com</b> and I dragged the folder to the root, I would point my browser to <b>site.com/cmcm</b>. This will initiate the setup mode for passwords and all that jazz. You can rename the cmcm folder if you like.
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/login.gif'>
				</div>
				<div class='step_title'>
				3. Login and Enjoy <3
				</div>
				<div class='caption'>
					Blam you're done. Anytime you return to this url,  <b>site.com/cmcm</b>, it will now ask you to login (or just let you in, if you disabled pw protection). Enjoy cmcm.
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>


<div class='bar grey center'>
	<div class='bar_wpr' style='max-width: 1200px'>
	
		<div class='big'>
			Hey Lets Add a Project!
		</div>
					<p>
	Simple, painless, and pretty.
					</p>
		
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/addproject.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Click on Add Projects
					</div>
					<div class='caption'>
					Here you'll find the basic form for a new project and an add media section. We can extend to have more attributes, check the <a href="#templates">templates</a> section out.
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/uploadphotos.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Upload photos
					</div>
					<div class='caption'>
					Images can be dragged into the browser or by clicking the + sign. Hovering over an image, will give you options like adding captions, making it the cover image, or deleting it. Piece of cake :3
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/uploadvideos.gif'>
				</div>
				<div class='step_title'>
				3. Embed External Videos and Sounds*
				</div>
				<div class='caption'>
					What about sounds and videos? We got you covered! Embed youtube, soundcloud, and vimeo links by holding down the + sign and pasting in valid URLs. 
					<p><i>*This feature is in beta and also cURL is needed in order to grab thumbs from external websites, so if your host doesn't have it..you'll have to update the thumbs manually.</i></p>
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>



<div class='bar black center'>
	<div class='bar_wpr' style='max-width: 1200px'>
		<a name='templates'></a>
		<div class='big'>
			Extending your project data with templates.
		</div>
				<p>
	Dude, this aint no blog engine. We're key-value based, meaning you can have as many fields/attributes as you like :3</p>
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/gototemplate.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Man! I want more form fields.
					</div>
					<div class='caption'>
				Yo I got you. That's why we have our edit template's page. Here we can extend (or reduce) the amount of fields that each project or media has. If you're a photographer vs. architect vs. web designer vs. artist....you're gonna want different data attributes for your archive.
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/edittemplate.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Adding extra form fields.
					</div>
					<div class='caption'>
					This ain't your grandma's blog engine. We go beyond description,title, and tags. With cmcm we can add unlimited additional fields..(we call 'em attributes)..by clicking the + sign on the templates page. You can add additional fields to projects, but also to individual media objects.<p> These attributes can have a type parameter, which renders/validates differently depending on your selection</p><p> Here we add a <b>featured</b> boolean (on or off toggle) for projects, and a <b>medium</b> (type of visual, dropdown) to the media. Dope, no?</p>
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/templatechange.gif'>
				</div>
				<div class='step_title'>
				3. The result!
				</div>
				<div class='caption'>
					Yo peep that project we added before. We now have a 'featured' checkbox, and for each media item, there is now an option to classify if it's a render,collage,or photo.
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>



<div class='bar grey center'>
	<div class='bar_wpr' style='max-width: 1200px'>
	
		<div class='big'>
		Manage your projects.
		</div>
		<p>
		Command control! Manage your projects at a global level. Hide, Sort Delete, Change Attributes..the world (or your site at least) is yours!</p>
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/manageprojects2.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Move, Hide, Delete
					</div>
					<div class='caption'>
				Your main page..aka..manage projects..allows you to move projects, hide, and delete them. You can also switch between grid (visual) and list mode.
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/sortby.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Sort by your attributes.
					</div>
					<div class='caption'>
					Most attribute fields that you have (or extend) can be used to sort your projects. Maybe you want to sort by year, type, scale...i dunno..but you can do it. Here we sort by <b> Year</b>.
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/changebydrop.gif'>
				</div>
				<div class='step_title'>
				3. Change attributes my draggin em'
				</div>
				<div class='caption'>
					Sorting is one thing? But maybe you wanna visually change attributes by dragging them into your now sorted groups? yah dude, possible. Here we  sort by <b>"type of project"</b>. And drag one from <b>"product design"</b> to <b>"installation"</b>. CMCM automatically makes the change.
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>


<div class='bar black center'>
	<div class='bar_wpr' style='max-width: 1200px'>
	
		<div class='big'>
		Multiple Users. Multiple Backend Support.
		</div>
		<p>
		Hey yeah! There's the configuration section! You can edit global data like the title,subtitle, description of your backend..as well as manage users..and load, save, back up different backends !! woohoo!</p>
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/configoverview.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					1. Config away!
					</div>
					
					<div class='caption'>
				Welcome to the config page. the everything else and then some of cmcm. Add users, New backends, Edit Global stuff, etc.
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
						<img class='screenCapture' data-original='{{dir}}images/useredit2.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					2. Multiple Users.
					</div>
					<div class='caption'>
					Dynamic websites are more fun when you have friends <3.
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/changesrc.gif'>
				</div>
				<div class='step_title'>
				3. Multiple Backends
				</div>
				<div class='caption'>
					You wanna blog? You wanna portfolio? You want 25 more backends? go at it..we support it. New, Save As, Load--all available.
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>


<div class='bar grey center'>
	<div class='bar_wpr' style='max-width: 1200px'>
	
		<div class='big'>
		Front end? Meet <img class='frunt-logo' src='{{dir}}images/graphics_pack/frunt-white.png' />.
		</div>
		<p>
		You get the backend now, right? Time to design the front for your wonderful visitors! We provide <a href='docs.php#frunt-setup'>frunt</a>, a <i>boostrapish</i> toolkit of functions and widgets to produce quck slideshows, menus, and other components on the fly. Check out the specific in the <a href='docs.php#frunt-steup'>docs</a>, or start by just using one of our <a href='downloads.php#templates'>web templates</a>.</p>
	
		<p>
		Currently the Frunt SDK is available in <b>PHP</b> and <b>JS</b>. View the <a href='gallery/_widgetsdemo/'>widget demo gallery</a>.
		</p>
		<div class='step_group'>
			<div class='step'>
				<div class='step_img_wpr'>
				<img class='screenCapture' data-original='{{dir}}images/vert2.gif'>
			</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					Vertical
					</div>
					
					<div class='caption'>
					 Use Frunt widgets like menus, enlargement boxes, and scrollers as insertable components alongside your CSS, HTML, and whatever! 
					</div>
				</div>
			</div>
			<div class='step'>
	
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/horiz2.gif'>
				</div>
				<div class='step_txt_wpr'>
					<div class='step_title'>
					Horizontal
					</div>
					<div class='caption'>
						Vertical, Horizontal, Grids! Many layouts available and more to come!
					</div>
				</div>
			</div>
			<div class='step'>
				<div class='step_img_wpr'>
					<img class='screenCapture' data-original='{{dir}}images/full2.gif'>
				</div>
				<div class='step_title'>
				Full Page
				</div>
				<div class='caption'>
				
						Our widget have responsiveness in mind, so enlarging windows and full screen web pages are a breeze.
				</div>
			</div>
		</div> <!--end group -->

	</div>
</div>

<div class='bar black center'>
	<div class='bar_wpr'>
	<div class='big'>
		Show us some Love.
	</div>
	<p>
	If you use CMCM, Give us a shout out some where on your site.  
 Also, if you care to like us on the social circuit..
	</p>
	{% include 'social.php' %}
	</div>
</div>
{% endblock %}