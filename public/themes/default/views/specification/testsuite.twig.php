<script type='text/x-template' id='new-test-suite-label-template'>
<div class='label_wrapper'>
  <span class="label label-default"><%= data.name %></span> <button class='btn btn-xs btn-remove-label'>X</button>
  <input type='hidden' name='labels[]' value='<%= data.name %>' />
</div>
</script>

<script type='text/x-template' id='test-case-step-template'>
<div class='step'>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Order</label>
      <div class='col-xs-10'>
        {{ Form.input('text', 'step_order[]', "", {'readonly': 'readonly', 'class': 'required-2 form-control'}) }}
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Description</label>
      <div class='col-xs-10'>
        {{ Form.textarea('step_description[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Expected Result</label>
      <div class='col-xs-10'>
        {{ Form.textarea('step_expected_result[]', '', {'class': 'required-2 form-control', 'rows': '4'}) }}
      </div>
    </div>
    <div class='form-group'>
      <label class='col-xs-2 control-label'>Execution Status</label>
      <div class='col-xs-10'>
        {{ Form.select('step_execution_status[]', execution_statuses_ids, null, {'class': 'required-2 form-control'}) }}
      </div>
    </div>
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

<div>
  <h4>Test Suite {{ HTML.link('/testsuites/' ~ node.node_id, node.display_name, {'class': ''}) }}</h4>
  <p>{{ testsuite.description }}</p>
  {% if labels.count() > 0 %}
  <div style='margin: 4px 0px; display: table;'>
    {% for label in labels.get() %}
  <div class='label_wrapper'>
    <span class="label label-default">{{ label.name }}</span>
    <input type='hidden' name='labels[]' value='{{ label.name }}' />
  </div>
    {% endfor %}
  </div>
  {% endif %}
  <h5>Create a child test suite <button class='btn' id='sub-test-suite-btn'>&#x25BC;</button></h5>

  <div id='sub-test-suite'>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li class="active"><a href="#new-test-suite" role="tab" data-toggle="tab">New</a></li>
      <li><a href="#copy-test-suite" role="tab" data-toggle="tab">Copy</a></li>
      <li><a href="#import-test-suite" role="tab" data-toggle="tab">Import</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="new-test-suite">
        <br/>
        {{ Form.open({'route': 'testsuites.store', 'class': 'form-horizontal'}) }}
          {{ Form.hidden('project_id', current_project.id) }}
          {{ Form.hidden('ancestor', node.descendant) }}
          <div class="form-group">
            {{ Form.label('name', 'Name', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
              {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control", 'placeholder': 'Name'}) }}
            </div>
          </div>
          <div class="form-group">
            {{ Form.label('description', 'Description', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
              {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control", 'placeholder': 'Description'}) }}
            </div>
          </div>
          <div class="form-group yui3-skin-sam">
            {{ Form.label('labels', 'Labels', {'class': 'control-label col-xs-2'}) }}
            <div class='col-xs-1'>
              <span id='addTestSuiteLabelButton' class='glyphicon glyphicon-plus'></span>
            </div>
            <div class="col-xs-9">
              <div id="test_suite_labels">
              <!-- Labels go here -->
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class='col-xs-8 col-xs-offset-2'>
              {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
            </div>
          </div>
        {{ Form.close() }}
        <div id="testSuiteLabelsDiv" class="yui3-overlay-loading">
          <div class="yui3-widget-hd">Add label</div>
          <div class="yui3-widget-bd">
            {{ Form.open({'class': 'form-horizontal', 'role': 'form', 'onsubmit': 'return false;'}) }}
              <div class='form-group'>
                <label for='label_name' class='control-label col-xs-2'>Name</label>
                <div class='col-xs-10'>
                  <input class='form-control' name='name' id='test_suite_label_name' />
                </div>
              </div>
              <div class='form-group'>
                <div class='col-xs-10 col-xs-offset-2'>
                  <input type='button' value='Add' id='add_test_suite_label_button' class='btn btn-primary' /> 
                </div>
              </div>
            {{ Form.close() }}
          </div>
        </div>
      </div>
      <div class="tab-pane" id="copy-test-suite">
        <br/>
        {{ Form.open({'url': '/testsuites/copy', 'class': 'form-horizontal'}) }}
          {{ Form.hidden('project_id', current_project.id) }}
          {{ Form.hidden('ancestor', node.descendant) }}
          <div class="form-group">
            {{ Form.label('copy_name', 'From', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10 yui3-skin-sam">
              {{ Form.input('text', 'copy_name', old.copy_name, {'id':"copy_name", 'class': "form-control", 'placeholder': 'From', 'required': 'required'}) }}
            </div>
          </div>
          <div class="form-group">
            {{ Form.label('copy_new_name', 'New Name', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
              {{ Form.input('text', 'copy_new_name', old.copy_new_name, {'id':"copy_new_name", 'class': "form-control", 'placeholder': 'New Name',  'required': 'required'}) }}
            </div>
          </div>
          <div class="form-group">
            <div class='col-xs-8 col-xs-offset-2'>
              {{ Form.submit('Copy', {'class': "btn btn-primary"}) }}
            </div>
          </div>
        {{ Form.close() }}
      </div>
      <div class="tab-pane" id="import-test-suite">
        Import
      </div>
    </div>
  </div>
  <hr/>
  <h5>Create a test case</h5>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#new-test-case" role="tab" data-toggle="tab">New</a></li>
    <li><a href="#copy-test-case" role="tab" data-toggle="tab">Copy</a></li>
    <li><a href="#import-test-case" role="tab" data-toggle="tab">Import</a></li>
  </ul>
  <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="new-test-case">
        <br/>
        {{ Form.open({'route': 'testcases.store', 'class': 'form-horizontal'}) }}
          {{ Form.hidden('project_id', current_project.id) }}
          {{ Form.hidden('test_suite_id', node.node_id) }}
          {{ Form.hidden('ancestor', node.descendant) }}
          <div class="form-group">
            {{ Form.label('name', 'Name', {'class': 'control-label col-xs-2'}) }}
              <div class="col-xs-10">
                {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control", 'placeholder': 'Name'}) }}
              </div>
          </div>
          <div class="form-group">
            {{ Form.label('description', 'Description', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
              {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control", 'placeholder': 'Description'}) }}</div>
          </div>
          <div class="form-group">
            {{ Form.label('prerequisite', 'Prerequisite', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
              {{ Form.textarea('prerequisite', old.prerequisite, {'id': "prerequisite", 'rows': "3", 'class': "form-control", 'placeholder': 'Prerequisite'}) }}
            </div>
          </div>
          <div class="form-group">
            {{ Form.label('test_case_execution_id', 'Execution Type', {'class': 'control-label col-xs-2'}) }}
            <div class="col-xs-10">
            	{{ Form.select('execution_type_id', execution_type_ids, null, {'id': 'test_case_execution_id', 'class': 'form-control'}) }}
            </div>
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
            <label class='control-label col-xs-2' for='add_step'>Test Case Steps</label>
            <div class="col-xs-10">
              <div id="test-case-steps">
              <!-- Test Case steps go here -->
              </div>
              <button id='add-test-case-step' class='btn'><i class='icon-plus'></i> Add step</button>
            </div>
          </div>

          <div class="form-group">
            <div class='col-xs-10 col-xs-offset-2'>
              {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
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
      </div>
      <div class="tab-pane active" id="copy-test-case">
        Copy
      </div>
      <div class="tab-pane active" id="import-test-case">
      </div>
</div>
<script>
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
    var order = 1;
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

  // add test suite label button
  var xy = Y.one("#addTestSuiteLabelButton").getXY();

  var testsuiteOverlay = new Y.Overlay({
    srcNode:"#testSuiteLabelsDiv",
    width:"20em",
    height:"10em",
    xy:[xy[0] + 0, xy[1] + 15],
    visible: false,
    zIndex: 1
  });
  testsuiteOverlay.render();
  Y.one('#testSuiteLabelsDiv').on("clickoutside", function() {
    testsuiteOverlay.hide();
  });

  // Labels
  var addLabelButton = Y.one('#addTestSuiteLabelButton');
  addLabelButton.on('click', function(e) {
    testsuiteOverlay.show();
    e.stopPropagation();
  });

  Y.one('#add_test_suite_label_button').on('click', function (e) {
    var newLabel = Y.Template.Micro.compile(Y.one('#new-test-suite-label-template').getHTML());
    var labelName = Y.one('#test_suite_label_name').get('value');
    var o = Y.one('#test_suite_labels').appendChild(newLabel({name: labelName}));
    o.one('.btn-remove-label').on('click', function(e) { 
      e.preventDefault();
      o.remove();
      e.stopPropagation();
    });
  });
  // end test suite label button

  // Auto complete for copying test suites
  Y.one('#copy_name').plug(Y.Plugin.AutoComplete, {
    resultFilters    : 'phraseMatch',
    resultHighlighter: 'phraseMatch',
    source           : testsuiteNames
  });

  // Hide the test suites form
  sub_test_suite_div.hide();

  // add label button
  xy = Y.one("#addLabelButton").getXY();

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
      o.remove();
      e.stopPropagation();
    });
  });
  // end add label button

  var testsuiteNames = [
    {% if testsuites is defined %}
      {% for testsuite in testsuites %}
      '{{ testsuite.name }}',
      {% endfor %}
    {% endif %}
  ];

});

</script>