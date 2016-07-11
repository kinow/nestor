define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testplan/TestPlanModel',
    'collections/testplan/TestPlansCollection',
    'views/testplans/TestPlansListView',
    'text!templates/executions/executionsTemplate.html'
], function($, _, Backbone, app, TestPlanModel, TestPlansCollection, TestPlansListView, executionsTemplate) {

    var ExecutionsView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'setProjectId', 'setTestPlanId', 'render');
            this.page = 1;
            this.projectId = 0;
            this.testPlanId = 0;
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

        setTestPlanId: function(testPlanId) {
            this.testPlanId = testPlanId;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');
            var self = this;

            var data = {
                test_plan_id: this.testPlanId,
                _: _
            };
            var compiledTemplate = _.template(executionsTemplate, data);
            this.$el.html(compiledTemplate);
            this.testPlansCollection.fetch({
                data: {
                    page: self.page,
                    test_plan_id: self.testPlanId
                },
                success: function() {
                    self.testplansListView.render(self.testPlansCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return ExecutionsView;

});