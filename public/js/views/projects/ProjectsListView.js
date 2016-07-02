// Filename: ProjectsListView.js
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/projects/projectsListTemplate.html'
], function($, _, Backbone, projectsListTemplate) {
    var ProjectsListView = Backbone.View.extend({
        el: $("#projects-list"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        render: function(collection) {
            var data = {
                projects: collection.models,
                collection: collection,
                _: _
            };
            var compiledTemplate = _.template(projectsListTemplate, data);
            $("#projects-list").html(compiledTemplate);
        }

    });
    return ProjectsListView;
});