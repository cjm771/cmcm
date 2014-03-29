{% macro preview(opts) %}
	{% if type=="soundcloud" %}
		<iframe width="100%" height="100%" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url{{opts.url}}&amp;auto_play=false&amp;hide_related=false&amp;visual={{opts.visual}}"></iframe>
	{% endif %}
{% endmacro %}

{% import _self as macros %}

{% spaceless %}
	{{ macros.preview(opts)}}
{% endspaceless %}