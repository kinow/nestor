<script type='text/x-template' id='test-case-step-template'>
<div class='step'>
    {% verbatim %}
    <input type='hidden' name='step_id[]' value='-1' />
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Order</label>
      <div class='col-xs-10'>
        <input type='text' name='step_order[]' readonly='readonly' class='required-2 form-control' />
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Description</label>
      <div class='col-xs-10'>
        <textarea name='step_description[]' class='required-2 form-control' rows='4'></textarea>
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Expected Result</label>
      <div class='col-xs-10'>
        <textarea name='step_expected_result[]' class='required-2 form-control' rows='4'></textarea>
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Execution Status</label>
      <div class='col-xs-10'>
        <select name='step_execution_status[]' class='required-2 form-control'>
          <% Y.Array.each(data.execution_statuses, function (item) { %>
            <option value='<%= item.id %>'><%= item.name %></option>
          <% }); %>
        </select>
      </div>
    </div>
    {% endverbatim %}
    <div class='form-group'>
      <div class='col-xs-10 col-xs-offset-2'>
        <a href="#" class='btn btn-danger btn-remove-test-case-step'><span class='icon-minus'></span> Remove step</a>
      </div>
    </div>
</div>
</script>

<script type='text/x-template' id='existing-test-case-step-template'>
<div class='step'>
    {% verbatim %}
    <input type='hidden' name='step_id[]' value='<%= data.id %>' />
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Order</label>
      <div class='col-xs-10'>
        <input type='text' name='step_order[]' value="<%= data.order %>" readonly="readonly" class="required-2 form-control" />
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Description</label>
      <div class='col-xs-10'>
        <textarea name='step_description[]' class='required-2 form-control' rows='4'><%= data.description %></textarea>
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Expected Result</label>
      <div class='col-xs-10'>
        <textarea name='step_expected_result[]' class='required-2 form-control' rows='4'><%= data.expected_result %></textarea>
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Execution Status</label>
      <div class='col-xs-10'>
        <select name='step_execution_status[]' class='required-2 form-control'>
          <% Y.Array.each(data.execution_statuses, function (item) { %>
            <option value='<%= item.id %>'<%= item.id == data.execution_status_id ? 'selected="selected"' : '' %>><%= item.name %></option>
          <% }); %>
        </select>
      </div>
    </div>
    {% endverbatim %}
    <div class='form-group'>
      <div class='col-xs-10 col-xs-offset-2'>
        <a href="#" class='btn btn-danger btn-remove-test-case-step'><span class='icon-minus'></span> Remove step</a>
      </div>
    </div>
</div>
</script>

<script type='text/x-template' id='new-label-template'>
<div class='label_wrapper'>
  <span class="label label-default"><%= data.name %></span> <button class='btn btn-xs btn-remove-label'>X</button>
  <input type='hidden' name='labels[]' value='<%= data.name %>' />
</div>
</script>

<style>
/* Hide overlay markup while loading, if js is enabled */
.yui3-js-enabled .yui3-overlay-loading {
    top: -1000em;
    left: -1000em;
    position: absolute;
}
/* Overlay Look/Feel */
.yui3-overlay-content {
    background-color: #ECEFFB;  
    border: 1px solid #9EA8C6;
    border-radius: 3px;
    box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.25);
}

.yui3-overlay-content .yui3-widget-hd {
    background-color: #B6BFDA;  
    color: #30418C;
    font-size: 120%;
    font-weight: bold;
    padding: 0.2em 0.5em 0.3em;
    border-radius: 2px 2px 0 0;
}

.yui3-overlay-content .yui3-widget-bd {
    padding: 0.4em 0.6em 0.5em;
}

.yui3-overlay-content .yui3-widget-ft {
    background-color:#DFE3F5;
    padding: 0.4em 0.6em 0.5em;
    border-radius: 0 0 2px 2px;
}

.label_wrapper {
  float: left;
}
</style>

