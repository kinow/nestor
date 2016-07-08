define([
    'jquery',
    'underscore',
    'backbone',
    'models/navigationtree/NodeItemModel'
], function($, _, Backbone, NodeItemModel) {
    var NavigationTreeCollection = Backbone.Collection.extend({
        
        model: NodeItemModel,

        initialize: function (options) {
            options || (options = {});
            this.projectId = options.projectId;
        },

        url: function () {
            return '/api/navigationtree/1-' + this.projectId;
        },

        setProjectId: function (projectId) {
            this.projectId = projectId;
        },

        parse: function (response) {
            return response;
        }

    });

    return NavigationTreeCollection;
});