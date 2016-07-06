define([
    'jquery',
    'underscore',
    'backbone',
    'models/testplan/TestPlanModel',
    'collections/testplan/TestPlansCollection',
    'views/testplans/TestPlansListView',
    'text!templates/testplans/testplansTemplate.html'
], function($, _, Backbone, TestPlanModel, TestPlansCollection, TestPlansListView, testplansTemplate) {

    var TestPlansView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'render');
            this.page = 1;
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

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/planning"]').parent().addClass('active');
            var self = this;

            this.$el.html(testplansTemplate);
            
            this.testPlansCollection.setPage(this.page);
            this.testPlansCollection.fetch({
                success: function() {
                    self.testplansListView.render(self.testPlansCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return TestPlansView;

});