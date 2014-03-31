{% spaceless %}

<div class='frunt-layout frunt-layout-slideshow transition-{{transition_effect}}' data-effect='{{transition_effect}}' data-duration={{transition_length}} 
	{% if next_on_click %} data-move-on-click='true' {% endif %} 
	{% if loop_slides %} data-loop='true' {% endif %}
	{% if autoplay %} data-autoplay={{autoplay}} {% endif %}
>
	{% if media|length > 0 %}
		<div class='frunt-slider'>
		{% set count = 0 %}
		{% for mediaId, _media in media %}
			<div class='slide' data-id={{count}}>
					{% include 'media_preview.php' with {'media' : _media} %}
					{% set count = count + 1 %}
				{#<------------------ handle all captions ---------------->#}
				{% if no_caption == false %} 
					{% if _media.caption %} 
						<div class='caption'>{{_media.caption}}</div>
					 {% endif %}
				{% endif %}
			</div>
		{% endfor %}
		</div>
		{% if slide_controls %}
				<div class='frunt-layout-controls {{slide_controls}}'>
						{#<------------------ numbers ---------------->#}
						{% if slide_controls=="numbers" %}
								<span class='prev'>Prev</span> 
								<span class='next'>Next</span>
								<span class='info'>(<span class='current'>1</span>/{{media|length}})</span>
							{% set count = 0 %}
							{% for mediaId, _media in media %}
								<span class='jump_to dot' data-id={{count}}>{{count+1}}</span>
								{% set count = count + 1 %}
							{% endfor %}
						{% endif %}
						
						{#<------------------ dots ---------------->#}
						{% if slide_controls=="dots" %}
								<span class='prev'>Prev</span> 
								<span class='next'>Next</span>
								<span class='info'>(<span class='current'>1</span>/{{media|length}})</span>
							{% set count = 0 %}
							{% for mediaId, _media in media %}
								<span class='jump_to dot' data-id={{count}}></span>
								{% set count = count + 1 %}
							{% endfor %}
						{% endif %}
						
						{#<------------------ thumbnails ---------------->#}
						{% if slide_controls=="thumbs" %}
							<div class='next_and_prev'>
								<span class='prev'>Prev</span> 
								<span class='next'>Next</span>
								<span class='info'>(<span class='current'>1</span>/{{media|length}})</span>
							</div>
							{% set count = 0 %}
							{% for mediaId, _media in media %}
								<span class='jump_to' data-id={{count}}>
									{% include 'media_preview.php' with {'media' : _media, 'just_thumbs' : true } %}
									{% set count = count + 1 %}
								</span>
							{% endfor %}
							
						{% endif %}
				</div>
		{% endif %}
		{% endif %}
</div>
{% endspaceless %}