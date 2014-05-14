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
							<div class="btn-group">
							  <button type="button" class="btn">Action</button>
							  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li>{{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'New Test Run') }}</li>
							    <li class="divider"></li>
							    <li>{{ HTML.link('execution/testruns?test_plan_id=' ~ testplan.id, "View Test Runs (" ~ testplan.testruns.count ~ ")") }}</li>
							  </ul>
							</div>
						</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No test plans found. {{ HTML.link('planning/create', "Create a test plan") }}</p>
			{% endif %}
		</div>
	</div>
</div>
{{ testplans.links() }}
{% endblock %}