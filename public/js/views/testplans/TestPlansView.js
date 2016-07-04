define([
    'jquery',
    'underscore',
    'backbone',
    'models/testplan/TestPlanModel',
    'collections/testplan/TestPlansCollection',
    'views/testplans/TestPlansListView',
    'text!templates/testplans/testplansTemplate.html'
], function($, _, Backbone, TestPlanModel, TestPlansCollection, TestPlansListView, testplansTemplate) {

    var ProjectsView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'render');
            this.page = 1;

            this.testPlansCollection = new TestPlansCollection();
            this.testPlansCollection.setPage(this.page);
            this.projectsListView = new TestPlansListView({
                collection: this.testPlansCollection
            });

            // For GC
            this.subviews = new Object();
            this.subviews.projectsListView = this.projectsListView;
        },

        setPage: function(page) {
            this.page = page;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/planning"]').parent().addClass('active');

            this.$el.html(testplansTemplate);
        }

    });

    return ProjectsView;

});