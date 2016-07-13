// Filename: TestPlansListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/testruns/testPlansListTemplate.html'
], function($, _, Backbone, testPlansListTemplate) {
    var TestPlansListView = Backbone.View.extend({
        el: $("#testplans-list"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        render: function(collection) {
            var data = {
                testplans: collection.models,
                collection: collection,
                _: _
            };
            var compiledTemplate = _.template(testPlansListTemplate, data);
            $("#testplans-list").html(compiledTemplate);
        }

    });
    return TestPlansListView;
});