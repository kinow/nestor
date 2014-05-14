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
			{% if projects[0] is defined %}
				{{ pagination.create_links() }}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					{% for project in projects %}
					<tr>
						<td>{{ HTML.linkRoute('projects.show', project.name, [project.id]) }}</td>
						<td>{{ project.description }}</td>
						<td>{{ project.projectStatus.first.name }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No projects found. {{ HTML.link('/projects/create', 'Create a new project') }}</p>
			{% endif %}
		</div>
	</div>
</div>
{{ projects.links() }}
{% endblock %}