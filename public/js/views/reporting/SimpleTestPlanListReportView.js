define([
    'jquery',
    'underscore',
    'backbone',
    'collections/testplan/TestPlansCollection',
    'views/reporting/SimpleTestPlanListReportListView',
    'text!templates/reporting/simpleTestPlanReportTemplate.html'
], function($, _, Backbone, TestPlansCollection, SimpleTestPlanListReportListView, simpleTestPlanReportTemplate) {

    var SimpleTestPlanListReportView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'setPage', 'setProjectId');
            this.projectId = options.projectId;
            this.page = 1;
            // Collections
            this.testPlansCollection = new TestPlansCollection();
            // Views
            this.simpleTestPlanListReportListView = new SimpleTestPlanListReportListView();
            // For GC
            this.subviews = new Object();
            this.subviews.simpleTestPlanListReportListView = this.simpleTestPlanListReportListView;
        },

        render: function() {
            $('.item').removeClass('active');
            var self = this;

            this.$el.html(simpleTestPlanReportTemplate);
            this.testPlansCollection.fetch({
                data: {
                    page: self.page,
                    project_id: self.projectId
                },
                success: function() {
                    self.simpleTestPlanListReportListView.render(self.testPlansCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch test plans");
                }
            });
        },

        setPage: function(page) {
            this.page = page;
        },

        setProjectId: function(projectId) {
            this.projectId = projectId;
        },

    });

    return SimpleTestPlanListReportView;

});
