/**
 * @desc backbone router for pushState page routing
 */

define([
  // Libraries
  'underscore',
  'backbone',
  'navigation',
  'parsley',
  // Base views
  'views/home/HomeView',
  'views/header/HeaderView',
  'views/breadcrumb/BreadcrumbView',
  // Auth views
  'views/auth/SignUpView',
  'views/auth/SignInView',
  // Projects views
  'views/projects/ProjectsView',
  'views/projects/NewProjectView',
  'views/projects/ProjectView',
  'views/projects/ConfirmDeleteProjectView',
  'views/projects/ViewProjectView',
  // Test Suite views
  'views/testsuites/NewTestSuiteView',
  'views/testsuites/TestSuiteView',
  'views/testsuites/ViewTestSuiteView'
], function(
  _,
  Backbone,
  Navigation,
  Parsley,

  HomeView,
  HeaderView,
  BreadcrumbView,

  SignUpView,
  SignInView,

  ProjectsView,
  NewProjectView,
  ProjectView,
  ConfirmDeleteProjectView,
  ViewProjectView,

  NewTestSuiteView,
  TestSuiteView,
  ViewTestSuiteView) {
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

  var AuthRouter = Backbone.Router.extend({
    routes: {
      'signup': 'signUp',
      'signin': 'signIn'
    },
    navigation: {
      prefix: 'Auth',
      pages: {
        'Auth.signUp': {
          template: 'Sign Up',
          parent: 'Base.defaultAction'
        },
        'Auth.signIn': {
          template: 'Sign In',
          parent: 'Base.defaultAction'
        }
      }
    }
  });

  var ProjectsRouter = Backbone.Router.extend({
    routes: {
      // Project routes
      'projects': 'showProjects',
      'projects/new': 'showAddProject',
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
        'Projects.showAddProject': {
          template: 'Add new Project',
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
      'projects/:projectId/testsuites/new': 'showAddTestSuite',
      'projects/:projectId/testsuites/:testsuiteId': 'showTestSuite',
      'projects/:projectId/testsuites/showConfirmDeleteTestSuite': 'showConfirmDeleteTestSuite',
      'projects/:projectId/testsuites/:testsuiteId/view': 'viewTestSuite'
    }
  });

  var initialize = function() {

    // --- common views ---
    var headerView = new HeaderView();
    headerView.render();
    var breadcrumbView = new BreadcrumbView({navigation: navigation});
    // --- end common views ---

    // --- base router ---
    var baseRouter = new BaseRouter();

    baseRouter.on('route:defaultAction', function (actions) {
       // We have no matching route, lets display the home page
        var homeView = new HomeView();
        homeView.render();
    });
    // --- end base router

    // --- auth router ---
    var authRouter = new AuthRouter();

    authRouter.on('route:signUp', function() {
      var signUpView = new SignUpView();
      signUpView.render();
    });

    authRouter.on('route:signIn', function() {
      var signInView = new SignInView();
      signInView.render();
    });
    // --- end auth router ---

    // --- projects router ---
    var projectsRouter = new ProjectsRouter();

    projectsRouter.on('route:showProjects', function() {
      var projectsView = new ProjectsView();
      projectsView.render();

    });

    projectsRouter.on('route:showAddProject', function() {
      var newProjectView = new NewProjectView();
      newProjectView.render();
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
      var projectView = new ViewProjectView({id: projectId});
      projectView.render();
      var newTestSuiteView = new NewTestSuiteView({projectId: projectId});
      newTestSuiteView.render();
    });

    testSuitesRouter.on('route:showTestSuite', function(projectId, testSuiteId) {
      var projectView = new ViewProjectView({id: projectId});
      projectView.render();
      var testSuiteView = new TestSuiteView({projectId: projectId, testSuiteId: testSuiteId});
      testSuiteView.render();
    });

    testSuitesRouter.on('route:showConfirmDeleteTestSuite', function(id) {
      var projectView = new ViewProjectView({id: projectId});
      projectView.render();
      var confirmDeleteTestSuiteView = new ConfirmDeleteTestSuiteView({id: id});
      confirmDeleteTestSuiteView.render();
    });

    testSuitesRouter.on('route:viewTestSuite', function(projectId, testsuiteId) {
      var projectView = new ViewProjectView({id: projectId});
      projectView.render();
      var testSuiteView = new ViewTestSuiteView({projectId: projectId, testSuiteId: testsuiteId});
      testSuiteView.render();
    });

    // --- end test suites router ---

    navigation.appendRouter(baseRouter);
    navigation.appendRouter(authRouter);
    navigation.appendRouter(projectsRouter);
    navigation.mapRouters();

    Backbone.history.start();
  };

  var AppRouter = {
    initialize: initialize,
    navigation: navigation
  };

  return AppRouter;
});
