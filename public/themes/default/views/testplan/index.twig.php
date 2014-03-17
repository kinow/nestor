{% block content %}
<div class='page-header'>
	<h1>Test Plans</h1>
</div>
<div class='row'>
	<div class='span2'>
		<ul class="nav nav-tabs nav-stacked">
			<li>
				{{ HTML.link('/planning/create', 'New Test Plan') }}
			</li>
		</ul>
	</div>
	<div class='span10'>
		<div id="projects">
			{% if testplans[0] is defined %}
				{{ pagination.create_links() }}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th># test cases</th>
					</tr>
				</thead>
				<tbody>
					{% for testplan in testplans %}
					<tr>
						<td>{{ HTML.linkRoute('planning.show', testplan.name, [testplan.id]) }}</td>
						<td>{{ testplan.description }}</td>
						<td>{{ testplan.testcases.count }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
				{{ pagination.create_links() }}
			{% else %}
			<p>No test plans found. {{ HTML.link('/planning/create', 'Create a new test plan') }}</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}