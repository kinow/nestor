{% block content %}
<div class='page-header'>
    <h1>Edit Test Case {{ testcase.id }} &mdash; {{ testcase.name }}</h1>
</div>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

{{ Form.open({'route': ['testcases.update', testcase.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', testcase.project_id) }}
{{ Form.hidden('test_suite_id', testcase.test_suite_id) }}
<div class="control-group">
    {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', testcase.name, {'id':"name", 'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.textarea('description', testcase.description, {'id': "description", 'rows': "3",
        'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('prerequisite', 'Prerequisite', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.textarea('prerequisite', testcase.prerequisite, {'id': "prerequisite", 'rows': "3",
        'class': "span10"}) }}</div>
</div>
<div class="control-group">
    {{ Form.label('execution_type_id', 'Execution Type', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.select('execution_type_id', execution_type_ids, testcase.execution_type_id, {'id': "execution_type_id",
        'class': "span10"}) }}</div>
</div>
<div class="control-group">
    <div class='controls'>
    	<div class='span8'>
        	{{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
        </div>
    </div>
</div>
{{ Form.close() }} {% endblock %}

{{ Form.open({'route': ['testcases.destroy', testcase.id], 'method': 'DELETE', 'class': 'form-horizontal pull-right'}) }}
	{{ Form.submit('Delete', {'class': 'btn btn-danger'}) }}
{{ Form.close() }}

<script type='text/javascript'>

templatecallback = function() {
    var opts = {
        absoluteURLs: false,
        cssClass : 'el-rte',
        lang     : 'en',
        height   : 100,
        toolbar  : 'normal',
        cssfiles : ['{{ URL.to('/themes/default/assets/css/plugins/elrte/elrte-inner.css') }}']
    }
    $("#description").elrte(opts);
    $("#prerequisite").elrte(opts);
}

</script>