define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/ExecutionTypeModel'
], function($, _, Backbone, ExecutionTypeModel) {
    var ExecutionTypesCollection = Backbone.Collection.extend({

        model: ExecutionTypeModel,

        initialize: function(options) {

        },

        url: function() {
            return 'api/executiontypes/';
        }

    });

    return ExecutionTypesCollection;
});