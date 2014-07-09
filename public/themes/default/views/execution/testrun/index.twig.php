{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ HTML.link('execution/testruns/create?test_plan_id=' ~ testplan.id, 'New Test Run', {'class': 'btn btn-primary'}) }}
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
					<col width="30%" />
					<col width="20%" />
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
							<a href="{{ URL.to('execution/testruns/' ~ testrun.id ~ '/run') }}" title="Run Test Run" class="btn btn-success"><span class="glyphicon glyphicon-play"></span> Run</a> 
							<a href="{{ URL.to('execution/testruns/' ~ testrun.id ~ '/junit') }}" title="Download JUnit report" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span> JUnit</a>
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