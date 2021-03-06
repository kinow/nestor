// Filename: TestPlansListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/testplans/testplansListTemplate.html'
], function($, _, Backbone, testplansListTemplate) {
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
            var compiledTemplate = _.template(testplansListTemplate, data);
            $("#testplans-list").html(compiledTemplate);
        }

    });
    return TestPlansListView;
});