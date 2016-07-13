// Filename: ExecutionsListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/testruns/testRunsListTemplate.html'
], function($, _, Backbone, executionsListTemplate) {
    var ExecutionsListView = Backbone.View.extend({
        el: $("#executions-list"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        render: function(collection) {
            var data = {
                executions: collection.models,
                collection: collection,
                _: _
            };
            var compiledTemplate = _.template(executionsListTemplate, data);
            $("#executions-list").html(compiledTemplate);
        }

    });
    return ExecutionsListView;
});