{% block content %}
<script type='text/x-template' id='sql-textarea-template'>
    <label for='<%= data.name %>' class='col-xs-2 control-label'><%= data.description %></label>
    <div class="col-xs-10">
        <textarea class='form-control col-xs-12' rows='3' id='<%= data.name %>' name='<%= data.name %>'></textarea>
    </div>
</script>

<script type='text/x-template' id='php-script-template'>
    <label for='<%= data.name %>' class='col-xs-2 control-label'><%= data.description %></label>
    <div class="col-xs-10">
        <input type='text' class='form-control col-xs-12' id='<%= data.name %>' name='<%= data.name %>' />
    </div>
</script>

<script type='text/x-template' id='new-parameter-template'>
<div class='parameter_wrapper col-xs-12'>
    <input type='hidden' name='parameterTypes[]' value='<%= data.type %>' />
    <input type='hidden' name='parameters[]' value='<%= data.name %>' />
    <strong><%= data.name %></strong> (<%= data.typeText %>)
    <button class='btn btn-xs btn-remove-parameter'>X</button>
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

.parameter_wrapper {
  float: left;
}
</style>

<div class='row yui3-skin-sam'>
    <div class='col-xs-12'>
        {{ Form.open({'route': 'reports.store', 'class': 'form-horizontal', 'role': 'form'}) }}
            <div class="form-group">
                {{ Form.label('report_type_id', 'Report type', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.select('report_type_id', ['-- Choose one --'] + reportTypes.lists('name', 'id'), null, {'id':"report_type_id", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control col-xs-12"}) }}
                </div>
            </div>
            <!-- elements go here -->
            <div class='form-group' id='reportElementsHolder'></div>
            
            <div class="form-group">
                {{ Form.label('parameters', 'Parameters', {'class': 'col-xs-2 control-label'}) }}
                <div class='col-xs-1'>
                    <span id='addParameterButton' class='glyphicon glyphicon-plus'></span>
                </div>
                <div class="col-xs-9">
                    <div id="parameters">
                    <!-- parameters go here -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class='col-xs-10 col-xs-offset-2'>
                    {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                </div>
            </div>
        {{ Form.close() }}
        <div id="newParameterDiv" class="yui3-overlay-loading">
            <div class="yui3-widget-hd">Add parameter</div>
            <div class="yui3-widget-bd">
              {{ Form.open({'class': 'form-horizontal', 'role': 'form', 'onsubmit': 'return false;'}) }}
                <div class='form-group'>
                  <label for='parameter_name' class='control-label col-xs-2'>Name</label>
                  <div class='col-xs-10'>
                    <input class='form-control' name='name' id='parameter_name' />
                  </div>
                </div>
                <div class='form-group'>
                  <label for='parameter_type' class='control-label col-xs-2'>Type</label>
                  <div class='col-xs-10'>
                    {{ Form.select('parameter_type', parameterTypes.lists('name', 'id'), null, {'class': 'form-control', 'id': 'parameter_type'}) }}
                  </div>
                </div>
                <div class='form-group'>
                  <div class='col-xs-10 col-xs-offset-2'>
                    <input type='button' value='Add' id='add_parameter_button' class='btn btn-primary' /> 
                  </div>
                </div>
              {{ Form.close() }}
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
templatecallback = function(Y) {
    var xy = Y.one("#addParameterButton").getXY();
    var overlay = new Y.Overlay({
        srcNode:"#newParameterDiv",
        width:"20em",
        height:"13em",
        xy:[xy[0] + 0, xy[1] + 15],
        visible: false,
        zIndex: 1
    });
    overlay.render();
    Y.one('#newParameterDiv').on("clickoutside", function() {
        overlay.hide();
    });
    // Labels
    var addParameterButton = Y.one('#addParameterButton');
    addParameterButton.on('click', function(e) {
        overlay.show();
        e.stopPropagation();
    });
    Y.one('#add_parameter_button').on('click', function (e) {
        var newParameter = Y.Template.Micro.compile(Y.one('#new-parameter-template').getHTML());
        var parameterName = Y.one('#parameter_name').get('value');
        var parameterType = Y.one('#parameter_type').get('value');
        var parameterTypeText = Y.one('#parameter_type option:checked').get('text');
        var o = Y.one('#parameters').appendChild(newParameter({name: parameterName, type: parameterType, typeText: parameterTypeText}));
        o.one('.btn-remove-parameter').on('click', function(e) { 
          e.preventDefault();
          o.remove();
          e.stopPropagation();
        });
    });


    var elementsHolder = Y.one('#reportElementsHolder');
    Y.one('#report_type_id').on('change', function(e) {
        e.preventDefault();
        elementsHolder.get('childNodes').remove();
        var val = this.get('value');
        if (val == 1) {
            var element = Y.Template.Micro.compile(Y.one('#sql-textarea-template').getHTML());
            var renderedElement = element({name: 'script', description: 'SQL script'});
            elementsHolder.appendChild(renderedElement);
        } else if (val == 2) {
            var element = Y.Template.Micro.compile(Y.one('#php-script-template').getHTML());
            var renderedElement = element({name: 'script', description: 'PHP script'});
            elementsHolder.appendChild(renderedElement);
        }
    });
}
</script>
{% endblock %}
