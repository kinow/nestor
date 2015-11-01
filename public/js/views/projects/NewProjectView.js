define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel',
  'collections/projects/ProjectsCollection',
  'views/projects/ProjectsListView',
  'text!templates/projects/newProjectTemplate.html'
], function($, _, Backbone, ProjectModel, ProjectsCollection, ProjectsListView, newProjectTemplate){

  var NewProjectView = Backbone.View.extend({
    el: $("#page"),

    render: function() {
      $('.menu a').removeClass('active');
      $('.menu a[href="#/projects"]').addClass('active');

      this.$el.html(newProjectTemplate);

      var projectsCollection = new ProjectsCollection();
      var projectsListView = new ProjectsListView({collection: projectsCollection}); 
    }

  });

  return NewProjectView;
  
});
