define([
    'underscore',
    'backbone'
], function(_, Backbone) {

    var ExecutionTypeModel = Backbone.Model.extend({

        defaults: {
            id: 0,
            name: '',
            description: ''
        },

        initialize: function(options) {
            _.bindAll(this, 'url');
        },

        url: function() {
            return '/api/executiontypes';
        }

    });

    return ExecutionTypeModel;

});