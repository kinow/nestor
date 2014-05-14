{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': 'testplans.store', 'class': 'form-horizontal'}) }}
            {{ Form.hidden('project_id', project.id) }}
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control"}) }}</div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control"}) }}</div>
            </div>
            <div class="form-group pull-right">
                <div class='col-xs-12'>
                    {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>
{% endblock %}
