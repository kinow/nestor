define([
    'jquery',
    'underscore',
    'backbone',
    'views/projects/NavigationTreeView',
    'views/projects/NodeItemView',
    'models/project/ProjectModel',
    'text!templates/projects/viewProjectTemplate.html'
], function($, _, Backbone, NavigationTreeView, NodeItemView, ProjectModel, viewProjectTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {
            this.model = new ProjectModel();

            this.navigationTreeView = new NavigationTreeView();
            this.nodeItemView = new NodeItemView();
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

            compiledTemplate = _.template(viewProjectTemplate, {});
            this.$el.html(compiledTemplate);
        },

        displayProject: function(projectId) {
            this.model.id = projectId;
            var self = this;
            project.fetch({
                success: function(data) {
                    var data = {
                        project: project,
                        _: _
                    };
                    var compiledTemplate = _.template(projectNodeItemTemplate, data);
                    self.$el.html(compiledTemplate);
                    self.projectAreaTemplate = projectAreaTemplate;
                    compiledTemplate = _.template(viewProjectTemplate, data);
                    $("#content-area").html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

        rendered: function() {
            return !$("#navigation-tree").length == 0;
        }

    });

    return ViewProjectView;

});