{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('/planning/create', 'New Test Plan', {'class': 'btn btn-primary'}) }}
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="testplans">
			{% if testplans.total == 0 %}
			<p>No test plans found. {{ HTML.link('/testplans/create', 'Create a new test plan') }}</p>
			{% else %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th># test cases</th>
					</tr>
				</thead>
				<tbody>
					{% for testplan in testplans.data %}
					<tr>
						<td>{{ HTML.linkRoute('planning.show', testplan.name, [testplan.id]) }}</td>
						<td>{{ testplan.description }}</td>
						<td>{{ testplan.test_cases|length }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% endif %}
		</div>
	</div>
</div>
{{ testplans.links() }}
{% endblock %}