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
        // models: [],

        // initialize: function(options) {
        //     this.rootId = 0;
        // },

        // setRootId: function(projectId) {
        //     this.projectId = projectId;
        //     var rootId = '1-' + projectId;
        //     this.rootId = rootId;
        // },

        // url: function() {
        //     return 'api/navigationtree/' + this.rootId;
        // },

        // parse: function(response) {
        //     // TODO: discard?
        //     var item = new NodeItemModel({
        //         node_id: this.rootId
        //     });
        //     return response;
        // }

    });

    return NavigationTreeCollection;
});