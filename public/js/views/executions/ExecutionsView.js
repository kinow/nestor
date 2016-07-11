define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/execution/ExecutionModel',
    'collections/executions/ExecutionsCollection',
    'views/executions/ExecutionsListView',
    'text!templates/executions/executionsTemplate.html'
], function($, _, Backbone, app, ExecutionModel, ExecutionsCollection, ExecutionsListView, executionsTemplate) {

    var ExecutionsView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'setTestPlanId', 'render');
            this.page = 1;
            this.testPlanId = 0;
            // Collections
            this.executionsCollection = new ExecutionsCollection();
            // Views
            this.executionsListView = new ExecutionsListView();
            // For GC
            this.subviews = new Object();
            this.subviews.executionsListView = this.executionsListView;
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
            var compiledTemplate = _.template(executionsTemplate, data);
            this.$el.html(compiledTemplate);
            this.executionsCollection.fetch({
                data: {
                    page: self.page,
                    test_plan_id: self.testPlanId
                },
                success: function() {
                    self.executionsListView.render(self.executionsCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch executions");
                }
            });
        }

    });

    return ExecutionsView;

});