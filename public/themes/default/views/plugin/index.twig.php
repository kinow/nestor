{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<ul class="nav nav-tabs">
		  <li class="active"><a href="{{ URL.to('/pluginManager/updates') }}">Updates</a></li>
		  <li><a href="{{ URL.to('/pluginManager/available') }}">Available</a></li>
		  <li><a href="{{ URL.to('/pluginManager/installed') }}">Installed</a></li>
		  <li><a href="{{ URL.to('/pluginManager/advanced') }}">Advanced</a></li>
		</ul>
	</div>
</div>
{% endblock %}