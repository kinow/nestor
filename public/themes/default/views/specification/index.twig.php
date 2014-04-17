{% block content %}

<div class='page-header'>
	<h1>Test Specification</h1>
</div>
<div class='row'>
	<div class='span4' id='navigation_tree_panel'>
		<p>Navigation tree</p>
		{{ navigation_tree_html }}
	</div>
	<div class="span8" id="test_specification">
		<div class='pad_l pad_r' id='nodes_panel'>

			{% if node.node_type_id == 1 %} {# project #}
				{% include 'views/specification/project.twig.php' %}
			{% elseif node.node_type_id == 2 %} {# test suite #}
				{% include 'views/specification/testsuite.twig.php' %}
			{% elseif node.node_type_id == 3 %} {# test case #}
				{% include 'views/specification/testcase.twig.php' %}
			{% else %} {# main page #}
			<h4>Select a node in the navigation tree</h4>
			<p>Different forms or details about nodes will be displayed here.</p>
			{% endif %}
		</div>
	</div>
</div>
<script type="text/javascript" src="{{ URL.to('/themes/default/assets/js/plugins/fancytree/jquery.fancytree.dnd.js') }}"></script>
<script type='text/javascript'>
var templatecallback = function() {
	$("#navigation_tree_panel").fancytree({
		imagePath: "{{ URL.to('/themes/default/assets/icons/32x32') }}/",
		extensions: ["dnd"],
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
	    minExpandLevel: 2, // 1: root node is not collapsible
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
	    activate: function(e, data) {
	    	if (data.draggable)
	    		return; // prevent false hits
			var node = data.node;
			if(node.data.href){
				window.open(node.data.href, node.data.target);
			}
		},
		click: function(e, data) { // allow re-loads
			var node = data.node;
			if(node.isActive() && node.data.href){
				// TODO: data.tree.reactivate();
			}
		},
		dnd: {
	        preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
	        preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
	        autoExpandMS: 400,
	        dragStart: function(node, data) {
	        	// Defines whether this node is draggable or not
	        	// The top level project should have dnd disabled
				if (node.data.nodeType == 1) { 
					return false;
				}
	        	return true;
	        },
	        dragOver: function(node, data) {
	        },
	        dragEnter: function(node, data) {
	        	// Defines whether another node can be dragged on this node
	            // Return ['before', 'after'] to restrict available hitModes.
	            //  Any other return value will calc the hitMode from the cursor position.
	            // Prevent dropping a parent below another parent (only sort
	            // nodes under the same parent)
	            // if(node.parent !== data.otherNode.parent){
	            //   return false;
	            // }
	            // Don't allow dropping *over* a node (would create a child)
	            // return ["before", "after"];

	            var selectedNode = data.otherNode; // the selected node
				if (selectedNode.data.nodeType == 3) {
					// Test cases should not be dnd onto projects or test cases, only test suites
					if (node.data.nodeType != 2) {
						return false;
					}
					// Test cases should not be dnd onto its own parent
					if (node == selectedNode.parent) {
						return false;
					}
				}
				if (selectedNode.data.nodeType == 2) {
					// Test suites should not be dnd onto test cases
					if (node.data.nodeType == 3) {
						return false;
					}
					// Test suites should not be dnd onto its own parent
					if (node == selectedNode.parent) {
						return false;
					}
				}
				return "over";
	        },
	        dragDrop: function(node, data) {
	          	var selectedNode = data.otherNode; // the selected node
	          	var nodeId = '' + selectedNode.data.nodeType + '-' + selectedNode.data.nodeId;
	          	var ancestorId = '' + node.data.nodeType + '-' + node.data.nodeId;
	          	console.log('Update ' + nodeId + ' set ancestor = ' + ancestorId);
	          	$.ajax({
				  type: "POST",
				  url: url,
				  data: {nodeId: nodeId, ancestorId: ancestorId},
				  success: success,
				  dataType: dataType
				});
				selectedNode.moveTo(node, data.hitMode);
	        }
        }
	});
	$("#navigation_tree_panel").fancytree("getRootNode").visit(function(node){
        //node.setExpanded(true);
    });
	var tree = $("#navigation_tree_panel").fancytree("getTree");
	tree.setFocus(true);
}
</script>
{% endblock %}