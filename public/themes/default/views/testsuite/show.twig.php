{% block content %}
<div class='page-header'>
    <h1>Test Suite {{ testsuite.id }} &mdash; {{ testsuite.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Project</strong></p>
	</div>
	<div class='span10'>
		<p>{{ testsuite.project.first.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Name</strong></p>
	</div>
	<div class='span10'>
		<p>{{ testsuite.name }}</p>
	</div>
</div>
<div class='row'>
	<div class='span2'>
		<p class='pull-right'><strong>Description</strong></p>
	</div>
	<div class='span10'>
		{{ Form.textarea('description', testsuite.description, {'id': "description", 'rows': "3",
        	'class': "span10", 'readonly': 'readonly'}) }}
    </div>
</div>
<div class='row'>
	<div class='offset2'>
		{{ HTML.linkRoute('testsuites.edit', 'Edit', [testsuite.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
{% endblock %}