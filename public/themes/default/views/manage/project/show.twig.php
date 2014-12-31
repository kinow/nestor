{% block content %}
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ project.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Status</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ project.project_status.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='col-xs-10'>
		{{ Form.textarea('description', project.description, {'id': "description", 'rows': "3", 'class': "form-control", 'readonly': 'readonly'}) }}
    </div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-offset-2 col-xs-8'>
		{{ HTML.link('manage/projects/' ~ project.id ~ '/edit', 'Edit', {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
{% endblock %}