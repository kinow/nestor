{% block content %}
<div class='row'>
	{{ Form.open({'url': '/planning/' ~ testplan.id ~ '/addTestCases', 'method': 'POST', 'id': 'testplan_form'}) }}
		<div class='col-xs-4'>
			<div id='navigation_tree_panel'>
				<p>Navigation tree</p>
				{{ navigation_tree_html }}
			</div>
			<br/>
			{{ Form.submit('Save', {'class': "btn btn-primary"}) }}
			{{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
		</div>
	{{ Form.close() }}
	<div class="col-xs-8" id="test_specification">
		<div id='nodes_panel'>
			<h4>Check the test cases you would like to add to this test plan</h4>
		</div>
	</div>
</div>
<script type='text/javascript'>
var templatecallback = function() {
	$("form#testplan_form").submit(function() {
      // Render hidden <input> elements for active and selected nodes
      $("#navigation_tree_panel").fancytree("getTree").generateFormElements(true, false);
      var form = $("#fancytree_result_1");
      var children = form.children();
      for (var i = 0; i < children.length; ++i) {
      	  child = children[i];
      	  if (child.tagName == 'INPUT') {
      	  	  child.setAttribute('name', child.name + '[]');
      	  }
      }

      // console.log("POST data:\n" + jQuery.param($(this).serializeArray()));
      // return false to prevent submission of this sample
      return true;
    });

	$("#navigation_tree_panel").fancytree({
		imagePath: "{{ URL.to('/themes/default/assets/icons/32x32') }}/",
		extensions: [],
		activeVisible: true, // Make sure, active nodes are visible (expanded).
	    aria: false, // Enable WAI-ARIA support.
	    autoActivate: true, // Automatically activate a node when it is focused (using keys).
	    autoCollapse: false, // Automatically collapse all siblings, when a node is expanded.
	    autoScroll: false, // Automatically scroll nodes into visible area.
	    clickFolderMode: 2, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
	    checkbox: true, // Show checkboxes.
	    debugLevel: 1, // 0:quiet, 1:normal, 2:debug
	    disabled: false, // Disable control
	    generateIds: false, // Generate id attributes like <span id='fancytree-id-KEY'>
	    idPrefix: "ft_", // Used to generate node id´s like <span id='fancytree-id-<key>'>.
	    icons: true, // Display node icons.
	    keyboard: true, // Support keyboard navigation.
	    keyPathSeparator: "/", // Used by node.getKeyPath() and tree.loadKeyPath().
	    minExpandLevel: 2, // 1: root node is not collapsible
	    selectMode: 3, // 1:single, 2:multi, 3:multi-hier
	    tabbable: true, // Whole tree behaves as one single control
	});
	var rootNode = $("#navigation_tree_panel").fancytree("getRootNode");
	rootNode.visit(function(node){
        //node.setExpanded(true);
        //console.log(node);
    });
	var sortCmp = function(a, b) {
    	//console.log(a);
	    var x = a.data.nodeType + a.title.toLowerCase(),
	        y = b.data.nodeType + b.title.toLowerCase();
	    return x === y ? 0 : x > y ? 1 : -1;
	};
    rootNode.sortChildren(sortCmp, true);
	var tree = $("#navigation_tree_panel").fancytree("getTree");
	tree.setFocus(true);
}
</script>
{% endblock %}