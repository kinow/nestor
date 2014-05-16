{% block content %}
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Test Plan</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ testplan.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ testrun.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='col-xs-10'>
		{{ Form.textarea('description', testrun.description, {'id': "description", 'rows': "3", 'class': "form-control", 'readonly': 'readonly'}) }}
    </div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-10 col-xs-offset-2'>
		{{ HTML.linkRoute('execution.testruns.edit', 'Edit', [testrun.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
{% endblock %}