
{% macro subList(name, projects, sort, context) %}
		{% for groupName,group in projects %}
			<div class='thumb_group'>
				{% if sort %}
				<div class='group_header'>{{ groupName }}</div>
				{% endif %}
			{% for projId, proj in group %}
			<div class='thumb_wpr {% if proj.cleanUrl == context.current %}active{% endif %}'>
				<a href='{{context.site_url}}{{context.url_rewrite}}{{proj.cleanUrl}}' title='{{proj.title}}'>
					<div class='media_wpr'>
					{% if proj.media[proj.coverImage] %}
						<img src='{{ context.cmcm_url }}{{ proj.media[proj.coverImage].thumb }}'>
					{% else %}
						<div class='noImage'><span>{{ proj.title|slice(0,1) }}</span></div>
					{% endif %}
					</div>
					{% if context.no_title==false %}
					<div class='title_wpr'><span>{{proj.title}}</span></div>
					{% endif %}
				</a>
			</div>
			{% endfor %}
			</div>
		{% endfor %}
{% endmacro %}

{% import _self as macros %}

{% spaceless %}
<div class='frunt-menu frunt-menu-grid' {% if force_cols %}data-force-cols={{ force_cols }}{% endif %}>
{% if sort_by==false %}
	{{ macros.subList(false, projects, false,  _context) }}
{% else %}
	{{ macros.subList(false, projects, sort_by,  _context) }}
{% endif %}

	<div class='extras'>
	{% for name,link in extras %}
		<a href='{{site_url}}{{link}}' {% if link == current %}class='active'{% endif %}>{{name}}</a>
	{% endfor %}
	</div>
</div>

{% endspaceless %}