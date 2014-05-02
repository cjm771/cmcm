{% macro subList(name, mediaObjs, sort, context) %}
		{% for groupName,group in mediaObjs %}
			<div class='thumb_group'>
				{% if sort %}
				<div class='group_header'>{{ groupName }}</div>
				{% endif %}
			{% for mediaId, _media in group  %}
			<div class='thumb_wpr'>
					<div class='media_wpr'>
						{#<------------------ handle all previews ---------------->#}
						{% include 'media_preview.php' with {'just_thumbs' :  context.just_thumbs,'media' : _media, 'cmcm_url' : context.cmcm_url, 'media_opts' : context.media_opts} %}
					</div>
						{#<------------------ handle all captions ---------------->#}
					{% if context.no_caption == false %} 
					{% if _media.caption %} 
						<div class='caption'>{{_media.caption}}</div>
					 {% endif %}
					{% endif %}
			</div>
			{% endfor %}
			</div>
		{% endfor %}
{% endmacro %}

{% import _self as macros %}

{% spaceless %}
<div class='frunt-layout frunt-layout-grid' {% if force_cols %}data-force-cols={{ force_cols }}{% endif %}>
{% if sort_by==false %}
	{{ macros.subList(false, media, false,  _context) }}
{% else %}
	{{ macros.subList(false, media, sort_by,  _context) }}
{% endif %}
</div>

{% endspaceless %}