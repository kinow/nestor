{% block content %}
<div class='row'>
	<div class='col-xs-12'>
		<div id="projects">
			{% if testcases[0] is defined %}
			<div class='row'>
				<div class='col-xs-4'>
					<div id='navigation_tree_panel'>
						<p>Navigation tree</p>
						{{ navigation_tree_html }}
					</div>
					<br/>
				</div>
				<div class="col-xs-8" id="test_specification">
					<div id='nodes_panel'>
						<h4>{{ testcase.name }}</h4>
						<hr />
						<h5>Description</h5>
						<p>
							{{ testcase.description }}
						</p>
						<hr />
						<h5>Prerequisite</h5>
						<p>
							{{ testcase.prerequisite }}
						</p>
						<hr/>
						<p>Execution Type: {{ testcase.executionType.first.name }}</p>
						<hr/>
						<h5>Execution Status</h5>

						{{ Form.open({'action': Request.url(), 'method': 'post', 'class': 'form-vertical'}) }}

							<div class="form-group">
							    {{ Form.label('notes', 'Notes', {'class': 'control-label col-xs-12'}) }}
							    <div class="col-xs-12">
							    	{{ Form.textarea('notes', '', {'id': "notes", 'rows': "3", 'class': "form-control"}) }}
							    </div>
							</div>

							{% set lastExecutionStatus = testcase.lastExecutionStatus.first %}
							{% if lastExecutionStatus == null %}
								{% set lastExecutionStatusId = 1 %} {# FIXME magic number, 1 is NOT RUN #}
							{% else %}
								{% set lastExecutionStatusId = lastExecutionStatus.execution_status_id %} {# FIXME magic number, 1 is NOT RUN #}
							{% endif %}
							{% for executionStatus in executionStatuses %}
							<div class='radio col-xs-12'>
								<label>
									{{ Form.radio('execution_status_id', executionStatus.id, lastExecutionStatusId == executionStatus.id) }} {{ executionStatus.name }}
								</label>
							</div>
							{% endfor %}
							{{ Form.submit('Save', {'class': 'btn btn-primary'}) }}
						{{ Form.close() }}
					</div>
				</div>
			</div>
			{% else %}
			<p>
				This test run is empty.
			</p>
			{% endif %}
		</div>
	</div>
</div>
<script type='text/javascript'>
var templatecallback = function() {
	$("#navigation_tree_panel").fancytree({
		imagePath: "{{ URL.to('/themes/default/assets/icons/32x32') }}/",
		extensions: [],
		activeVisible: true, // Make sure, active nodes are visible (expanded).
	    aria: false, // Enable WAI-ARIA support.
	    autoActivate: true, // Automatically activate a node when it is focused (using keys).
	    autoCollapse: false, // Automatically collapse all siblings, when a node is expanded.
	    autoScroll: false, // Automatically scroll nodes into visible area.
	    clickFolderMode: 1, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
	    checkbox: false, // Show checkboxes.
	    debugLevel: 1, // 0:quiet, 1:normal, 2:debug
	    disabled: false, // Disable control
	    generateIds: false, // Generate id attributes like <span id='fancytree-id-KEY'>
	    idPrefix: "ft_", // Used to generate node id´s like <span id='fancytree-id-<key>'>.
	    icons: true, // Display node icons.
	    keyboard: true, // Support keyboard navigation.
	    keyPathSeparator: "/", // Used by node.getKeyPath() and tree.loadKeyPath().
	    minExpandLevel: 0, // 1: root node is not collapsible
	    selectMode: 1, // 1:single, 2:multi, 3:multi-hier
	    tabbable: true, // Whole tree behaves as one single control
	    childcounter: {
	        deep: true,
	        hideZeros: true,
	        hideExpanded: true
	    },
	    focus: function(e, data) {
			var node = data.node;
			// Auto-activate focused node after 1 second
			if(node.data.href){
				node.scheduleAction("activate", 100000);
			}
		},
	    blur: function(e, data) {
			data.node.scheduleAction("cancel");
		},
	    activate: function(e, data){
			var node = data.node;
			if(node.data.href){
				window.open(node.data.href, node.data.target);
			}
		},
		click: function(e, data){ // allow re-loads
			var node = data.node;
			if(node.isActive() && node.data.href){
				// TODO: data.tree.reactivate();
			}
		}
	});
	$("#navigation_tree_panel").fancytree("getRootNode").visit(function(node){
        node.setExpanded(true);
    });
	var tree = $("#navigation_tree_panel").fancytree("getTree");
	tree.setFocus(true);
}
</script>
{% endblock %}