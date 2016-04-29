// Filename: ProjectsListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    // Pull in the Collection module from above,
    'models/project/ProjectModel',
    'text!templates/projects/projectsListTemplate.html'
], function($, _, Backbone, ProjectModel, projectsListTemplate) {
    var ProjectListView = Backbone.View.extend({
        el: $("#projects-list"),

        initialize: function(options) {
            var self = this;
            this.collection.fetch({
                success: function() {
                    self.render();
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
            this.listenTo(this.collection, 'reset', this.render);
        },

        render: function() {
            var data = {
                projects: this.collection.models,
                collection: this.collection,
                _: _
            };
            var compiledTemplate = _.template(projectsListTemplate, data);
            $("#projects-list").html(compiledTemplate);
        }

    });
    return ProjectListView;
});