{% block content %}
<div class='page-header'>
    <h1>Test Run {{ testrun.id }} &mdash; {{ testrun.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Test Plan</strong></p>
	</div>
	<div class='span10'>
		<p>{{ testplan.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='span10'>
		<p>{{ testrun.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='span10'>
		{{ Form.textarea('description', testrun.description, {'id': "description", 'rows': "3",
        	'class': "span10", 'readonly': 'readonly'}) }}
    </div>
</div>
<div class='row'>
	<div class='offset2'>
		{{ HTML.linkRoute('execution.testruns.edit', 'Edit', [testrun.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
{% endblock %}