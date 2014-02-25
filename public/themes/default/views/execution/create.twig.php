{% block content %}
<div class='page-header'>
    <h1>Create Test Run</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

{{ Form.open({'route': 'execution.store', 'class': 'form-horizontal'}) }}
{{ Form.hidden('test_plan_id', testplan.id) }}
<div class="control-group">
    {{ Form.label('name', 'Test Plan', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'testplan_name', testplan.name, {'id':"testplan_name", 'class': "span10", "readonly": "readonly"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3",
        'class': "span10"}) }}</div>
</div>
<div class="control-group">
    <div class='controls'>
        {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
    </div>
</div>
{{ Form.close() }}

{% endblock %}
