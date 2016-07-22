define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/ExecutionStatusModel'
], function($, _, Backbone, ExecutionStatusModel) {
    var ExecutionStatusesCollection = Backbone.Collection.extend({

        model: ExecutionStatusModel,

        url: 'api/executionstatuses/',

        parse: function(response) {
            return response ? response.execution_statuses : [];
        }

    });

    return ExecutionStatusesCollection;
});