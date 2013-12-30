<div>
<h4>Project &mdash; {{ HTML.link('/projects/' ~ node.node_id, node.display_name, {'class': ''}) }}</h4>
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
{{ Form.hidden('parent_id', node.id) }}
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
</div>