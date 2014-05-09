{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ Form.open({'url': URL.current(), 'class': 'form-horizontal', 'role': 'form'}) }}
			<h4 class='page-header configuration-entry'>
				Appearance
			</h4>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="enableSecurity">Editor</label>
				<div class="col-xs-10">
			    <select id="selectbasic" name="selectbasic" class="form-control">
			      <option value="1">Option one</option>
			      <option value="2">Option two</option>
			    </select>
			  </div>
			</div>
			<h4 class='page-header configuration-entry'>
				Security
			</h4>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="enableSecurity">Enable Security</label>
				<div class="col-xs-10">
				    <label class="checkbox-inline" for="enableSecurity">
				    	<input type="checkbox" name="enableSecurity" id="enableSecurity" value="1">
				    </label>
				</div>
			</div>
			<!-- Select Basic -->
			<div class="form-group">
			  <label class="col-xs-2 control-label" for="selectbasic">Select Basic</label>
			  <div class="col-xs-10">
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