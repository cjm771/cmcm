{# <---------- IMAGE PREVIEW -----------------> #}
{% if media.type == "image" %}
	{% set mediaSrc = cmcm_url~media.src %}
	{% set mediaOpts = media_opts.image %}
	
{# <---------- SOUND PREVIEW -----------------> #}
{% elseif media.type == "sound" %}

	{% set mediaSrc = media.src %}
	{% set mediaOpts = media_opts.sound %}
		
{# <---------- VIDEO PREVIEW -----------------> #}
{% elseif media.type == "video" %}
	{% set mediaSrc = media.src %}
	{% set mediaOpts = media_opts.video %}
{% endif %}



<a href='{{mediaSrc}}' class='frunt-widget frunt-widget-preview' title='{{media.caption}}'
	
	{# <---- preview vars -----/ #}
	
	data-type='{{media.type}}' 
	data-media-key='{{mediaId}}' 
	data-src='{{mediaSrc}}'  
	{% if media.thumb %}
		data-thumb='{{cmcm_url}}{{media.thumb}}'
	{% endif %}  
	
	{# <---- preview settings -----/ #}
	
	data-use-thumb='{{mediaOpts.autoplay}}'
	data-mode='{{mediaOpts.mode}}'
	data-visual='{{mediaOpts.visual}}'  
	data-autoplay='{{mediaOpts.autoplay}}' 
	
	{# <---- preview responsive settings -----/ #}
	
	data-responsive='{{mediaOpts.responsive}}' 
	data-fit='{{mediaOpts.fit}}' 
	data-real-fit='{{mediaOpts.real_fit}}'
	
	
	
>{{media.src}}
</a>


