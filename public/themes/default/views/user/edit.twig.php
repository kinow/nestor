{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': ['users.update', user.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
            <div class="form-group">
                {{ Form.label('first_name', 'First Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'first_name', user.first_name, {'id':"first_name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('last_name', 'Last Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'last_name', user.last_name, {'id':"last_name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('email', 'E-mail', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'email', user.email, {'id':"email", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('password', 'Password', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('password', 'password', '', {'id':"password", 'class': "form-control"}) }}
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
        {{ Form.open({'route': ['users.destroy', user.id], 'method': 'DELETE', 'class': 'form-horizontal'}) }}
            <div class="form-group">
                <div class='col-xs-12'>
                    {{ Form.submit('Delete', {'class': 'btn btn-danger pull-right'}) }}
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>
{% endblock %}