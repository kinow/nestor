// File: router.js
define([
    // Libraries
    'underscore',
    'backbone',
    'navigation',
    'parsley',
    // app
    'app',
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
], function(
    _,
    Backbone,
    Navigation,
    Parsley,
    app,
    HomeView,
    HeaderView,
    BreadcrumbView,
    SignUpView,
    SignInView,
    ProjectsView,
    NewProjectView,
    ProjectView,
    ConfirmDeleteProjectView,
    ViewProjectView) {

    'use strict';

    var navigation = new Navigation();

    /**
     * http://stackoverflow.com/questions/11671400/navigate-route-with-querystring
     */
    function parseQueryString(queryString) {
        var params = {};
        if (queryString) {
            _.each(
                _.map(decodeURI(queryString).split(/&/g), function(el, i) {
                    var aux = el.split('='),
                        o = {};
                    if (aux.length >= 1) {
                        var val = undefined;
                        if (aux.length == 2)
                            val = aux[1];
                        o[aux[0]] = val;
                    }
                    return o;
                }),
                function(o) {
                    _.extend(params, o);
                }
            );
        }
        return params;
    }

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
            'projects?*queryString': 'showProjects',
            'projects/new': 'showAddProject',
            'projects/:projectId': 'showProject',
            'projects/:projectId/confirmDelete': 'showConfirmDeleteProject',
            'projects/:projectId/view': 'viewProject',
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
                        var tpl = _.template('Edit Project <%= args[":projectId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'Projects.showProjects'
                },
                'Projects.showAddProject': {
                    template: 'Add new Project',
                    parent: 'Projects.showProjects'
                },
                'Projects.showConfirmDeleteProject': {
                    template: function(args) {
                        var tpl = _.template('Delete Project <%= args[":projectId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'Projects.showProjects'
                },
                'Projects.viewProject': {
                    template: function(args) {
                        var tpl = _.template('View Project <%= args[":projectId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'Projects.showProjects'
                }
            }
        }
    });

    var TestSuitesRouter = Backbone.Router.extend({
        routes: {
            'projects/:projectId/testsuites/new': 'showAddTestSuite',
            'projects/:projectId/testsuites/new?*queryString': 'showAddTestSuite',
            'projects/:projectId/testsuites/:testsuiteId/view': 'viewTestSuite',
            'projects/:projectId/testsuites/:testsuiteId': 'showTestSuite',
            'projects/:projectId/testsuites/showConfirmDeleteTestSuite': 'showConfirmDeleteTestSuite'
        },
        navigation: {
            prefix: 'TestSuites',
            pages: {
                'TestSuites.showAddTestSuite': {
                    template: 'Add new Test Suite',
                    parent: 'Projects.viewProject'
                },
                'TestSuites.viewTestSuite': {
                    template: function(args) {
                        var tpl = _.template('View Test Suite <%= args[":testsuiteId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'Projects.viewProject'
                },
                'TestSuites.showTestSuite': {
                    template: function(args) {
                        var tpl = _.template('Edit Test Suite <%= args[":testsuiteId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'Projects.viewProject'
                }
            }
        }
    });

    var initialize = function() {

        // --- common views ---
        if (!app.headerView) {
            app.headerView = new HeaderView();
            app.headerView.render();
        }

        if (!app.breadcrumbView) {
            app.breadcrumbView = new BreadcrumbView({
                navigation: navigation
            });
            // render happens after breadcrumbView has calculated its breadcrumbs, within itself it calls render()
        }
        // --- end common views ---

        // --- base router ---
        var baseRouter = new BaseRouter();

        baseRouter.on('route:defaultAction', function(actions) {
            // We have no matching route, lets display the home page
            if (!app.homeView) {
                app.homeView = new HomeView();
            }
            app.showView(app.homeView);
        });
        // --- end base router

        // --- auth router ---
        var authRouter = new AuthRouter();

        authRouter.on('route:signUp', function() {
            if (!app.signUpView) {
                app.signUpView = new SignUpView();
            }
            app.showView(app.signUpView);
        });

        authRouter.on('route:signIn', function() {
            if (!app.signInView) {
                app.signInView = new SignInView();
            }
            app.showView(app.signInView);
        });
        // --- end auth router ---

        // --- projects router ---
        var projectsRouter = new ProjectsRouter();

        projectsRouter.on('route:showProjects', function(queryString) {
            var params = parseQueryString(queryString);
            var page = 1;
            if (typeof(params.page) != "undefined") {
                page = params.page;
            }
            if (!app.projectsView) {
                app.projectsView = new ProjectsView();
            }
            app.projectsView.setPage(page);
            app.showView(app.projectsView, {
                requiresAuth: true
            });
        });

        projectsRouter.on('route:showAddProject', function() {
            if (!app.newProjectView) {
                app.newProjectView = new NewProjectView();
            }
            app.showView(app.newProjectView, {
                requiresAuth: true
            });
        });

        projectsRouter.on('route:showProject', function(id) {
            if (!app.projectView) {
                app.projectView = new ProjectView();
            }
            app.projectView.model.id = id;
            app.showView(app.projectView, {
                requiresAuth: true
            });
        });

        projectsRouter.on('route:showConfirmDeleteProject', function(id) {
            if (!app.confirmDeleteProjectView) {
                app.confirmDeleteProjectView = new ConfirmDeleteProjectView();
            }
            app.confirmDeleteProjectView.model.id = id;
            app.showView(app.confirmDeleteProjectView, {
                requiresAuth: true
            });
        });

        // --- displayed as specification screen ---

        projectsRouter.on('route:viewProject', function(id) {
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView();
            }
            app.viewProjectView.setProjectId(id);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayProject();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayProject
                });
            }
        });

        projectsRouter.on('route:showContributors', function() {
            // Like above, call render but know that this view has nested sub views which
            // handle loading and displaying data from the GitHub API
            var contributorsView = new ContributorsView();
        });
        // --- end projects router ---

        // --- test suites router ---
        var testSuitesRouter = new TestSuitesRouter();

        testSuitesRouter.on('route:showAddTestSuite', function(projectId, queryString) {
            var params = parseQueryString(queryString);
            var parentId = 0;
            // the parent **must** be a test suite id, not a project id
            if (typeof(params.parent) != "undefined") {
                parentId = parseInt(params.parent);
            }
            if (!app.viewProjectView) {
                console.log('Creating view project view');
                app.viewProjectView = new ViewProjectView();
            }
            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.parentId = parentId;
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                console.log('Re-using existing view project view. Displaying new test suite view');
                app.viewProjectView.displayNewTestSuite();
            } else {
                console.log('Displaying new test suite view');
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayNewTestSuite
                });
            }
        });

        testSuitesRouter.on('route:showTestSuite', function(projectId, testSuiteId) {
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView();
            }
            
            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.testSuiteId = testSuiteId;
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayShowTestSuite();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayShowTestSuite
                });
            }
        });

        testSuitesRouter.on('route:showConfirmDeleteTestSuite', function(id) {
            var projectView = new ViewProjectView();
            projectView.render();
            var confirmDeleteTestSuiteView = new ConfirmDeleteTestSuiteView({
                id: id
            });
            confirmDeleteTestSuiteView.render();
        });

        testSuitesRouter.on('route:viewTestSuite', function(projectId, testSuiteId) {
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView();
            }
            
            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.testSuiteId = testSuiteId;
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayTestSuite();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayTestSuite
                });
            }
        });

        // --- end test suites router ---

        navigation.appendRouter(baseRouter);
        navigation.appendRouter(authRouter);
        navigation.appendRouter(projectsRouter);
        navigation.appendRouter(testSuitesRouter);
        navigation.mapRouters();

        Backbone.history.start();
    };

    var AppRouter = {
        // router initialization function
        initialize: initialize,
        // for breadcrumbs
        navigation: navigation,
        // routes
        BaseRouter: BaseRouter,
        AuthRouter: AuthRouter,
        ProjectsRouter: ProjectsRouter,
        TestSuitesRouter: TestSuitesRouter
    };

    return AppRouter;
});