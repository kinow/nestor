// File: router.js
define([
    // Libraries
    'underscore',
    'backbone',
    'navigation',
    // app
    'app',
    // Collections
    'collections/project/ProjectsCollection',
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
    // This is the view displayed when you browse the navigation tree. It has methods to display
    // other nested views, and garbage collect them.
    'views/projects/ViewProjectView',
    // Test plans views
    'views/testplans/TestPlansView',
    'views/testplans/NewTestPlanView',
    'views/testplans/TestPlanView',
    'views/testplans/ConfirmDeleteTestPlanView',
    // This is the view displayed when you browse the navigation tree in the planning
    // view.
    'views/testplans/ViewTestPlanView',
    // Test Runs views
    'views/testruns/ExecuteTestPlansView',
    'views/testruns/TestRunsView',
    'views/testruns/NewTestRunView'
], function(
    _,
    Backbone,
    Navigation,
    app,
    ProjectsCollection,
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
    TestPlansView,
    NewTestPlanView,
    TestPlanView,
    ConfirmDeleteTestPlanView,
    ViewTestPlanView,
    ExecuteTestPlansView,
    TestRunsView,
    NewTestRunView) {

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

    function checkIfProjectIsSet() {
        var projectId = app.session.get('project_id');
        if (typeof projectId === typeof undefined || projectId <= 0) {
            app.showAlert('Internal error', 'Invalid request', 'error');
            Backbone.history.navigate("#/", {
                trigger: false
            });
            // TODO: global try catch?
            throw new Error('Invalid request. Aborting program execution');
        }
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
            //'projects/:projectId/view': 'viewProject',
            'specification': 'viewProject',
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
                    template: 'Specification',
                    parent: 'Base.defaultAction'
                }
            }
        }
    });

    var TestSuitesRouter = Backbone.Router.extend({
        routes: {
            // project
            'projects/:projectId/testsuites/new': 'showAddTestSuite',
            'projects/:projectId/testsuites/new?*queryString': 'showAddTestSuite',
            'projects/:projectId/testsuites/:testsuiteId/view': 'viewTestSuite',
            'projects/:projectId/testsuites/:testsuiteId': 'showTestSuite',
            'projects/:projectId/testsuites/:testsuiteId/confirmDelete': 'showConfirmDeleteTestSuite',
            // test plan
            'testplans/:testPlanId/testsuites/:testsuiteId/view': 'viewPlanTestSuite'
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
                },
                'TestSuites.viewPlanTestSuite': {
                    template: function(args) {
                        var tpl = _.template('View Test Suite <%= args[":testsuiteId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestPlans.showTestPlans'
                },
            }
        }
    });

    var TestCasesRouter = Backbone.Router.extend({
        routes: {
            // project
            'projects/:projectId/testsuites/:testsuiteId/testcases/new': 'showAddTestCase',
            'projects/:projectId/testsuites/:testsuiteId/testcases/new?*queryString': 'showAddTestCase',
            'projects/:projectId/testsuites/:testsuiteId/testcases/:testcaseId/view': 'viewTestCase',
            'projects/:projectId/testsuites/:testsuiteId/testcases/:testcaseId': 'showTestCase',
            'projects/:projectId/testsuites/:testsuiteId/testcases/:testcaseId/confirmDelete': 'showConfirmDeleteTestCase',
            // test plan
            'testplans/:testPlanId/testsuites/:testsuiteId/testcases/:testcaseId/view': 'viewPlanTestCase'
        },
        navigation: {
            prefix: 'TestCases',
            pages: {
                'TestCases.showAddTestCase': {
                    template: 'Add new Test Case',
                    parent: 'TestSuites.viewTestSuite'
                },
                'TestCases.viewTestCase': {
                    template: function(args) {
                        var tpl = _.template('View Test Case <%= args[":testcaseId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestSuites.viewTestSuite'
                },
                'TestCases.showTestCase': {
                    template: function(args) {
                        var tpl = _.template('Edit Test Case <%= args[":testcaseId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestSuites.viewTestSuite'
                },
                'TestCases.viewPlanTestCase': {
                    template: function(args) {
                        var tpl = _.template('View Test Case <%= args[":testcaseId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestPlans.showTestPlans'
                },
            }
        }
    });

    var TestPlansRouter = Backbone.Router.extend({
        routes: {
            // Test Plan routes
            'planning': 'showTestPlans',
            'planning?*queryString': 'showTestPlans',
            'testplans': 'showTestPlans',
            'testplans?*queryString': 'showTestPlans',
            'testplans/new': 'showAddTestPlan',
            'testplans/:testPlanId': 'showTestPlan',
            'testplans/:testPlanId/confirmDelete': 'showConfirmDeleteTestPlan',
            'testplans/:testPlanId/view': 'viewTestPlan'
        },
        navigation: {
            prefix: 'TestPlans',
            pages: {
                'TestPlans.showTestPlans': {
                    template: 'Planning',
                    parent: 'Base.defaultAction'
                },
                'TestPlans.showAddTestPlan': {
                    template: 'Add new Test Plan',
                    parent: 'TestPlans.showTestPlans'
                },
                'TestPlans.showTestPlan': {
                    template: function(args) {
                        var tpl = _.template('Edit Test Plan <%= args[":testPlanId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestPlans.showTestPlans'
                },
                'TestPlans.showConfirmDeleteTestPlan': {
                    template: function(args) {
                        var tpl = _.template('Delete Test Plan <%= args[":testPlanId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestPlans.showTestPlans'
                },
                'TestPlans.viewTestPlan': {
                    template: function(args) {
                        var id = app.session.get('project_id');
                        var tpl = _.template('Managing Project <%= ' + id +  ' %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestPlans.showTestPlans'
                }
            }
        }
    });

    var TestRunsRouter = Backbone.Router.extend({
        routes: {
            // Execution routes
            'execution': 'showExecuteTestPlans',
            'execution?*queryString': 'showExecuteTestPlans',
            'testplans/:testPlanId/testruns': 'showTestRuns',
            'testplans/:testPlanId/testruns?*queryString': 'showTestRuns',
            'testplans/:testPlanId/testruns/new': 'showAddTestRun',
            'testplans/:testPlanId/testruns/:testRunId': 'showTestRun',
            'testplans/:testPlanId/testruns/:testRunId/confirmDelete': 'showConfirmDeleteTestRun',
            'testplans/:testPlanId/testruns/:testRunId/view': 'viewExecution'
        },
        navigation: {
            prefix: 'TestRuns',
            pages: {
                'TestRuns.showExecuteTestPlans': {
                    template: 'Execution',
                    parent: 'Base.defaultAction'
                },
                'TestRuns.showTestRuns': {
                    template: function(args) {
                        var tpl = _.template('Test Runs for test plan <%= args[":testPlanId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestRuns.showExecuteTestPlans'
                },
                'TestRuns.showAddTestRun': {
                    template: 'Add new Test Run',
                    parent: 'TestRuns.showExecuteTestPlans'
                },
                'TestRuns.showTestRun': {
                    template: function(args) {
                        var tpl = _.template('Edit Test Run <%= args[":testRunId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestRuns.showExecuteTestPlans'
                },
                'TestRuns.showConfirmDeleteTestRun': {
                    template: function(args) {
                        var tpl = _.template('Delete Test Run <%= args[":testRunId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestRuns.showExecuteTestPlans'
                },
                'TestRuns.viewTestRun': {
                    template: function(args) {
                        var tpl = _.template('Executing Test Plan <%= args["testPlanId"] %>');
                        return tpl({
                            args: args
                        });
                    },
                    parent: 'TestRuns.showExecuteTestPlans'
                }
            }
        }
    });

    var initialize = function() {

        // Projects collection shared by HeaderView and NewProjectView
        // So when a new project is saved, the header is automatically updated
        var projectsCollection = new ProjectsCollection();

        // --- common views ---
        if (!app.headerView) {
            app.headerView = new HeaderView({'collection': projectsCollection});
            app.headerView.render();
            app.session.bind('reset', app.headerView.updateMenu);
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
                app.newProjectView = new NewProjectView({'collection': projectsCollection});
            }
            app.showView(app.newProjectView, {
                requiresAuth: true
            });
        });

        projectsRouter.on('route:showProject', function(id) {
            if (!app.projectView) {
                app.projectView = new ProjectView({'collection': projectsCollection});
            }
            app.projectView.projectId = id;
            app.showView(app.projectView, {
                requiresAuth: true
            });
        });

        projectsRouter.on('route:showConfirmDeleteProject', function(id) {
            if (!app.confirmDeleteProjectView) {
                app.confirmDeleteProjectView = new ConfirmDeleteProjectView({'collection': projectsCollection});
            }
            app.confirmDeleteProjectView.projectId = id;
            app.showView(app.confirmDeleteProjectView, {
                requiresAuth: true
            });
        });

        // --- displayed as specification screen ---

        Backbone.on('project:position', function(objects) {
            if (app.currentView != app.viewProjectView) {
                if (objects[1] == true) {
                    Backbone.history.navigate("#/specification", {
                        trigger: false
                    });
                }
            }
        });

        projectsRouter.on('route:viewProject', function() {
            var id = app.session.get('project_id');
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: id
                });
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
            checkIfProjectIsSet();
            var params = parseQueryString(queryString);
            var parentId = 0;
            // the parent **must** be a test suite id, not a project id
            if (typeof(params.parent) != "undefined") {
                parentId = parseInt(params.parent);
            }
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
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
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }

            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayShowTestSuite();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayShowTestSuite
                });
            }
        });

        testSuitesRouter.on('route:showConfirmDeleteTestSuite', function(projectId, testSuiteId) {
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }

            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayConfirmDeleteTestSuite();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayConfirmDeleteTestSuite
                });
            }
        });

        testSuitesRouter.on('route:viewTestSuite', function(projectId, testSuiteId) {
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }

            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayTestSuite();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayTestSuite
                });
            }
        });

        testSuitesRouter.on('route:viewPlanTestSuite', function(testPlanId, testSuiteId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            if (!app.viewTestPlanView) {
                app.viewTestPlanView = new ViewTestPlanView({
                    projectId: id,
                    testPlanId: testPlanId
                });
            }

            app.viewTestPlanView.setProjectId(id);
            app.viewTestPlanView.setTestPlanId(testPlanId);
            app.viewTestPlanView.setTestSuiteId(testSuiteId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewTestPlanView.cid) {
                app.viewTestPlanView.displayTestSuite();
            } else {
                app.showView(app.viewTestPlanView, {
                    requiresAuth: true,
                    onSuccess: app.viewTestPlanView.displayTestSuite
                });
            }
        });

        // --- end test suites router ---

        // --- test cases router ---
        var testCasesRouter = new TestCasesRouter();

        testCasesRouter.on('route:showAddTestCase', function(projectId, testsuiteId, queryString) {
            checkIfProjectIsSet();
            var params = parseQueryString(queryString);
            var parentId = 0;
            // the parent **must** be a test suite id, not a project id
            if (typeof(params.parent) != "undefined") {
                parentId = parseInt(params.parent);
            }
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }
            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testsuiteId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                console.log('Re-using existing view project view. Displaying new test case view');
                app.viewProjectView.displayNewTestCase();
            } else {
                console.log('Displaying new test case view');
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayNewTestCase
                });
            }
        });

        testCasesRouter.on('route:viewTestCase', function(projectId, testSuiteId, testCaseId) {
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }

            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            app.viewProjectView.setTestCaseId(testCaseId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayTestCase();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayTestCase
                });
            }
        });

        testCasesRouter.on('route:showTestCase', function(projectId, testSuiteId, testCaseId) {
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }
            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            app.viewProjectView.setTestCaseId(testCaseId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayShowTestCase();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayShowTestCase
                });
            }
        });

        testCasesRouter.on('route:showConfirmDeleteTestCase', function(projectId, testSuiteId, testCaseId) {
            checkIfProjectIsSet();
            if (!app.viewProjectView) {
                app.viewProjectView = new ViewProjectView({
                    projectId: projectId
                });
            }

            app.viewProjectView.setProjectId(projectId);
            app.viewProjectView.setTestSuiteId(testSuiteId);
            app.viewProjectView.setTestCaseId(testCaseId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewProjectView.cid) {
                app.viewProjectView.displayConfirmDeleteTestCase();
            } else {
                app.showView(app.viewProjectView, {
                    requiresAuth: true,
                    onSuccess: app.viewProjectView.displayConfirmDeleteTestCase
                });
            }
        });

        testCasesRouter.on('route:viewPlanTestCase', function(testPlanId, testSuiteId, testCaseId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            if (!app.viewTestPlanView) {
                app.viewTestPlanView = new ViewTestPlanView({
                    projectId: id,
                    testPlanId: testPlanId
                });
            }

            app.viewTestPlanView.setProjectId(id);
            app.viewTestPlanView.setTestPlanId(testPlanId);
            app.viewTestPlanView.setTestSuiteId(testSuiteId);
            app.viewTestPlanView.setTestCaseId(testCaseId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewTestPlanView.cid) {
                app.viewTestPlanView.displayTestCase();
            } else {
                app.showView(app.viewTestPlanView, {
                    requiresAuth: true,
                    onSuccess: app.viewTestPlanView.displayTestCase
                });
            }
        });

        // --- end test cases router ---

        // --- projects router ---
        var testPlansRouter = new TestPlansRouter();

        testPlansRouter.on('route:showTestPlans', function(queryString) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            var params = parseQueryString(queryString);
            var page = 1;
            if (typeof(params.page) != "undefined") {
                page = params.page;
            }
            if (!app.testPlansView) {
                app.testPlansView = new TestPlansView();
            }
            app.testPlansView.setPage(page);
            app.testPlansView.setProjectId(id);
            app.showView(app.testPlansView, {
                requiresAuth: true
            });
        });

        testPlansRouter.on('route:showAddTestPlan', function(queryString) {
            checkIfProjectIsSet();
            var params = parseQueryString(queryString);
            if (!app.newTestPlanView) {
                app.newTestPlanView = new NewTestPlanView();
            }
            app.newTestPlanView.setProjectId(app.session.get('project_id'));
            app.showView(app.newTestPlanView, {
                requiresAuth: true
            });
        });

        testPlansRouter.on('route:showTestPlan', function(testPlanId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            if (!app.testPlanView) {
                app.testPlanView = new TestPlanView();
            }
            app.testPlanView.testplanId = testPlanId;
            app.testPlanView.projectId = id;
            app.showView(app.testPlanView, {
                requiresAuth: true
            });
        });

        testPlansRouter.on('route:showConfirmDeleteTestPlan', function(testPlanId) {
            checkIfProjectIsSet();
            if (!app.confirmDeleteTestPlanView) {
                app.confirmDeleteTestPlanView = new ConfirmDeleteTestPlanView();
            }
            app.confirmDeleteTestPlanView.model.set('id', testPlanId);
            app.showView(app.confirmDeleteTestPlanView, {
                requiresAuth: true
            });
        });

        // --- displayed as planning screen ---

        testPlansRouter.on('route:viewTestPlan', function(testPlanId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            if (!app.viewTestPlanView) {
                app.viewTestPlanView = new ViewTestPlanView({
                    projectId: id,
                    testPlanId: testPlanId
                });
            }
            app.viewTestPlanView.setProjectId(id);
            app.viewTestPlanView.setTestPlanId(testPlanId);
            if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewTestPlanView.cid) {
                app.viewTestPlanView.displayProject();
            } else {
                app.showView(app.viewTestPlanView, {
                    requiresAuth: true,
                    onSuccess: app.viewTestPlanView.displayProject
                });
            }
        });

        // --- end test plans router ---

        // --- test runs router ---
        var testRunsRouter = new TestRunsRouter();

        testRunsRouter.on('route:showExecuteTestPlans', function(queryString) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            var params = parseQueryString(queryString);
            var page = 1;
            if (typeof(params.page) != "undefined") {
                page = params.page;
            }
            if (!app.executeTestRunsView) {
                app.executeTestRunsView = new ExecuteTestPlansView();
            }
            app.executeTestRunsView.setPage(page);
            app.executeTestRunsView.setProjectId(id);
            app.showView(app.executeTestRunsView, {
                requiresAuth: true
            });
        });

        testRunsRouter.on('route:showTestRuns', function(testPlanId, queryString) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            var params = parseQueryString(queryString);
            var page = 1;
            if (typeof(params.page) != "undefined") {
                page = params.page;
            }
            if (!app.testRunsView) {
                app.testRunsView = new TestRunsView({
                    test_plan_id: testPlanId
                });
            }
            app.testRunsView.setPage(page);
            app.testRunsView.setTestPlanId(testPlanId);
            app.showView(app.testRunsView, {
                requiresAuth: true
            });
        });

        testRunsRouter.on('route:showAddTestRun', function(testPlanId, queryString) {
            checkIfProjectIsSet();
            var params = parseQueryString(queryString);
            if (!app.newTestRunView) {
                app.newTestRunView = new NewTestRunView({ test_plan_id: testPlanId });
            }
            app.newTestRunView.setTestPlanId(testPlanId);
            app.showView(app.newTestRunView, {
                requiresAuth: true
            });
        });

        testRunsRouter.on('route:showTestRun', function(testPlanId, testRunId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            // if (!app.testPlanView) {
            //     app.testPlanView = new TestPlanView();
            // }
            // app.testPlanView.testplanId = testPlanId;
            // app.testPlanView.projectId = id;
            // app.showView(app.testPlanView, {
            //     requiresAuth: true
            // });
        });

        testRunsRouter.on('route:showConfirmDeleteTestRun', function(testPlanId, testRunId) {
            checkIfProjectIsSet();
            // if (!app.confirmDeleteTestPlanView) {
            //     app.confirmDeleteTestPlanView = new ConfirmDeleteTestPlanView();
            // }
            // app.confirmDeleteTestPlanView.model.set('id', testPlanId);
            // app.showView(app.confirmDeleteTestPlanView, {
            //     requiresAuth: true
            // });
        });

        // --- displayed as execution screen ---

        testRunsRouter.on('route:viewTestRun', function(testPlanId, testRunId) {
            checkIfProjectIsSet();
            var id = app.session.get('project_id');
            // if (!app.viewTestPlanView) {
            //     app.viewTestPlanView = new ViewTestPlanView({
            //         projectId: id,
            //         testPlanId: testPlanId
            //     });
            // }
            // app.viewTestPlanView.setProjectId(id);
            // app.viewTestPlanView.setTestPlanId(testPlanId);
            // if (typeof app.currentView !== 'undefined' && app.currentView.cid == app.viewTestPlanView.cid) {
            //     app.viewTestPlanView.displayProject();
            // } else {
            //     app.showView(app.viewTestPlanView, {
            //         requiresAuth: true,
            //         onSuccess: app.viewTestPlanView.displayProject
            //     });
            // }
        });

        // --- end test runs router ---

        navigation.appendRouter(baseRouter);
        navigation.appendRouter(authRouter);
        navigation.appendRouter(projectsRouter);
        navigation.appendRouter(testSuitesRouter);
        navigation.appendRouter(testCasesRouter);
        navigation.appendRouter(testPlansRouter);
        navigation.appendRouter(testRunsRouter);
        navigation.mapRouters();
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
        TestSuitesRouter: TestSuitesRouter,
        TestCasesRouter: TestCasesRouter,
        TestPlansRouter: TestPlansRouter
    };

    return AppRouter;
});