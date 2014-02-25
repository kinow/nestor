{% block content %}
<div class='page-header'>
	<h1>Test Runs</h1>
	<p class='muted'>Choose one of the existing Test Runs for Test Plan {{ testplan.name }}</p>
</div>
<div class='row'>
	<div class='span12'>
		<div id="projects">
			{% if testruns[0] is defined %}
				{{ pagination.create_links() }}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Test cases</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{% for testrun in testruns %}
					<tr>
						<td>{{ HTML.link('execution/testruns/' ~ testrun.id, testrun.name) }}</td>
						<td>{{ testrun.description }}</td>
						<td>{{ testrun.testplan.testcases.count() }}</td>
						<td style='text-align: center'><a href="{{ URL.to('execution/testruns/' ~ testrun.id ~ '/run') }}"><i class="icon-play"></i></a></td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
				{{ pagination.create_links() }}

				<p>
					<i class='icon-plus'></i> {{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'Create new Test Run') }}
				</p>
			{% else %}
				<p>
					You have no test runs yet. {{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'Create one now') }}
				</p>
			{% endif %}
		</div>
	</div>
</div>
{% endblock %}