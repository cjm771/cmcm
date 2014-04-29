{% extends '_scaffold.php' %}

{% block title %}
Donate
{% endblock %}


{% block head %}
<link rel='stylesheet' href='css/splash.css' />
{% endblock %}

{% block content %}
<div class='bar black center'>
	<div class='bar_wpr'>
	<div class='big'>
		Donate?
	</div>
	<p>
If you enjoy CMCM and/or Frunt please donate to fund the development of this project. I poured months of work into this project and have always had the intention of giving it away for free, so help a broke dude out.
	</p>
	<div class='button_group'>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="chrisishere@gmail.com">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="item_name" value="cmcm.io">
			<input type="hidden" name="no_note" value="0">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>

	</div>
</div>
<div class='bar grey center'>
	<div class='bar_wpr'>
	<a name='share'></a>
	<div class='big'>
		Show us some Love.
	</div>
	<p>
 Also, if you care to like us on the social circuit..
	</p>
	{% include 'social.php' %}
	</div>
</div>
{% endblock %}