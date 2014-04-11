{% block content %}
<div class='page-header'>
	<h1>Projects</h1>
</div>
<div class='row'>
	<div class='span2'>
		<ul class="nav nav-tabs nav-stacked">
			<li>
				{{ HTML.link('/projects/create', 'New Project') }}
			</li>
		</ul>
	</div>
	<div class='span10'>
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
				{{ pagination.create_links() }}
			{% else %}
			<p>No projects found. {{ HTML.link('/projects/create', 'Create a new project') }}</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}