{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'Create new Test Run', {'class': 'btn btn-primary'}) }}
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		<div id="testruns">
			{% if testruns[0] is defined %}
				{{ pagination.create_links() }}
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Test cases</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					{% for testrun in testruns %}
					<tr>
						<td>{{ HTML.link('execution/testruns/' ~ testrun.id, testrun.name) }}</td>
						<td>{{ testrun.description }}</td>
						<td>{{ testrun.testplan.first.testcases.count() }}</td>
						<td>
							<a href="{{ URL.to('execution/testruns/' ~ testrun.id ~ '/run') }}"><i class="icon-play"></i></a>
							<div class="btn-group">
							  <button type="button" class="btn">Action</button>
							  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li>{{ HTML.link('execution/testruns/' ~ testrun.id ~ '/run', 'Run') }}</li>
							  </ul>
							</div>
						</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
			{% else %}
			<p>
				You have no test runs yet. {{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'Create one now') }}
			</p>
			{% endif %}
		</div>
	</div>
</div>
{{ testruns.links() }}
{% endblock %}