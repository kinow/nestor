define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel',
  'collections/projects/ProjectsCollection',
  'views/projects/ProjectsListView',
  'text!templates/projects/projectsTemplate.html'
], function($, _, Backbone, ProjectModel, ProjectsCollection, ProjectsListView, projectsTemplate){

  var ProjectsView = Backbone.View.extend({
    el: $("#page"),

    render: function() {
      $('.menu a').removeClass('active');
      $('.menu a[href="'+window.location.hash+'"]').addClass('active');

      this.$el.html(projectsTemplate);

      var projectsCollection = new ProjectsCollection();
      var projectsListView = new ProjectsListView({collection: projectsCollection}); 
    }

  });

  return ProjectsView;
  
});
