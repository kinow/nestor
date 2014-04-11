<script type='text/x-template' id='test-case-step-template'>
<div class='step'>
    <div><div class='span2'>Order</div> {{ Form.input('text', 'step_order[]', "", {'readonly': 'readonly', 'class': 'required-2 form-control'}) }}</div>
    <div><div class='span2'>Description</div> {{ Form.textarea('step_description[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}</div>
    <div><div class='span2'>Expected Result</div> {{ Form.textarea('step_expected_result[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}</div>
    <div><div class='span2'>Execution Status</div> 
    {{ Form.select('step_execution_status[]', execution_statuses_ids, null, {'class': 'required-2 form-control'}) }}
    </div>
    <br style='clear: both' />
    <div class='span2'></div><a href="#" class='btn btn-danger btn-xs btn-remove-test-case-step'><span class='icon-minus'></span> Remove step</a>
    <br style='clear: both' />
</div>
</script>


<script type='text/x-template' id='existing-test-case-step-template'>
<div class='step'>
    {% verbatim %}
    <div><div class='span2'>Order</div> 
      <input type='text' name='step_order[]' value="<%= data.order %>" readonly="readonly" class="required-2 form-control" />
    </div>
    <div><div class='span2'>Description</div> 
      <textarea name='step_description[]' class='required-2 form-control' rows='4'><%= data.description %></textarea>
    </div>
    <div><div class='span2'>Expected Result</div> 
      <textarea name='step_expected_result[]' class='required-2 form-control' rows='4'><%= data.expected_result %></textarea>
    </div>
    <div><div class='span2'>Execution Status</div> 
      <select name='step_execution_status[]' class='required-2 form-control'>
        <% Y.Array.each(data.execution_statuses, function (item) { %>
          <option value='<%= item.id %>'<%= item.id = data.execution_status_id ? 'selected="selected"' : '' %>><%= item.name %></option>
        <% }); %>
      </select>
    </div>
    {% endverbatim %}
    <br style='clear: both' />
    <div class='span2'></div><a href="#" class='btn btn-danger btn-xs btn-remove-test-case-step'><span class='icon-minus'></span> Remove step</a>
    <br style='clear: both' />
</div>
</script>

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
    	<div class='span8'>
        	{{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
        </div>
    </div>
</div>
{{ Form.close() }}

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
    addStep();
  });

  var addStep = function() {
    var newStep = micro.compile(Y.one('#test-case-step-template').getHTML());
    var o = Y.one('#test-case-steps').appendChild(newStep());
    o.one('.btn-remove-test-case-step').on('click', removeTestCaseStep);
    // update order
    fixOrder();
  }

  var addExistingStep = function(options) {
    var newStep = micro.compile(Y.one('#existing-test-case-step-template').getHTML());
    var o = Y.one('#test-case-steps').appendChild(newStep(options));
    o.one('.btn-remove-test-case-step').on('click', removeTestCaseStep);
    // update order
    //fixOrder();
  }

  var fixOrder = function() {
    var order = 0;
    Y.all('.step').each(function(o) {
      o.one('input[name="step_order[]"]').set('value', order);
      order += 1;
    });
  };

  var removeTestCaseStep = function(e) { 
    e.preventDefault();
    var o = e.target.ancestor();
    o.remove();
    fixOrder();
    e.stopPropagation();
  };

  {% for step in testcase.steps.get() %}
  addExistingStep({
    'order': "{{ step.order }}",
    'description': "{{ step.description}}",
    'expected_result': "{{ step.expected_result }}",
    'execution_statuses': {{ execution_statuses }},
    'execution_status_id': "{{ step.executionStatus.first.id }}"
   });
  {% endfor %}

});

</script>