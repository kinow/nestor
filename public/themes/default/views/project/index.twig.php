{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('/projects/create', 'New Project', {'class': 'btn btn-primary'}) }}
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="projects">
			{% if projects.total == 0 %}
			<p>No projects found. {{ HTML.link('/projects/create', 'Create a new project') }}</p>
			{% else %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					{% for project in projects.data %}
					<tr>
						<td>{{ HTML.linkRoute('projects.show', project.name, [project.id]) }}</td>
						<td>{{ project.description }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% endif %}
		</div>
	</div>
</div>
{{ projects.links() }}
{% endblock %}