//Backbone JS
Backbone.emulateHTTP = true;
// Models
var Node = Backbone.Model.extend({
	url: window.location.pathname + '/nodes', 
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
	initialize: function(options) {
		this.id = options.id;
		this.url = window.location.pathname + '/nodes/id/' + this.id; 
	}
});
// Views
var NodeItem = Backbone.View.extend({
	render: function() {
		this.template = _.template($('#node_item').html());
		var html = this.template(this.model.toJSON());
		this.setElement(html); // in order to avoid wrapper div
		return this;
	},
});
var NodeSubItem = Backbone.View.extend({
	render: function() {
		this.$el.append('<li>Sub</li>');
		return this;
	}, 
});
var NodeForm = Backbone.View.extend({
	initialize: function() {
		this.showForm = this.options.showForm;
	}, 
	render: function() {
		var node_type_id = this.model.get('node_type_id');
		if (node_type_id == 1) { // project
			this.template = _.template($('#node_project_form').html());
			var data = this.model.toJSON();
			data.show_form = this.showForm;
			var html = this.template(data);
			this.setElement(html);
		} else if (node_type_id == 2) {
			this.template = _.template($('#node_test_suite_form').html());
			var data = this.model.toJSON();
			data.show_form = this.showForm;
			var html = this.template(data);
			this.setElement(html);
		}
		return this;
	}
});
var NavigationTree = Backbone.View.extend({
	el: $('body'), 
	initialize: function() {
		this.nodes_panel = $('#nodes_panel');
		this.listenTo(this.model, 'reset', this.render);
	}, 
	render: function() {
		//var node_template = _.template($('#node_template').html(), {});
		//this.nodes_panel.html(node_template);
		_.each(this.model.models, function(node) {
			var node_item = new NodeItem({model: node});
			$('#navigation_tree').append(node_item.render().el);
		});
		return this;
	}, 
	events: {
		'click .expand_node': 'expand_node',
	},
	expand_node: function() {
	},
	addNode: function(node) {
		//alert(node);
		//console.log(node);
	}, 
});
// Routes
var NavigationRouter = Backbone.Router.extend({
	initialize: function(options) {
		this.id = options.id;
		this.nodes = new Nodes({id: this.id});
		this.navigationTree = new NavigationTree({
			model: this.nodes, 
		});
	},
	routes: {
		'': 'show', 
		'node/:id': 'showNode',
		'node/:id/show_form': 'showForm',
	},
	show: function () {
		this.nodes.fetch({reset:true});
	}, 
	showNode: function(id) {
		if (!this.nodes || this.nodes.models.length <= 0) {
			this.nodes.fetch({
				reset:true, 
				success: function(collection, data) {
					var node = collection.get(id);
		    		var nodeForm = new NodeForm({model: node});
		    		$('#nodes_panel').html(nodeForm.render().$el.html());
				}
			});
		} else {
			var node = this.nodes.get(id);
    		var nodeForm = new NodeForm({model: node});
    		$('#nodes_panel').html(nodeForm.render().$el.html());
		}
	},
	showForm: function(id) {
		if (!this.nodes || this.nodes.models.length <= 0) {
			this.nodes.fetch({
				reset:true, 
				success: function(collection, data) {
					var node = collection.get(id);
		    		var nodeForm = new NodeForm({model: node, showForm: true});
		    		$('#nodes_panel').html(nodeForm.render().$el.html());
				}
			});
		} else {
			var node = this.nodes.get(id);
    		var nodeForm = new NodeForm({model: node, showForm: true});
    		$('#nodes_panel').html(nodeForm.render().$el.html());
		}
	}
});