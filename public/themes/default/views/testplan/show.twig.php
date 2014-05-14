{% block content %}
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ testplan.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='col-xs-10'>
		{{ Form.textarea('description', testplan.description, {'id': "description", 'rows': "3", 'class': "col-xs-10 form-control", 'readonly': 'readonly'}) }}
    </div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-offset-2 col-xs-8'>
		{{ HTML.linkRoute('planning.edit', 'Edit', [testplan.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
<hr />
<div class='row'>
	<div class='col-xs-12'>
		<p class='muted'><strong>Test Cases</strong></p>
		<div class='well'>
			{{ HTML.link('planning/' ~ testplan.id ~ '/addTestCases', 'Manage test cases in this test plan', {'class': 'btn btn-primary'}) }}
		</div>
		{% if testcases[0] is defined %}
		<table class='table table-bordered table-striped table-hover'>
			<thead>
				<tr>
					<th>Test Case</th>
					<th>Execution Type</th>
				</tr>
			</thead>
			<tbody>
			{% for testcase in testcases %}
				<tr>
					<td>{{ HTML.linkRoute('testcases.show', testcase.name, testcase.id) }}</td>
					<td>{{ testcase.executionType[0]['name'] }}</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
		{% else %}
		<p>No test cases added yet.</p>
		{% endif %}

	</div>
</div>
{% endblock %}