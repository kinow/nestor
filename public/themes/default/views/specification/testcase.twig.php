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
	{% for step in testcase.steps.get() %}
		<h6>Order</h6>
		<p>{{ step.order }}</p>
		<h6>Description</h6>
		<p>{{ step.description }}</p>
		<h6>Expected Result</h6>
		<p>{{ step.expected_result }}</p>
		<h6>Execution Status</h6>
		<p>{{ step.executionStatus.first.name }} </p>
	{% endfor %}
{% else %}
<p>No steps defined</p>
{% endif %}
<hr/>
{{ HTML.linkRoute('testcases.edit', 'Edit', [testcase.id], {'class': 'btn btn-primary'}) }}
<hr/>
</div>