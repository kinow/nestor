{% block content %}
<div class='page-header'>
    <h1>Edit Test Suite {{ testsuite.id }} &mdash; {{ testsuite.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

{% set project = testsuite.project.first %}

{{ Form.open({'route': ['testsuites.update', testsuite.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', project.id) }}
<div class='control-group'>
    {{ Form.label('project', 'Project', {'class': 'control-label'}) }}
    <div class='controls'>{{ Form.input('text', 'project', project.name, {'id':"project", 'class': "span10", 'readonly': 'readonly'}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', testsuite.name, {'id':"name", 'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.textarea('description', testsuite.description, {'id': "description", 'rows': "3",
        'class': "span10"}) }}</div>
</div>
<div class="control-group">
    <div class='controls'>
    	<div class='span8'>
        	{{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
        </div>
    </div>
</div>
{{ Form.close() }}

{{ Form.open({'route': ['testsuites.destroy', testsuite.id], 'method': 'DELETE', 'class': 'form-horizontal'}) }}
<div class="control-group">
    <div class='controls'>
	{{ Form.submit('Delete', {'class': 'btn btn-danger pull-right'}) }}
	</div>
</div>
{{ Form.close() }}


{% endblock %}