{% set extra = '' %}
{% if options is defined %}
	{% for key,value in options %}
		{% set extra = key ~ '=' ~ value ~ ' ' ~ extra %}
	{% endfor %}
{% endif %}
<textarea name="{{ name }}" {{ extra }}>
{{ value }}
</textarea>