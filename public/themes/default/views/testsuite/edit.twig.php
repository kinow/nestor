{% block content %}
{% set project = testsuite.project.first %}

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

<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': ['testsuites.update', testsuite.id], 'method': 'PUT', 'class': 'form-horizontal'}) }}
            {{ Form.hidden('project_id', testsuite.project.id) }}
            <div class='form-group'>
                {{ Form.label('project', 'Project', {'class': 'col-xs-2 control-label'}) }}
                <div class='col-xs-10'>
                    {{ Form.input('text', 'project', testsuite.project.name, {'id':"project", 'class': "form-control", 'readonly': 'readonly'}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', testsuite.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.textarea('description', testsuite.description, {'id': "description", 'rows': "3", 'class': "form-control"}) }}
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
                <div class='controls'>
                	<div class='col-xs-10 col-xs-offset-2'>
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
    </div>
</div>

<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': ['testsuites.destroy', testsuite.id], 'method': 'DELETE', 'class': 'form-horizontal'}) }}
        <div class="form-group">
            <div class='col-xs-12'>
        	{{ Form.submit('Delete', {'class': 'btn btn-danger pull-right'}) }}
        	</div>
        </div>
        {{ Form.close() }}
    </div>
</div>

<script type='text/javascript'>
YUI().use('node', 'template', 'overlay', 'event', 'event-outside', function(Y) {
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

  {% if testsuite.labels[0] %}
    {% for label in testsuite.labels %}
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

{% endblock %}