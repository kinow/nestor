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

        setPage: function(page) {
            this.page = page;
        },

        url: function() {
            return 'api/navigationtree/' + this.rootId;
        },

        fetchSuccess: function(collection, response) {
            this.models = collection.models;
        },

        fetchError: function(collection, response) {
            throw new Error("Navigation Tree fetch error");
        }

    });

    return NavigationTreeCollection;
});