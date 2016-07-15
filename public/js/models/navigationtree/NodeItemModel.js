define([
    'underscore',
    'backbone'
], function(_, Backbone) {

    var NodeItemModel = Backbone.Model.extend({

        defaults: {
            id: '',
            length: 0,
            node_id: 0,
            node_type_id: 0,
            created_at: '',
            updated_at: ''
        },

        initialize: function(options) {
            // Here ancestor and descendant will always point to the same node ID, with length=0
            this.id = options.node_id;
        }

    });

    return NodeItemModel;

});
