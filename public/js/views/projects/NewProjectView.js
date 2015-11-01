define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel',
  'collections/projects/ProjectsCollection',
  'views/projects/ProjectsListView',
  'text!templates/projects/newTestSuiteTemplate.html'
], function($, _, Backbone, ProjectModel, ProjectsCollection, ProjectsListView, newTestSuiteTemplate){

  var NewProjectView = Backbone.View.extend({
    el: $("#page"),

    render: function() {
      $('.menu a').removeClass('active');
      $('.menu a[href="#/projects"]').addClass('active');

      this.$el.html(newTestSuiteTemplate);

      var projectsCollection = new ProjectsCollection();
      var projectsListView = new ProjectsListView({collection: projectsCollection}); 
    }

  });

  return NewProjectView;
  
});
