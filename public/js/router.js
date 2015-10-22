// Filename: router.js
define([
  'jquery',
  'underscore',
  'backbone',
  'navigation',
  'views/home/HomeView',
  'views/breadcrumb/BreadcrumbView',
  'views/projects/ProjectsView',
  'views/projects/ProjectView',
  'views/projects/ConfirmDeleteProjectView',
  'views/projects/ViewProjectView'
], function(
  $,
  _,
  Backbone,
  Navigation,
  HomeView,
  BreadcrumbView,
  ProjectsView,
  ProjectView,
  ConfirmDeleteProjectView,
  ViewProjectView) {
  'use strict';

  var navigation = new Navigation();

  var ProjectsRouter = Backbone.Router.extend({
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
    },
    navigation: {
      prefix: 'Projects',
      pages: {
        'Projects.showProjects': {
          template: 'Projects'
        },
        'Projects.showProject': {
          template: _.template('Project <%= args["id"] %>'),
          parent: 'Projects.showProjects'
        }
      }
    }
  });

  var initialize = function() {
    var projectsRouter = new ProjectsRouter;

    projectsRouter.on('route:showProjects', function() {
      var projectsView = new ProjectsView();
      projectsView.render();

    });

    projectsRouter.on('route:showProject', function(id) {
      var projectView = new ProjectView({id: id});
      projectView.render();
    });

    projectsRouter.on('route:showConfirmDeleteProject', function(id) {
      var confirmDeleteProjectView = new ConfirmDeleteProjectView({id: id});
      confirmDeleteProjectView.render();
    });

    projectsRouter.on('route:viewProject', function(id) {
      var projectView = new ViewProjectView({id: id});
      projectView.render();
    });

    projectsRouter.on('route:showContributors', function () {
        // Like above, call render but know that this view has nested sub views which 
        // handle loading and displaying data from the GitHub API  
        var contributorsView = new ContributorsView();
    });

    projectsRouter.on('route:defaultAction', function (actions) {
       // We have no matching route, lets display the home page 
        var homeView = new HomeView();
        homeView.render();
    });

    navigation.appendRouter(projectsRouter);
    navigation.mapRouters();

    var breadcrumbView = new BreadcrumbView({navigation: navigation});

    Backbone.history.start();
  };
  return { 
    initialize: initialize
  };
});
