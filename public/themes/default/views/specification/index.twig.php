{% block content %}

<div class='page-header'>
	<h1>Test Specification</h1>
</div>
<div class='row'>
	<div class='span4' id='navigation_tree_panel'>
		<p>Navigation tree</p>
		{{ navigation_tree }}
	</div>
	<div class="span8" id="test_specification">
		<div class='pad_l pad_r' id='nodes_panel'>

			{% if node.node_type_id == 1 %} {# project #}
				{% include 'specification/project.html.twig' %}
			{% elseif node.node_type_id == 2 %} {# test suite #}
				{% include 'specification/testsuite.html.twig' %}
			{% elseif node.node_type_id == 3 %} {# test case #}
				{% include 'specification/testcase.html.twig' %}
			{% else %} {# main page #}
			<h4>Select a node in the navigation tree</h4>
			<p>Different forms or details about nodes will be displayed here.</p>
			{% endif %}
		</div>
	</div>
</div>

<script type='text/javascript'>
var templatecallback = function() {
	$("#navigation_tree_panel").fancytree({
		imagePath: "{{ twiggy_theme_url("icons/32x32") }}/",
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
        //node.setExpanded(true);
    });
	var tree = $("#navigation_tree_panel").fancytree("getTree");
	tree.setFocus(true);
}
</script>
{% endblock %}