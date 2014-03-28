{% macro dataParams(data) %}
	{% set atts = "" %}
	{% for dataName,arr in data %}
		{% set atts = atts~" data-"~dataName~"='"~arr|join(",")|replace("'", "\'")~"'" %}
	{% endfor %}
		{% set atts = atts~" data-xxMENUxx='projects'" %}
	{{ atts }}
{% endmacro %}

{% macro subList(data, cols, context) %}
	{% set eachCol = data|length // cols %}
	{% for index in 0..cols-1 %}
		<div class='subcolumn'>
			{# <----------------- MIDDLE COLUMNS--------------------> #}
			{% for key,val in data|slice(eachCol*(index), eachCol) %}
				<div class='link' {{ _self.dataParams(val) }} data-val="{{ key }}">{{ key }}</div>
			{% endfor %}
		</div>
	{% endfor %}	
{% endmacro %}


{% import _self as macros %}

{% spaceless %}
<div class='frunt-menu frunt-menu-horiz' >
	{% set COUNT = 0 %}
	{# <------------------ FIRST COLUMN---------------------> #}
	{% if collapse %}
		<div class='column' data-att="xxMENUxx">
			<div class='header'>Menu</div>
			<div class='link'   data-val="projects">Projects</div>
			<div class='extras'>
			{% for name,link in extras %}
				<a href='{{site_url}}{{link}}' class='link {% if link == current %}active{% endif %}'>{{name}}</a>
			{% endfor %}
			</div>

		</div>
	{% endif %}
	{% for colName, col in col_data %}
		<div class='column' data-att="{{ colName }}">
			<div class='header'>{{ colName }}</div>
			<div class='col_content'>
				{# <------------------ FIRST COLUMN---------------------> #}
				{% if COUNT==0 and col_data|length > 1 %}
					{% for link in col %}
						<div class='link'   data-val="{{ link }}" data-xxMENUxx='projects'>{{ link }}</div>
					{% endfor %}
					
				{# <----------------- MIDDLE COLUMNS--------------------> #}
				{% elseif COUNT != col_data|length-1 %}
					
					{% for link,data in col %}
						<div class='link' {{ macros.dataParams(data) }} data-val="{{ link }}">{{ link }}</div>
					{% endfor %}
					
					
					 {# {{ macros.subList(col, cols, _context) }} #} 
					
				{# <-------------- END (PROJECTS) COLUMN----------------> #}
				{% else %}
					{% for projId , data in col %}
						{% set proj = projects[projId] %}
						<a href='{{site_url}}{{url_rewrite}}{{proj.cleanUrl}}' class='link {% if proj.cleanUrl == current %}active{% endif %}' {{ macros.dataParams(data) }} data-val="{{ projId }}">{{ proj.title }}</a>
					{% endfor %}
				{% endif %}
			</div>	
		</div>
		{% set COUNT = COUNT + 1 %}
	{% endfor %}

	{% if collapse == false %}
		<div class='extras'>
		{% for name,link in extras %}
			<a href='{{site_url}}{{link}}' class='link {% if link == current %}active{% endif %}'>{{name}}</a>
		{% endfor %}
		</div>
	{% endif %}
</div>

{% endspaceless %}