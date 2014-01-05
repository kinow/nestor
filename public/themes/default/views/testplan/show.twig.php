{% block content %}
<div class='page-header'>
    <h1>Test Plan {{ testplan.id }} &mdash; {{ testplan.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='span10'>
		<p>{{ testplan.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='span10'>
		{{ Form.textarea('description', testplan.description, {'id': "description", 'rows': "3",
        	'class': "span10", 'readonly': 'readonly'}) }}
    </div>
</div>
<div class='row'>
	<div class='offset2'>
		{{ HTML.linkRoute('testplans.edit', 'Edit', [testplan.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
<hr />
<div class='row'>
	<div class='span12'>
		<p class='muted'><strong>Test Cases in this Test Plan</strong></p>
		{% if testcases[0] is defined %}
		<table class='table table-bordered table-striped table-hover'>
			<thead>
				<tr>
					<th>Test Case</th>
					<th>Execution Type</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			{% for testcase in testcases %}
				<tr>
					<td>{{ HTML.linkRoute('testcases.show', testcase.name, testcase.id) }}</td>
					<td>{{ testcase.executionType[0]['name'] }}</td>
					<td>Remove this test case from test plan link</td>
				</tr>
			{% endfor %}
			</tbody>
		</table>
		{% else %}
		<p>No test cases added yet.</p>
		{% endif %}
		<h4>{{ HTML.link('testplans/' ~ testplan.id ~ '/addTestCases', 'Add new tests cases to this test plan') }}</h4>

	</div>
</div>
{% endblock %}