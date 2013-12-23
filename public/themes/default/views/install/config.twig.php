{% extends "layouts/install.twig.php" %}

{% block content %}

	<div class='center span4 offset4'>
		<h2>Nestor-QA Install</h2>

		<p class='muted'><small>You can change these settings later in <code>app/config/</code></small></p>

		{{ Form.open({'url': '/install/config', 'class': 'form-horizontal'}) }}
			<div class="control-group">
			    {{ Form.label('site_theme', 'Site theme', 'class="control-label"') }}
			    <div class="controls">
			    	{{ Form.select('site_theme', Nestor.getAvailableThemes(), old.site_name, {'placeholder': 'First Name'}) }}
			    </div>
			</div>
			<div class='control-group'>
				{{ Form.submit('Finish', {'class': 'btn btn-primary'}) }}
			</div>

		{{ Form.close() }}
	</div>

{% endblock %}