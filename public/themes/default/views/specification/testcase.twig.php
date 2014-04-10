<div>
<h4>Test Case &mdash; {{ node.display_name }}</h4>
<hr/>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<h5>Name</h5>
<p>{{ testcase.name }}</p>
<h5>Description</h5>
<p>{{ testcase.description }}</p>
<h5>Prerequisite</h5>
<p>{{ testcase.prerequisite }}</p>
<h5>Execution Type</h5>
<p>{{ testcase.execution_type_name }}</p>
<hr/>
<h5>Test Steps</h5>
{% if testcase.steps is defined and testcase.steps.results is not empty %}
<table class='table table-bordered table-hover'>
	<thead>
		<colgroup>
			<col style="width: 20%;" />
			<col style="width: 80%;" />
		</colgroup>
	</thead>
	<tbody>
	{% for step in testcase.steps.get() %}
		<tr>
			<th colspan='2'>Step #{{ step.order }}</th>
		</tr>
		<tr>
			<th>Description</th>
			<td>{{ step.description }}</td>
		</tr>
		<tr>
			<th>Expected Result</th>
			<td>{{ step.expected_result }}</td>
		</tr>
		<tr>
			<th>Execution Status</th>
			<td>{{ step.executionStatus.first.name }}</td>
		</tr>
		<tr><td colspan='2'>&nbsp;</td></tr>
	{% endfor %}
	</tbody>
</table>
{% else %}
<p>No steps defined</p>
{% endif %}
<hr/>
{{ HTML.linkRoute('testcases.edit', 'Edit', [testcase.id], {'class': 'btn btn-primary'}) }}
<hr/>
</div>