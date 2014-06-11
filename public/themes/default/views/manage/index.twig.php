{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<ul class="list-group">
		  <li class="list-group-item">
		  	{{ HTML.link('/configure', 'Configuration System') }}
		    <br/>
		    <small>Configure global settings</small>
		  </li>
		  <li class="list-group-item">
		  	{{ HTML.link('/themeManager', 'Manage Themes') }}
        	<br/>
        	<small>Add, remove, disable or enable themes that change the UI of Nestor.</small>
		  </li>
		  <li class="list-group-item">
		  	{{ HTML.link('/pluginManager', 'Manage Plug-ins') }}
        	<br/>
        	<small>Install or uninstall plug-ins to extend Nestor behaviour.</small>
		  </li>
		  <li class="list-group-item">
		  	{{ HTML.link('/users', 'Manage Users') }}
        	<br/>
        	<small>Create/delete/modify users that can log in to this Nestor.</small>
		  </li>
		</ul>
	</div>
</div>
{% endblock %}