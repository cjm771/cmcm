{% if just_thumbs==false %}
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

{% else %}
	{% set mediaSrc = media.src %}
	{% if media.type == "image" %}
		{% set mediaSrc = cmcm_url~media.src %}
	{% endif %}
	{% set mediaOpts = {
		'mode' : 'none',
		'use_thumb' : 'true',
		'responsive' : 'false'
	}
	%}
	
{% endif %}

{% spaceless %}
<a href='{{mediaSrc}}' class='frunt-widget frunt-widget-preview' title='{{media.caption}}'
	
	{# <---- preview vars -----/ #}
	
	data-type='{{media.type}}' 
	data-media-key='{{mediaId}}' 
	data-src='{{mediaSrc}}'  
	{% if media.thumb %}
		data-thumb='{{cmcm_url}}{{media.thumb}}'
	{% endif %}  
	
	{# <---- preview settings -----/ #}
	
	data-use-thumb='{{mediaOpts.use_thumb}}'
	data-mode='{{mediaOpts.mode}}'
	data-visual='{{mediaOpts.visual}}'  
	data-autoplay='{{mediaOpts.autoplay}}' 
	
	{# <---- preview responsive settings -----/ #}
	
	{% if mediaOpts.responsive %}
		data-responsive='{{mediaOpts.responsive}}' 
		{% if mediaOpts.no_ratio %}
			data-no-ratio = 'true'
		{% endif %}
		{% if mediaOpts.bias %}
			data-bias='{{mediaOpts.bias}}' 
		{% endif %}
		{% if mediaOpts.sync_parent %}
			data-sync-parent={{mediaOpts.sync_parent}}
		{% endif %}
		data-fit='{{mediaOpts.fit}}' 
		data-real-fit='{{mediaOpts.real_fit}}'
	{% endif %}
	
	
>{{media.src}}
</a>
{% endspaceless %}

