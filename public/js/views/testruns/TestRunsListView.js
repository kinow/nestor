// Filename: ExecutionsListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/testruns/testRunsListTemplate.html'
], function($, _, Backbone, testRunsListTemplate) {
    var ExecutionsListView = Backbone.View.extend({
        el: $("#testruns-list"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        render: function(collection) {
            var data = {
                testruns: collection.models,
                collection: collection,
                _: _
            };
            var compiledTemplate = _.template(testRunsListTemplate, data);
            $("#testruns-list").html(compiledTemplate);
        }

    });
    return ExecutionsListView;
});