{% extends "layouts/install.twig.php" %}

{% block content %}

	<div class='center span4 offset4'>
		<h2>Success!</h2>

		<p>Install complete</p>

		<p>You can now {{ HTML.link('/', 'login to the admin') }}</p>

	</div>

{% endblock %}