define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'collections/testplan/TestPlansCollection',
    'views/testplans/TestPlansListView',
    'text!templates/testplans/testplansTemplate.html'
], function($, _, Backbone, app, TestPlansCollection, TestPlansListView, testplansTemplate) {

    var TestPlansView = Backbone.View.extend({
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
            $('.item a[href="#/planning"]').parent().addClass('active');
            var self = this;

            this.$el.html(testplansTemplate);
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

    return TestPlansView;

});
