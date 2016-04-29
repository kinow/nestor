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
            this.page = 1;
        },

        setPage: function(page) {
            this.page = page;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');

            this.$el.html(projectsTemplate);
            var projectsCollection = new ProjectsCollection();
            projectsCollection.setPage(this.page);
            var projectsListView = new ProjectsListView({
                collection: projectsCollection
            });
            // FIXME: garbage collect this subview
        }

    });

    return ProjectsView;

});