{% block content %}
<div class='page-header'>
	<h1>Manage Nestor</h1>
</div>

<div class='row'>
	<div class='span12'>
		<p class='muted'>
		    {{ HTML.link('/configure', 'Configure System') }}
		    <br/>
		    <small>Configure global settings</small>
		</p>
		<p class='muted'>
			{{ HTML.link('/themeManager', 'Manage Themes') }}
        	<br/>
        	<small>Add, remove, disable or enable themes that change the UI of Nestor.</small>
        </p>

        <p class='muted'>
        	{{ HTML.link('/pluginManager', 'Manage Plug-ins') }}
        	<br/>
        	<small>Install or uninstall plug-ins to extend Nestor behaviour.</small>
       	</p>
	</div>
</div>
{% endblock %}