{% extends "layouts/install.twig.php" %}

{% block content %}

	<div class='center span4 offset4'>
		<h2>Nestor-QA Install</h2>

		{% if (Session.has('install_errors')) %}
		<div class="alert alert-block alert-error">
	      <strong>Error!</strong>
	      <ul>
	      {% for error in errors.all() %}
	        <li>{{ error }}</li>
	      {% endfor %}
	      </ul>
	    </div>
		{% endif %}

		{{ Form.open({'url': '/install/user', 'class': 'form-horizontal'}) }}
			<fieldset>
				<legend>Create admin user</legend>
				<div class="control-group">
				    {{ Form.label('first_name', 'First name', 'class="control-label"') }}
				    <div class="controls">
				    	{{ Form.input('text', 'first_name', old.first_name, {'placeholder': 'First Name'}) }}
				    </div>
				</div>
				<div class="control-group">
				    {{ Form.label('last_name', 'Last name', 'class="control-label"') }}
				    <div class="controls">
				    	{{ Form.input('text', 'last_name', old.last_name, {'placeholder': 'Last Name'}) }}
				    </div>
				</div>
				<div class="control-group">
				    {{ Form.label('email', 'E-mail', 'class="control-label"') }}
				    <div class="controls">
				    	{{ Form.input('text', 'email', old.email, {'placeholder': 'E-mail'}) }}
				    </div>
				</div>
				<div class="control-group">
				    {{ Form.label('password', 'Password', 'class="control-label"') }}
				    <div class="controls">
				    	{{ Form.input('password', 'password', old.password, {'placeholder': ''}) }}
				    </div>
				</div>
				<div class='control-group'>
					{{ Form.submit('Next', {'class': 'btn btn-primary'}) }}
				</div>
			</fieldset>

		{{ Form.close() }}
	</div>

{% endblock %}