// position project
// TODO: improve docs
var position_project = function(select) {
	var form = $(select).parent('form');
	$(form).submit();
}

var get_children = function(node_id, node_type_id) {
	$('#nodes_panel').html('<h1>oi</h1>');
}

$(function() {
    $('#myTab a:first').tab('show');
    
    $('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});
	
    Backbone.emulateHTTP = true;
	// Models
	var Node = Backbone.Model.extend({
		defaults: {
			id: null, 
			node_id: null,
			node_type_id: null,
			parent_id: null, 
			display_name: null,
		}
	});
	// Collections
	var Nodes = Backbone.Collection.extend({
		model: Node,
		url: document.URL + '/nodes', 
	});
    // Views
	var NodeItem = Backbone.View.extend({
    	tagName: 'li',
    	className: 'node_item',
    });
    var NodeSubItem = Backbone.View.extend({
    	tagName: 'ul',
    	className: 'node_subitem',
    });
    var NavigationTree = Backbone.View.extend({
    	el: $('body'), 
    	initialize: function() {
    		this.nodes_panel = $('#nodes_panel');
    		_.each(this.model.models, function(node) {
    			console.log(node);
    			var node_item = new NodeItem({model: node});
    			$('#navigation_tree').append(node_item.render().el);
    		}, this);
    		this.render();
    	}, 
    	events: {
    		'click .expand_node': 'expand_node',
    	},
    	expand_node: function() {
    		alert('oi');
    	},
    	addNode: function(node) {
    		//alert(node);
    		//console.log(node);
    	}, 
    	render: function() {
    		var node_template = _.template($('#node_template').html(), {});
    		this.nodes_panel.html(node_template);
    	}
    });
    // Routes
    var NavigationRouter = Backbone.Router.extend({
    	routes: {
    		'*actions': 'defaultRoute'
    	}
    });
    
    var navigation_router = new NavigationRouter;
    navigation_router.on('route:defaultRoute', function(actions){
    	//alert(actions);
    });

    Backbone.history.start();
    
    var nodes = new Nodes(); // Initialize the collection
    nodes.fetch();
    var navigationTree = new NavigationTree({model: nodes});
});