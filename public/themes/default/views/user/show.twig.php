{% block content %}
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>First Name</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ user.first_name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Last Name</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ user.last_name }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>E-mail</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ user.email }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-2'>
		<p class='pull-right'><strong>Active?</strong></p>
	</div>
	<div class='col-xs-10'>
		<p>{{ (user.active == 1 ? 'Yes' : 'No') }}</p>
	</div>
</div>
<div class='row'>
	<div class='col-xs-offset-2 col-xs-8'>
		{{ HTML.linkRoute('users.edit', 'Edit', [user.id], {'class': 'btn btn-primary'}) }}
		{{ HTML.link(URL.previous(), 'Back', {'class': 'btn'}) }}
	</div>
</div>
{% endblock %}