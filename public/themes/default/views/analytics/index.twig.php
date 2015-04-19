{% block content %}
<div class='row'>
	<div class='col-xs-4'>
		<ul class="list-group">
		  <li class="list-group-item">
		  	{{ HTML.link('/analytics/reports', 'Reports') }}
		    <br/>
		    <small>User created reports and some default reports.</small>
		  </li>
		  <li class="list-group-item">
		  	{{ HTML.link('/analytics/dashboards', 'Dashboards') }}
        	<br/>
        	<small>User created dashboards and the default quality dashboard.</small>
		  </li>
		  <li class="list-group-item">
		  	{{ HTML.link('/analytics/stats', 'Statistics') }}
        	<br/>
        	<small>General statistics collected from projects.</small>
		  </li>
		</ul>
	</div>
</div>
{% endblock %}