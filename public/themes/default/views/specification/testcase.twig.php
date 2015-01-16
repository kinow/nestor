<style>
.label_wrapper {
  display: inline-block;
}
#labels {
	clear: both;
	display: block;
	white-space: nowrap;
	overflow: hidden;
}
</style>

<div>
<h4>Test Case {{ node.display_name }}</h4>
<label>Version</label>
<p>{{ testcase.version.version }}</p>
<label>Name</label>
<p>{{ testcase.version.name }}</p>
<label>Description</label>
<p>{{ testcase.version.description }}</p>
<label>Prerequisite</label>
<p>{{ testcase.version.prerequisite }}</p>
<label>Execution Type</label>
<p>{{ testcase.version.executionType.first.name }}</p>
<label>Labels</label>
<div id="labels">
{% for label in testcase.version.labels %}
<div class='label_wrapper'>
  <span class="label label-default">{{ label.name }}</span>
  <input type='hidden' name='labels[]' value='{{ label.name }}' />
</div>
{% endfor %}
</div>
<hr/>
<label>Test Steps</label>
{% if testcase.version.steps[0] %}
	{% for step in testcase.version.steps %}
<table class='table table-bordered table-hover'>
	<thead>
		<colgroup>
			<col style="width: 20%;" />
			<col style="width: 80%;" />
		</colgroup>
	</thead>
	<tbody>
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
	</tbody>
</table>
	{% endfor %}
{% else %}
<p>No steps defined</p>
{% endif %}
<hr/>
{{ HTML.linkRoute('testcases.edit', 'Edit', [testcase.id], {'class': 'btn btn-primary'}) }}
<hr/>
</div>