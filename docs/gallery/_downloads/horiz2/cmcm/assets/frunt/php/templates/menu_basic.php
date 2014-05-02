{% macro extrasList(extras, location, context) %}
	<div class='extras {{location}}'>
	{% if context.headers != false %}
		<div class='vertHeader'>
			{{context.headers[1]}}	
		</div>
	{% endif %}
	{% for name,link in extras %}
		<a href='{{context.site_url}}{{link}}' {% if link == context.current %}class='active'{% endif %}>{{name}}</a>
	{% endfor %}
	</div>
{% endmacro %}




{% macro subList(name, projects, more, index, context) %}
	{% if index is not defined %}
		{% set index = 0  %}
	{% endif %}
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
	{% if extras_location=="top" %}
		{{ macros.extrasList(extras, "top", _context) }}
	{% endif %}
	
	{% if headers != false %}
		<div class='vertHeader'>
			{{headers[0]}}	
		</div>
	{% endif %}
{% if sort_by==false %}
	{{ macros.subList(false, projects, 0, 0, _context) }}
{% else %}
	{{ macros.subList(false, projects, sort_by|length, 0, _context) }}
{% endif %}


	{% if extras_location==false or extras_location=="bottom" %}
		{{ macros.extrasList(extras, "bottom", _context) }}
	{% endif %}
</div>

{% endspaceless %}