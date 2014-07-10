{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<div id="projects">
			{% if testplans[0] is defined %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Test cases</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					{% for testplan in testplans %}
					<tr>
						<td>{{ testplan.name }}</td>
						<td>{{ testplan.description }}</td>
						<td>{{ testplan.testcases.count }}</td>
						<td>
							{{ HTML.link('execution/testruns?test_plan_id=' ~ testplan.id, "View Test Runs (" ~ testplan.testruns.count ~ ")", {'class': 'btn btn-primary'}) }}
						</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No test plans with test cases found. {{ HTML.link('planning/create', "Create a test plan") }}. Or add test cases to existing test plans.</p>
			{% endif %}
		</div>
	</div>
</div>
{{ testplans.links() }}
{% endblock %}