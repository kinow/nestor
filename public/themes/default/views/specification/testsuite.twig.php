<div>
<h4>Test Suite &mdash; {{ node.display_name }}</h4>
<hr/>
<h5>Create a test suite</h5>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

{{ Form.open({'route': 'testsuites.store', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', current_project.id) }}
{{ Form.hidden('ancestor', node.descendant) }}
<div class="control-group">
  {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "span4",
    	'placeholder': 'Name'}) }}</div>
</div>
<div class="control-group">
  {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
  <div class="controls">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3",
        'class': "span4", 'placeholder': 'Description'}) }}</div>
</div>
<div class="control-group">
  <div class='controls'>
    {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
  </div>
</div>
{{ Form.close() }}
<hr/>
<h5>Create a test case</h5>
{{ Form.open({'route': 'testcases.store', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', current_project.id) }}
{{ Form.hidden('test_suite_id', node.node_id) }}
{{ Form.hidden('ancestor', node.descendant) }}
<div class="control-group">
  {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "span4",
    	'placeholder': 'Name'}) }}</div>
</div>
<div class="control-group">
  {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
  <div class="controls">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3",
        'class': "span4", 'placeholder': 'Description'}) }}</div>
</div>
<div class="control-group">
  <label class='control-label' for='test_case_execution_type_id'>Execution Type</label>
  <div class="controls">
  	{{ Form.select('execution_type_id', execution_type_ids, null, {'id': 'test_case_execution_id', 'class': 'span4'}) }}
  </div>
</div>
<div class="control-group">
  <div class='controls'>
    {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
  </div>
</div>
{{ Form.close() }}
</div>