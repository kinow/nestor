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
            this.page = 1;
        },

        setPage: function(page) {
            this.page = page;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/planning"]').parent().addClass('active');

            this.$el.html(testplansTemplate);
            var testPlansCollection = new TestPlansCollection();
            testPlansCollection.setPage(this.page);
            var projectsListView = new TestPlansListView({
                collection: testPlansCollection
            });
            // FIXME: garbage collect this subview
        }

    });

    return ProjectsView;

});