{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<p>Delete project <strong>{{ project.name }}</strong>?</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-12'>
	{{ Form.open({'url': '/manage/projects/' ~ project.id, 'method': 'DELETE'}) }}
		{{ Form.submit('Yes', {'class': 'btn btn-danger'}) }} 
		{{ HTML.link(URL.previous(), 'No', {'class': 'btn btn-primary'}) }}
	{{ Form.close() }}
	</div>
</div>
{% endblock %}