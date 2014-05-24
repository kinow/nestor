{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		{{ Form.open({'url': URL.current(), 'class': 'form-horizontal', 'role': 'form'}) }}
			<h4 class='page-header configuration-entry'>
				Appearance
			</h4>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="editor">Editor</label>
				<div class="col-xs-10">
				{{ Form.select('editor', editors, settings.editor, {'id': "editor", 'class': "form-control"}) }}
			  </div>
			</div>
			<h4 class='page-header configuration-entry'>
				Security
			</h4>
			<div class="form-group">
				<label class="col-xs-2 control-label" for="security_enabled">Enable Security</label>
				<div class="col-xs-10">
				    <label class="checkbox-inline" for="security_enabled">
				    	{% set enableSecurity = settings.getConfig()['security_enabled'] %}
				    	<input type="checkbox" name="security_enabled" id="security_enabled" value="true" {{ enableSecurity != "null" ? 'checked="checked"' : '' }} />
				</div>
			</div>
			<!-- Select Basic -->
			<div class="form-group">
			  <label class="col-xs-2 control-label" for="security_provider">Authentication Provider</label>
			  <div class="col-xs-10">
			    <select id="security_provider" name="security_provider" class="form-control">
			      <!--<option value="1">Option one</option>
			      <option value="2">Option two</option>-->
			    </select>
			  </div>
			</div>
			<div class='form-group'>
				<div class='col-xs-10 col-xs-offset-2'>
					<input type='submit' value='Save' class='btn btn-primary' />
				</div>
			</div>
		{{ Form.close() }}
	</div>
</div>
{% endblock %}