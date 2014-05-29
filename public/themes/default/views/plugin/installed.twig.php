{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<ul class="nav nav-tabs">
		  <li><a href="{{ URL.to('/pluginManager/updates') }}">Updates</a></li>
		  <li><a href="{{ URL.to('/pluginManager/available') }}">Available</a></li>
		  <li class="active"><a href="{{ URL.to('/pluginManager/installed') }}">Installed</a></li>
		  <li><a href="{{ URL.to('/pluginManager/advanced') }}">Advanced</a></li>
		</ul>
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="projects">
			{% if plugins[0] is defined %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th><a href="{{ plugin.url }}">Name</a></th>
						<th>Description</th>
						<th>Status</th>
						<th>Version</th>
					</tr>
				</thead>
				<tbody>
					{% for plugin in plugins %}
					<tr>
						<td>{{ plugin.name }}</td>
						<td>{{ plugin.description }}</td>
						<td>{{ plugin.status }}</td>
						<td>{{ plugin.version }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No plugins found.</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}