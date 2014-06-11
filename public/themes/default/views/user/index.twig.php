{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('/users/create', 'New User', {'class': 'btn btn-primary'}) }}
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="users">
			{% if users[0] is defined %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>E-mail</th>
						<th>Active?</th>
					</tr>
				</thead>
				<tbody>
					{% for user in users %}
					<tr>
						<td>{{ HTML.linkRoute('users.show', user.first_name ~ ' ' ~ user.last_name, [user.id]) }}</td>
						<td>{{ user.email }}</td>
						<td>{{ (user.active == 1 ? 'Yes' : 'No') }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No users found. {{ HTML.link('/users/create', 'Create a new user') }}</p>
			{% endif %}
		</div>
	</div>
</div>
{{ users.links() }}
{% endblock %}