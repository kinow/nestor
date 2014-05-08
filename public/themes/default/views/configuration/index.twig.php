{% block content %}
<div class='page-header'>
	<h1>Configure System</h1>
</div>

<div class='row'>
	<div class='span12'>
		{{ Form.open({'url': URL.current(), 'class': 'form-horizontal', 'role': 'form'}) }}
			<div class="form-group">
				<label class="col-md-4 control-label" for="enableSecurity">Enable Security</label>
				<div class="col-md-4">
				    <label class="checkbox-inline" for="enableSecurity">
				    	<input type="checkbox" name="enableSecurity" id="enableSecurity" value="1">
				    </label>
				</div>
			</div>
			<!-- Select Basic -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="selectbasic">Select Basic</label>
			  <div class="col-md-4">
			    <select id="selectbasic" name="selectbasic" class="form-control">
			      <option value="1">Option one</option>
			      <option value="2">Option two</option>
			    </select>
			  </div>
			</div>
		{{ Form.close() }}
	</div>
</div>
{% endblock %}