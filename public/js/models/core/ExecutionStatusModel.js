define([
    'underscore',
    'backbone'
], function(_, Backbone) {

    var ExecutionStatusModel = Backbone.Model.extend({

        defaults: {
            id: 0,
            name: '',
            description: ''
        },

        initialize: function(options) {
        },

        urlRoot: '/api/executionstatuses'

    });

    return ExecutionStatusModel;

});