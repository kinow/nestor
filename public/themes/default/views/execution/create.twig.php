{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': 'execution.store', 'class': 'form-horizontal'}) }}
            {{ Form.hidden('test_plan_id', testplan.id) }}
            <div class="form-group">
                {{ Form.label('name', 'Test Plan', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'testplan_name', testplan.name, {'id':"testplan_name", 'class': "form-control", "readonly": "readonly"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                <div class='col-xs-10 col-xs-offset-2'>
                    {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>

{% endblock %}
