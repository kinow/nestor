define([
    'jquery',
    'underscore',
    'backbone',
    'models/navigationtree/NodeItemModel'
], function($, _, Backbone, NodeItemModel) {
    var NavigationTreeCollection = Backbone.Collection.extend({
        model: NodeItemModel,
        models: [],

        initialize: function(options) {
            
        },

        setRootId: function(projectId) {
            this.projectId = projectId;
            var rootId = '1-' + projectId;
            this.rootId = rootId;
        },

        url: function() {
            return 'api/navigationtree/' + this.rootId;
        },

        parse: function(response) {
            var item = new NodeItemModel({
                node_id: this.rootId
            });
            return response;
        }

    });

    return NavigationTreeCollection;
});