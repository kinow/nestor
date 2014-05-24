{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<ul class="nav nav-tabs">
		  <li><a href="{{ URL.to('/pluginManager/updates') }}">Updates</a></li>
		  <li><a href="{{ URL.to('/pluginManager/available') }}">Available</a></li>
		  <li><a href="{{ URL.to('/pluginManager/installed') }}">Installed</a></li>
		  <li class="active"><a href="{{ URL.to('/pluginManager/advanced') }}">Advanced</a></li>
		</ul>
	</div>
</div>
<br/>
<div class='row'>
	<div class='col-xs-12'>
		{{ Form.open({'url': '/pluginManager/upload', 'class': 'form-horizontal', 'files': true}) }}
			<fieldset>
				<legend class="scheduler-border">Upload</legend>
			<div class="form-group">
                {{ Form.label('plugin_file', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.file('plugin_file', {'id':"plugin_file", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-10 col-xs-offset-2">
                    {{ Form.submit('Upload', {'class': "btn"}) }}
                </div>
            </div>
            </fieldset>
		{{ Form.close() }}
	</div>
</div>
{% endblock %}