{{ Form.open({'route': ['testcases.update', testcase.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
{{ Form.hidden('project_id', testcase.project_id) }}
{{ Form.hidden('test_suite_id', testcase.test_suite_id) }}
<div class="form-group">
    {{ Form.label('name', 'Name', {'class': 'control-label col-xs-2'}) }}
    <div class="col-xs-10">
      {{ Form.input('text', 'name', testcase.version.name, {'id':"name", 'class': "form-control"}) }}
    </div>
</div>
<div class="form-group">
    {{ Form.label('version', 'Version', {'class': 'control-label col-xs-2'}) }}
    <div class="col-xs-10">
      {{ Form.input('text', 'version', testcase.version.version, {'id':"Versionon", 'class': "form-control", 'disabled': 'disabled', 'readonly': 'readonly'}) }}
    </div>
</div>
<div class="form-group">
    {{ Form.label('description', 'Description', {'class': 'control-label col-xs-2'}) }}
    <div class="col-xs-10">
      {{ Form.textarea('description', testcase.version.description, {'id': "description", 'rows': "3",'class': "form-control"}) }}
    </div>
</div>
<div class="form-group">
    {{ Form.label('prerequisite', 'Prerequisite', {'class': 'control-label col-xs-2'}) }}
    <div class="col-xs-10">{{ Form.textarea('prerequisite', testcase.version.prerequisite, {'id': "prerequisite", 'rows': "3",
        'class': "form-control"}) }}</div>
</div>
<div class="form-group">
    {{ Form.label('execution_type_id', 'Execution Type', {'class': 'control-label col-xs-2'}) }}
    <div class="col-xs-10">{{ Form.select('execution_type_id', execution_type_ids, testcase.version.execution_type_id, {'id': "execution_type_id",
        'class': "form-control"}) }}</div>
</div>
<div class="form-group yui3-skin-sam">
  {{ Form.label('labels', 'Labels', {'class': 'control-label col-xs-2'}) }}
  <div class='col-xs-1'>
    <span id='addLabelButton' class='glyphicon glyphicon-plus'></span>
  </div>
  <div class="col-xs-9">
    <div id="labels">
    <!-- Test Case steps go here -->
    </div>
  </div>
</div>
<div class="form-group">
  {{ Form.label('add_step', 'Test Case steps', {'class': 'control-label col-xs-2'}) }}
  <div class="col-xs-10">
    <div id="test-case-steps">
    <!-- Test Case steps go here -->
    </div>
    <button id='add-test-case-step' class='btn'><i class='icon-plus'></i> Add Step</button>
  </div>
</div>
<div class="form-group">
    <div class='col-xs-10 col-xs-offset-2'>
    	<div class='span8'>
        	{{ Form.submit('Update', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
        </div>
    </div>
</div>
{{ Form.close() }}
<div id="labelsDiv" class="yui3-overlay-loading">
  <div class="yui3-widget-hd">Add label</div>
  <div class="yui3-widget-bd">
    {{ Form.open({'class': 'form-horizontal', 'role': 'form', 'onsubmit': 'return false;'}) }}
      <div class='form-group'>
        <label for='label_name' class='control-label col-xs-2'>Name</label>
        <div class='col-xs-10'>
          <input class='form-control' name='name' id='label_name' />
        </div>
      </div>
      <div class='form-group'>
        <div class='col-xs-10 col-xs-offset-2'>
          <input type='button' value='Add' id='add_label_button' class='btn btn-primary' /> 
        </div>
      </div>
    {{ Form.close() }}
  </div>
</div>

{{ Form.open({'route': ['testcases.destroy', testcase.id], 'method': 'DELETE', 'class': 'form-horizontal pull-right'}) }}
	{{ Form.submit('Delete', {'class': 'btn btn-danger'}) }}
{{ Form.close() }}

<br class="clearfix" />

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

YUI().use('node', 'sortable', 'template', 'dd-delegate', 'transition', 'overlay', 'event', 'event-outside', function(Y) {
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
    var o = Y.one('#test-case-steps').appendChild(newStep({
      'execution_statuses': {{ execution_statuses }}
    }));
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
    var order = 1;
    Y.all('.step').each(function(o) {
      o.one('input[name="step_order[]"]').set('value', order);
      order += 1;
    });
  };

  var removeTestCaseStep = function(e) { 
    e.preventDefault();
    var o2 = e.target.ancestor();
    var o1 = o2.ancestor();
    var o = o1.ancestor();
    o.remove();
    fixOrder();
    e.stopPropagation();
  };

  {% for step in testcase.version.steps %}
   addExistingStep({
    'id': '{{ step.test_case_step_id }}',
    'order': "{{ step.order }}",
    'description': "{{ step.description|e('js')}}",
    'expected_result': "{{ step.expected_result|e('js') }}",
    'execution_statuses': {{ execution_statuses }},
    'execution_status_id': "{{ step.execution_status_id }}"
   });
  {% endfor %}

  var xy = Y.one("#addLabelButton").getXY();

  var overlay = new Y.Overlay({
    srcNode:"#labelsDiv",
    width:"20em",
    height:"10em",
    xy:[xy[0] + 0, xy[1] + 15],
    visible: false,
    zIndex: 1
  });
  overlay.render();
  Y.one('#labelsDiv').on("clickoutside", function() {
    overlay.hide();
  });

  // Labels
  var addLabelButton = Y.one('#addLabelButton');
  addLabelButton.on('click', function(e) {
    overlay.show();
    e.stopPropagation();
  });

  Y.one('#add_label_button').on('click', function (e) {
    var newLabel = Y.Template.Micro.compile(Y.one('#new-label-template').getHTML());
    var labelName = Y.one('#label_name').get('value');
    var o = Y.one('#labels').appendChild(newLabel({name: labelName}));
    o.one('.btn-remove-label').on('click', function(e) { 
      e.preventDefault();
      this.get('parentNode').remove();
      e.stopPropagation();
    });
  });

  {% if testcase.version.labels[0] %}
    {% for label in testcase.version.labels %}
  var existingLabel = Y.Template.Micro.compile(Y.one('#new-label-template').getHTML());
  var labelName = '{{ label.name }}';
  var o = Y.one('#labels').appendChild(existingLabel({name: labelName}));
  o.one('.btn-remove-label').on('click', function(e) { 
    e.preventDefault();
    this.get('parentNode').remove();
    e.stopPropagation();
  });
    {% endfor %}
  {% endif %}

});

</script>