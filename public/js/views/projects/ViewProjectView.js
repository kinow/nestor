define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel',
  'text!templates/projects/viewProjectTemplate.html'
], function($, _, Backbone, ProjectModel, viewProjectTemplate){

  var ViewProjectView = Backbone.View.extend({
    el: $("#page"),

    events: {
    },

    initialize: function (options) {
      this.id = options.id;
    },

    render: function() {
      if ($("#project_tree").length == 0) {
        $('.menu a').removeClass('active');
        $('.menu a[href="#/projects"]').addClass('active');
        var project = new ProjectModel({id: this.id});
        var self = this;
        project.fetch({
          success: function () {
            var data = {
              project: project,
              _: _
            }
            var compiledTemplate = _.template( viewProjectTemplate, data );
            self.$el.html(compiledTemplate);
          },
          error: function() {
            throw new Error("Failed to fetch project");
          }
        });
      } else {
        console.log("Already CREATED!!!");
      }
    },

  });

  return ViewProjectView;
  
});
