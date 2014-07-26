{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('/reports/create', 'New Report', {'class': 'btn btn-primary'}) }}
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="reports">
			{% if reports[0] is defined %}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					{% for report in reports %}
					<tr>
						<td>{{ HTML.linkRoute('reports.show', report.name, [report.id]) }}</td>
						<td>{{ report.description }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>No reports found. {{ HTML.link('/reports/create', 'Create a new report') }}</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}