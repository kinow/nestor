define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'collections/project/ProjectsCollection',
    'views/projects/ProjectsListView',
    'text!templates/projects/projectsTemplate.html'
], function($, _, Backbone, ProjectModel, ProjectsCollection, ProjectsListView, projectsTemplate) {

    var ProjectsView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'setPage', 'render');
            this.page = 1;
            // Collections
            this.projectsCollection = new ProjectsCollection();
            // Views
            this.projectsListView = new ProjectsListView();
            // For GC
            this.subviews = new Object();
            this.subviews.projectsListView = this.projectsListView;
        },

        setPage: function(page) {
            this.page = page;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            var self = this;

            this.$el.html(projectsTemplate);
            
            this.projectsCollection.setPage(this.page);
            this.projectsCollection.fetch({
                success: function() {
                    self.projectsListView.render(self.projectsCollection);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return ProjectsView;

});