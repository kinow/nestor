define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel',
  'text!templates/projects/projectTemplate.html'
], function($, _, Backbone, ProjectModel, projectTemplate){

  var ProjectView = Backbone.View.extend({
    el: $("#page"),

    initialize: function (options) {
      this.id = options.id;
    },

    render: function() {
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
          var compiledTemplate = _.template( projectTemplate, data );
          self.$el.html(compiledTemplate);
        },
        error: function() {
          throw new Error("Failed to fetch project");
        }
      });
    }

  });

  return ProjectView;
  
});
