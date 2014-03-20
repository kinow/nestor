{% block content %}
<div class='page-header'>
	<h1>Test Execution</h1>
	<p class='muted'>Choose a Test Plan to execute, or use an existing Test Run</p>
</div>
<div class='row'>
	<div class='span12'>
		<div id="projects">
			{% if testplans[0] is defined %}
				{{ pagination.create_links() }}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Test cases</th>
						<th>Test runs</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{% for testplan in testplans %}
					<tr>
						<td>{{ testplan.name }}</td>
						<td>{{ testplan.description }}</td>
						<td>{{ testplan.testcases.count }}</td>
						<td>{{ HTML.link('execution/testruns?test_plan_id=' ~ testplan.id, "View Test Runs (" ~ testplan.testruns.count ~ ")") }}</td>
						<td>{{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'New Test Run') }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
				{{ pagination.create_links() }}
			{% else %}
			<p>No test plans found. {{ HTML.link('planning/create', "Create a test plan") }}</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}