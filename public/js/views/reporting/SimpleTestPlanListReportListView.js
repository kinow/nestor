define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/reporting/simpleTestPlanReportListTemplate.html'
], function($, _, Backbone, simpleTestPlanReportListTemplate) {
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
            var compiledTemplate = _.template(simpleTestPlanReportListTemplate, data);
            $("#testplans-list").html(compiledTemplate);
        }

    });
    return TestPlansListView;
});