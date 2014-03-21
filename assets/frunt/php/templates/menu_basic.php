
{% macro subList(name, projects, more, index, context) %}
	<div class='group_list group_index_{{index}}'>
	{% if name %}
		{# <----- group header ----> #}
		<div class='group_header group_index_{{index}}' data-name='{{name}}'>{{name}}</div>
	{% endif %}
	
	{% if more and more>0 %}
		{# <----- more groups -----> #}
		{% for groupName,proj in projects %}
			{{ _self.subList(groupName,proj,more-1,index+1, context) }}
		{% endfor %}
	{% else %}
		{# <----- final list-------> #}

		{% for proj in projects %}
			<a href='{{context.site_url}}{{context.url_rewrite}}{{proj.cleanUrl}}' {% if proj.cleanUrl == context.current %} class='active' {% endif %}>{{proj.title}}</a>
		{% endfor %}
	{% endif %}
	</div>
{% endmacro %}

{% import _self as macros %}

{% spaceless %}
<div class='verticalMenu {% if collapse and sort_by %}collapsed{% endif %} {% if collapse_multiple_fans==false and sort_by %}noMulti{% endif %}'  {% if collapse_current %}data-current='{{ collapse_current }}'{% endif %}>
{% if sort_by==false %}
	{{ macros.subList(false, projects, false, 0, _context) }}
{% else %}
	{{ macros.subList(false, projects, sort_by|length, 0, _context) }}
{% endif %}



	<div class='extras'>
	{% for name,link in extras %}
		<a href='{{site_url}}{{link}}' {% if link == current %}class='active'{% endif %}>{{name}}</a>
	{% endfor %}
	</div>
</div>
{% endspaceless %}