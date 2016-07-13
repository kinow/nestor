define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testplan/TestPlanModel',
    'collections/testplan/TestPlansCollection',
    'views/testruns/TestPlansListView',
    'text!templates/testruns/executeTestPlansTemplate.html'
], function($, _, Backbone, app, TestPlanModel, TestPlansCollection, TestPlansListView, executeTestPlansTemplate) {

    var ExecuteTestPlansView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'setProjectId', 'render');
            this.page = 1;
            this.projectId = 0;
            // Collections
            this.testPlansCollection = new TestPlansCollection();
            // Views
            this.testplansListView = new TestPlansListView();
            // For GC
            this.subviews = new Object();
            this.subviews.testplansListView = this.testplansListView;
        },

        setPage: function(page) {
            this.page = page;
        },

        setProjectId: function(projectId) {
            this.projectId = projectId;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');
            var self = this;

            this.$el.html(executeTestPlansTemplate);
            this.testPlansCollection.fetch({
                data: {
                    page: self.page,
                    project_id: self.projectId
                },
                success: function() {
                    self.testplansListView.render(self.testPlansCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch test plans");
                }
            });
        }

    });

    return ExecuteTestPlansView;

});