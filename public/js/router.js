// Filename: router.js
define([
  'jquery',
  'underscore',
  'backbone',
  'navigation',
  // Base views
  'views/home/HomeView',
  'views/breadcrumb/BreadcrumbView',
  // Projects views
  'views/projects/ProjectsView',
  'views/projects/ProjectView',
  'views/projects/ConfirmDeleteProjectView',
  'views/projects/ViewProjectView',
  // Test Suite views
  'views/testsuites/NewTestSuiteView'
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
  ViewProjectView,
  NewTestSuiteView) {
  'use strict';

  var navigation = new Navigation();

  var BaseRouter = Backbone.Router.extend({
    routes: {
      'home': 'defaultAction',
      '*actions': 'defaultAction'
    },
    navigation: {
      prefix: 'Base',
      pages: {
        'Base.defaultAction': {
          template: 'Home'
        }
      }
    }
  });

  var ProjectsRouter = Backbone.Router.extend({
    routes: {
      // Project routes
      'projects': 'showProjects',
      'projects/:id': 'showProject',
      'projects/:id/confirmDelete': 'showConfirmDeleteProject',
      'projects/:id/view': 'viewProject',
      // User routes
      'users': 'showContributors'
    },
    navigation: {
      prefix: 'Projects',
      pages: {
        'Projects.showProjects': {
          template: 'Projects',
          parent: 'Base.defaultAction'
        },
        'Projects.showProject': {
          template: function(args) {
            var tpl = _.template('Edit Project <%= args[":id"] %>');
            return tpl({args: args});
          },
          parent: 'Projects.showProjects'
        },
        'Projects.showConfirmDeleteProject': {
          template: function(args) {
            var tpl = _.template('Delete Project <%= args[":id"] %>');
            return tpl({args: args});
          },
          parent: 'Projects.showProjects'
        },
         'Projects.viewProject': {
          template: function(args) {
            var tpl = _.template('View Project <%= args[":id"] %>');
            return tpl({args: args});
          },
          parent: 'Projects.showProjects'
        }
      }
    }
  });

  var TestSuitesRouter = Backbone.Router.extend({
    routes: {
      'projects/:projectId/testsuites/new': 'showAddTestSuite'
    }
  });

  var initialize = function() {

    // --- base router ---
    var baseRouter = new BaseRouter();

    baseRouter.on('route:defaultAction', function (actions) {
       // We have no matching route, lets display the home page 
        var homeView = new HomeView();
        homeView.render();
    });
    // --- end base router

    // --- projects router ---
    var projectsRouter = new ProjectsRouter();

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
    // --- end projects router ---

    // --- test suites router ---
    var testSuitesRouter = new TestSuitesRouter();
    testSuitesRouter.on('route:showAddTestSuite', function(projectId) {
      var newTestSuiteView = new NewTestSuiteView({projectId: projectId});
      newTestSuiteView.render();
    });
    // --- end test suites router ---

    navigation.appendRouter(baseRouter);
    navigation.appendRouter(projectsRouter);
    navigation.mapRouters();

    var breadcrumbView = new BreadcrumbView({navigation: navigation});

    Backbone.history.start();
  };
  return { 
    initialize: initialize
  };
});
