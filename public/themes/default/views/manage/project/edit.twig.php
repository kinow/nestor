{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'url': URL.to('/manage/projects/' ~ project.id), 'method': 'PUT', 'class': 'form-horizontal'}) }}
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', project.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('project_statuses_id', 'Status', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.select('project_statuses_id', projectStatuses, project.project_status.id, {'id':"project_statuses_id", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    <!-- editor plugin -->
                    {# editor.render('description', project.description, {'id': "description", 'rows': "3", 'class': "form-control col-xs-12"}) #}
                    {{ Form.textarea('description', project.description, {'id': "description", 'rows': "3", 'class': "form-control col-xs-12"}) }}
                </div>
            </div>
            <div class="form-group">
                <div class='col-xs-offset-2'>
    	           <div class='col-xs-8'>
        	           {{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                    </div>
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>
<div class='row'>
    <div class='col-xs-12'>
        <div class="form-group">
            <div class='col-xs-12'>
                {{ HTML.link('/manage/projects/' ~ project.id ~ '/destroy', 'Delete', {'class': 'btn btn-danger pull-right'}) }}
            </div>
        </div>
    </div>
</div>
{% endblock %}