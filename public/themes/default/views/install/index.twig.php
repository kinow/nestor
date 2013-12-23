{% extends "layouts/install.twig.php" %}

{% block content %}

	<div class='center span4 offset4'>
	<h2>Nestor-QA Install</h2>

	{{ Form.open({'url': 'install/index', 'class': 'form-inline'}) }}

		{{ Form.submit('Install Database &amp; Continue &rarr;', {'class': "btn btn-primary"}) }}

	{{ Form.close() }}

	</div>
{% endblock %}