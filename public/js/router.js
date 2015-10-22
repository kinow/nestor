// Filename: router.js
define([
  'jquery',
  'underscore',
  'backbone',
  'views/home/HomeView',
  'views/projects/ProjectsView',
  'views/projects/ProjectView',
  'views/projects/ConfirmDeleteProjectView',
  'views/projects/ViewProjectView'
], function(
  $,
  _,
  Backbone,
  HomeView,
  ProjectsView,
  ProjectView,
  ConfirmDeleteProjectView,
  ViewProjectView) {
  'use strict';

  var AppRouter = Backbone.Router.extend({
    routes: {
      // Project routes
      'projects': 'showProjects',
      'projects/:id': 'showProject',
      'projects/:id/confirmDelete': 'showConfirmDeleteProject',
      'projects/:id/view': 'viewProject',
      // User routes
      'users': 'showContributors',
      // Default
      '*actions': 'defaultAction'
    }
  });

  var initialize = function(){
    var app_router = new AppRouter;

    app_router.on('route:showProjects', function() {
      var projectsView = new ProjectsView();
      projectsView.render();

    });

    app_router.on('route:showProject', function(id) {
      var projectView = new ProjectView({id: id});
      projectView.render();
    });

    app_router.on('route:showConfirmDeleteProject', function(id) {
      var confirmDeleteProjectView = new ConfirmDeleteProjectView({id: id});
      confirmDeleteProjectView.render();
    });

    app_router.on('route:viewProject', function(id) {
      var projectView = new ViewProjectView({id: id});
      projectView.render();
    });

    app_router.on('route:showContributors', function () {
        // Like above, call render but know that this view has nested sub views which 
        // handle loading and displaying data from the GitHub API  
        var contributorsView = new ContributorsView();
    });

    app_router.on('route:defaultAction', function (actions) {
       // We have no matching route, lets display the home page 
        var homeView = new HomeView();
        homeView.render();
    });

    // Unlike the above, we don't call render on this view as it will handle
    // the render call internally after it loads data. Further more we load it
    // outside of an on-route function to have it loaded no matter which page is
    // loaded initially.
    //var footerView = new FooterView();

    Backbone.history.start();
  };
  return { 
    initialize: initialize
  };
});
