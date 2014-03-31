<script type='text/x-template' id='test-case-step-template'>
<div class='step'>
    <div><div class='span2'>Order</div> {{ Form.input('text', 'step_order[]', "", {'readonly': 'readonly', 'class': 'required-2 form-control'}) }}</div>
    <div><div class='span2'>Description</div> {{ Form.textarea('step_description[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}</div>
    <div><div class='span2'>Expected Result</div> {{ Form.textarea('step_expected_result[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}</div>
    <div><div class='span2'>Execution Status</div> 
    {{ Form.select('step_execution_status[]', execution_statuses, {'class': 'required-2 form-control'}) }}
    </div>
    <br style='clear: both' />
    <div class='span2'></div><a href="#" class='btn btn-danger btn-xs btn-remove-test-case-step'><span class='icon-minus'></span> Remove step</a>
    <br style='clear: both' />
</div>
</script>

<div>
<h4>Test Suite &mdash; {{ HTML.link('/testsuites/' ~ node.node_id, node.display_name, {'class': ''}) }}</h4>
<hr/>
<h5>Create a test suite <button class='btn' id='sub-test-suite-btn'>&#x25BC;</button></h5>

{% if errors is defined and errors is not empty %}
<ul>
    {% for error in errors.all() %}
    <li>{{ error }}</li> {% endfor %}
</ul>
{% endif %}

<div id='sub-test-suite'>
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
    <div id="test-case-steps">
    <!-- Test Case steps go here -->
    </div>
    <button id='add-test-case-step' class='btn'><i class='icon-plus'></i> Add Step</button>
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
YUI().use('node', 'sortable', 'template', 'dd-delegate', 'transition', function(Y) {
  // drag and drop of test case steps
  var sortable = new Y.Sortable({
    container: '#test-case-steps',
    nodes: 'div.step',
    opacity: '.1'
  });

  sortable.delegate.after('drag:end', function(e) {
    // update order
    fixOrder();
  });

  var micro = new Y.Template();

  // adds a new test step
  Y.one('#add-test-case-step').on('click', function(e) {
    e.preventDefault();
    var newStep = micro.compile(Y.one('#test-case-step-template').getHTML());
    var o = Y.one('#test-case-steps').appendChild(newStep());
    o.one('.btn-remove-test-case-step').on('click', function(e) { 
      e.preventDefault();
      o.remove();
      fixOrder();
      e.stopPropagation();
    });
    // update order
    fixOrder();
  });

  var fixOrder = function() {
    var order = 0;
    Y.all('.step').each(function(o) {
      o.one('input[name="step_order[]"]').set('value', order);
      order += 1;
    });
  };

  // used to toggle the test suite form visibility
  var sub_test_suite_btn = Y.one('#sub-test-suite-btn');
  var sub_test_suite_div = Y.one('#sub-test-suite');
  sub_test_suite_btn.on('click', function(e) {
    e.preventDefault();
    sub_test_suite_div.toggleView();
    e.stopPropagation();
  });

  // Hide the test suites form
  sub_test_suite_div.hide();

});

</script>