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
		{% set hasExecutions = testplan.hasExecutions() %}
		{% if hasExecutions %}
		<div class='well'>
			<span class='glyphicon glyphicon-lock'></span> This test plan has already been executed and cannot be edited. 
		</div>
		{% else %}
		<div class='well'>
			{{ HTML.link('planning/' ~ testplan.id ~ '/addTestCases', 'Manage test cases in this test plan', {'class': 'btn btn-primary'}) }}
		</div>
		{% endif %}
		{% if testcases.count() > 0 %}
		{{ Form.open({'url': '/planning/' ~ testplan.id ~ '/assign'}) }}
		<table class='table table-bordered table-striped table-hover'>
			<thead>
				<tr>
					<th>Test Case</th>
					<th>Execution Type</th>
					<th>Assignee</th>
				</tr>
			</thead>
			<tbody>
			{% for testcase in testcases %}
				<tr>
					<td>
						{{ HTML.linkRoute('testcases.edit', testcase.name, testcase.test_case_id) }}
						<input type='hidden' name='testcases[]' value='{{ testcase.id }}' />
					</td>
					<td>{{ testcase.executionType.first.name }}</td>
					<td>{{ Form.select('users[]', ['-- Not assigned --']|merge(users.lists('fullname', 'id')), testcase.assignee, {'class': 'form-control'}) }}</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
		{% if not hasExecutions %}
		<div class='form-group pull-right'>
			<input type='submit' value='Assign' class='btn btn-primary' />
		</div>
		{% endif %}
		{{ Form.close() }}
		{% else %}
		<p>No test cases added yet.</p>
		{% endif %}

	</div>
</div>
{% endblock %}