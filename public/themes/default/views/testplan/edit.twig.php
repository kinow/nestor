{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': ['testplans.update', testplan.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
            {{ Form.hidden('project_id', project.id) }}
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', testplan.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.textarea('description', testplan.description, {'id': "description", 'rows': "3", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                <div class='controls'>
                	<div class='col-xs-10 col-xs-offset-2'>
                    	{{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                    </div>
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>

<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': ['testplans.destroy', testplan.id], 'method': 'DELETE', 'class': 'form-horizontal'}) }}
            <div class="form-group">
                <div class='col-xs-12'>
            	   {{ Form.submit('Delete', {'class': 'btn btn-danger pull-right'}) }}
            	</div>
            </div>
        {{ Form.close() }}
    </div>
</div>
{% endblock %}