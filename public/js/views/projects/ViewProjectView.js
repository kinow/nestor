define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'text!templates/projects/projectAreaTemplate.html',
    'text!templates/projects/viewProjectTemplate.html'
], function($, _, Backbone, ProjectModel, projectAreaTemplate, viewProjectTemplate) {

    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {},

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

            if (!this.rendered()) {
                var project = new ProjectModel({
                    id: this.id
                });
                var self = this;
                project.fetch({
                    success: function(data) {
                        var data = {
                            project: project,
                            _: _
                        };
                        // FIXME: wrong code here...
                        var compiledTemplate = _.template(projectAreaTemplate, data);
                        self.$el.html(compiledTemplate);
                        compiledTemplate = _.template(viewProjectTemplate, data);
                        $("#content-area").html(compiledTemplate);
                    },
                    error: function() {
                        throw new Error("Failed to fetch project");
                    }
                });
            } else {
                // render only project data in content-area
                var project = new ProjectModel({
                    id: this.id
                });
                var self = this;
                project.fetch({
                    success: function() {
                        var data = {
                            project: project,
                            _: _
                        }
                        var compiledTemplate = _.template(viewProjectTemplate, data);
                        $("#content-area").html(compiledTemplate);
                    },
                    error: function() {
                        throw new Error("Failed to fetch project");
                    }
                });
            }
        },

        rendered: function() {
            return !$("#project_tree").length == 0;
        }

    });

    return ViewProjectView;

});