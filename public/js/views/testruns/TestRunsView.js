define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testrun/TestRunModel',
    'collections/testruns/TestRunsCollection',
    'views/testruns/TestRunsListView',
    'text!templates/testruns/testRunsTemplate.html'
], function($, _, Backbone, app, TestRunModel, TestRunsCollection, TestRunsListView, testRunsTemplate) {

    var TestRuns = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'setTestPlanId', 'render');
            this.page = 1;
            this.testPlanId = 0;
            // Collections
            this.testRunsCollection = new TestRunsCollection({ test_plan_id: options.test_plan_id });
            // Views
            this.testRunsListView = new TestRunsListView();
            // For GC
            this.subviews = new Object();
            this.subviews.testRunsListView = this.testRunsListView;
        },

        setPage: function(page) {
            this.page = page;
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
            var compiledTemplate = _.template(testRunsTemplate, data);
            this.$el.html(compiledTemplate);
            this.testRunsCollection.fetch({
                data: {
                    page: self.page,
                    test_plan_id: self.testPlanId
                },
                success: function() {
                    self.testRunsListView.render(self.testRunsCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch executions");
                }
            });
        }

    });

    return TestRuns;

});