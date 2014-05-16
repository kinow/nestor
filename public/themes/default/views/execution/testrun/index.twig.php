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
				<colgroup>
					<col width="10%" />
					<col width="30%" />
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
				</colgroup>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Test cases</th>
						<th>Progress</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					{% for testrun in testruns %}
					<tr>
						<td class="vert-align">{{ HTML.link('execution/testruns/' ~ testrun.id, testrun.name) }}</td>
						<td class="vert-align">{{ testrun.description }}</td>
						<td class="vert-align">{{ testrun.testplan.first.testcases.count() }}</td>
						<td class="vert-align">
							{% set testRunProgress = testrun.progress %}
							<div data-toggle="tooltip" data-title='{{ testRunProgress.percentage|number_format(2, ".", ",") }}%' data-container='body' data-placement='top'>
								<div style="margin: 0px;" class="progress progress-striped">
								{% for executionStatusId,percentage in testRunProgress.progress if executionStatusId != 1%}
									{% set extraClass = '' %}
									{% set progressBarName = '' %}
									{% if executionStatusId == 2 %}
										{% set extraClass = 'progress-bar-success' %}
									{% elseif executionStatusId == 3 %}
										{% set extraClass = 'progress-bar-danger' %}
									{% elseif executionStatusId == 4 %}
										{% set extraClass = 'progress-bar-warning' %}
									{% endif %}
								    <div class="progress-bar {{ extraClass }}" style="width: {{ percentage }}%">
								    	<span class="sr-only">{{percentage}}% Complete {% if progressBarName != '' %}({{progressBarName}}){% endif %}</span>
								    </div>
								{% endfor %}
								</div>
							</div>
						</td>
						<td class="vert-align">
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
				This test plan does not have any test execution yet. 
			</p>
			{% endif %}
		</div>
	</div>
</div>
{{ testruns.links() }}
{% endblock %}