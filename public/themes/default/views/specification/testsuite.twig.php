<script type='text/template' id='testStepTemplate'>
<div class='step'>
    <div><div class='span2'>Order</div> {{ Form.input('text', 'step_order[]', '', {'class': 'required-2 form-control'}) }}</div>
    <div><div class='span2'>Description</div> {{ Form.input('text', 'step_description[]', '', {'class': 'required-2 form-control'}) }}</div>
    <div><div class='span2'>Expected Result</div> {{ Form.input('text', 'step_expected_result[]', '', {'class': 'required-2 form-control'}) }}</div>
    <div><div class='span2'>Execution Status</div> {{ Form.input('text', 'step_execution_status[]', '', {'class': 'required-2 form-control'}) }}</div>
    <br style='clear: both' />
    <div class='span2'></div><a href="#" class='btn btn-danger btn-xs' onclick='javascript:removeStep(this);return false;'><span class='icon-minus'></span> Remove step</a>
    <br style='clear: both' />
</div>
</script>

<div>
<h4>Test Suite &mdash; {{ HTML.link('/testsuites/' ~ node.node_id, node.display_name, {'class': ''}) }}</h4>
<hr/>
<h5>Create a test suite <button class='btn' id='sub_test_suite_btn'>&#x25BC;</button></h5>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<div class='hide' id='sub_test_suite'>
{{ Form.open({'route': 'testsuites.store', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', current_project.id) }}
{{ Form.hidden('ancestor', node.descendant) }}
<div class="control-group">
  {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "span4",
    	'placeholder': 'Name'}) }}</div>
</div>
<div class="control-group">
  {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
  <div class="controls">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3",
        'class': "span4", 'placeholder': 'Description'}) }}</div>
</div>
<div class="control-group">
  <div class='controls'>
    {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
  </div>
</div>
{{ Form.close() }}
</div>
<hr/>
<h5>Create a test case</h5>
{{ Form.open({'route': 'testcases.store', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', current_project.id) }}
{{ Form.hidden('test_suite_id', node.node_id) }}
{{ Form.hidden('ancestor', node.descendant) }}
<div class="control-group">
  {{ Form.label('name', 'Name', {'class': 'control-label'}) }}
    <div class="controls">{{ Form.input('text', 'name', old.name, {'id':"name", 'class': "span4",
    	'placeholder': 'Name'}) }}</div>
</div>
<div class="control-group">
  {{ Form.label('description', 'Description', {'class': 'control-label'}) }}
  <div class="controls">{{ Form.textarea('description', old.description, {'id': "description", 'rows': "3",
        'class': "span4", 'placeholder': 'Description'}) }}</div>
</div>
<div class="control-group">
  {{ Form.label('prerequisite', 'Prerequisite', {'class': 'control-label'}) }}
  <div class="controls">{{ Form.textarea('prerequisite', old.prerequisite, {'id': "prerequisite", 'rows': "3",
        'class': "span4", 'placeholder': 'Prerequisite'}) }}</div>
</div>
<div class="control-group">
  <label class='control-label' for='test_case_execution_type_id'>Execution Type</label>
  <div class="controls">
  	{{ Form.select('execution_type_id', execution_type_ids, null, {'id': 'test_case_execution_id', 'class': 'span4'}) }}
  </div>
</div>
<div class="control-group">
  <label class='control-label' for='add_step'>Test Case Steps</label>
  <div class="controls">
    <div id="steps">
    <!-- Test Case steps go here -->
    </div>
    <button id='add_step' class='btn'><i class='icon-plus'></i> Add Step</button>
  </div>
</div>

<div class="control-group">
  <div class='controls'>
    {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
  </div>
</div>
{{ Form.close() }}
</div>
<script>
// Toggles the sub test suite form
$("#sub_test_suite_btn").click(function(){
  $("#sub_test_suite").toggle();
});

// Adds a new test step
$('#add_step').click(function (event) {
  event.preventDefault();
  var steps = $('#steps');

  var stepTemplate = $("#testStepTemplate").html(); // http://stackoverflow.com/questions/14062368/new-recommended-jquery-templates
  var template = stepTemplate.format($('#steps').length+1);
  //var template = cardTemplate.format("http://example.com", "Link Title");
  steps.append(template);
});

// Removes a test step
removeStep = function(elem) {
  var step = $(elem).parent();
  step.remove();
  return false;
};
</script>