{% block content %}
<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': 'users.store', 'class': 'form-horizontal', 'role': 'form'}) }}
            <div class="form-group">
                {{ Form.label('first_name', 'First Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'first_name', old.first_name, {'id':"first_name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('last_name', 'Last Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'last_name', old.last_name, {'id':"last_name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('email', 'E-mail', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'email', old.email, {'id':"email", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('password', 'Password', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('password', 'password', old.password, {'id':"password", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                <div class='col-xs-12 col-xs-offset-2'>
                    {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>
{% endblock %}
