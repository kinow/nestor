define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/ExecutionTypeModel'
], function($, _, Backbone, ExecutionTypeModel) {
    var ExecutionTypesCollection = Backbone.Collection.extend({

        model: ExecutionTypeModel,

        url: 'api/executiontypes/',

        parse: function(response) {
            return response ? response.execution_types : [];
        }

    });

    return ExecutionTypesCollection;
});