{% set list = {
	
	'Facebook':'https://www.facebook.com/sharer/sharer.php?u=cmcm.io',
	'Twitter' : 'https://twitter.com/home?status=http://cmcm.io%20-%20A%20simple%20content%20manager%20for%20designers.',
	'Google':'https://plus.google.com/share?url=http://cmcm.io',
	'LinkedIn':'https://www.linkedin.com/shareArticle?mini=true&url=http://cmcm.io&title=CMCM&summary=A%20simple%20content%20manager%20for%20designers.&source=http://cmcm.io',
	'Download':'downloads.php',
	'Donate':'donate.php',
	'About' :'about.php'




	} 
%}

<div class='button_group social'>
	<span class='socicon'><a href='{{list.Twitter}}' target="_blank">a</a></span>
	<span class='socicon'><a href='{{list.Facebook}}' target="_blank">b</a></span>
	<span class='socicon'><a href='{{list.Google}}' target="_blank">c</a></span>
	<span class='socicon'><a href='{{list.LinkedIn}}' target="_blank">j</a></span>
</div>