// Filename: TestPlansListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    // Pull in the Collection module from above,
    'models/testplan/TestPlanModel',
    'text!templates/testplans/testplansListTemplate.html'
], function($, _, Backbone, TestPlanModel, testplansListTemplate) {
    var TestPlansListView = Backbone.View.extend({
        el: $("#testplans-list"),

        initialize: function(options) {
            var self = this;
            _.bindAll(this, 'render');
            this.collection.fetch({
                success: function() {
                    self.render();
                },
                error: function() {
                    throw new Error("Failed to fetch test plans");
                }
            });
            this.listenTo(this.collection, 'reset', this.render);
        },

        render: function() {
            var data = {
                testplans: this.collection.models,
                collection: this.collection,
                _: _
            };
            var compiledTemplate = _.template(testplansListTemplate, data);
            $("#testplans-list").html(compiledTemplate);
        }

    });
    return TestPlansListView;
});