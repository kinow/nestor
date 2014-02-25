{% block content %}
<div class='page-header'>
    <h1>Edit Test Run {{ testrun.id }} &mdash; {{ testrun.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

{{ Form.open({'route': ['execution.testruns.update', testrun.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
{{ Form.hidden('test_plan_id', testplan.id) }}
<div class="control-group">
    {{ Form.label('testplan_name', 'Test Plan', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'testplan_name', testplan.name, {'id':"testplan_name", 'class': "span10", "readonly": "readonly"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', testrun.name, {'id':"name", 'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.textarea('description', testrun.description, {'id': "description", 'rows': "3",
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

{{ Form.open({'route': ['execution.testruns.destroy', testrun.id], 'method': 'DELETE', 'class': 'form-horizontal'}) }}
<div class="control-group">
    <div class='controls'>
	{{ Form.submit('Delete', {'class': 'btn btn-danger pull-right'}) }}
	</div>
</div>
{{ Form.close() }}


{% endblock %}