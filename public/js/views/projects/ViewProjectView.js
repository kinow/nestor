define([
    'jquery',
    'underscore',
    'backbone',
    'views/navigationtree/NavigationTreeView',
    'views/projects/ViewNodeItemView',
    'views/testsuites/NewTestSuiteView',
    'views/testsuites/TestSuiteView',
    'views/testsuites/ConfirmDeleteTestSuiteView',
    'views/testcases/NewTestCaseView',
    'views/testcases/TestCaseView',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'models/testcase/TestCaseModel',
    'collections/core/ExecutionTypesCollection',
    'text!templates/projects/viewProjectTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html',
    'text!templates/testcases/testCaseNodeItemTemplate.html'
], function(
    $, 
    _, 
    Backbone, 
    NavigationTreeView, 
    ViewNodeItemView, 
    NewTestSuiteView, 
    TestSuiteView, 
    ConfirmDeleteTestSuiteView, 
    NewTestCaseView,
    TestCaseView, 
    ProjectModel, 
    TestSuiteModel, 
    TestCaseModel,
    ExecutionTypesCollection, 
    viewProjectTemplate, 
    projectNodeItemTemplate, 
    testSuiteNodeItemTemplate, 
    testCaseNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {
            _.bindAll(this, 
                'render', 
                'setProjectId', 
                'setTestSuiteId', 
                'setTestCaseId', 
                'updateNavigationTree', 
                'displayProject', 
                'displayNewTestSuite', 
                'displayTestSuite', 
                'displayShowTestSuite', 
                'displayConfirmDeleteTestSuite', 
                'displayNewTestCase', 
                'displayTestCase',
                'displayShowTestCase');

            this.projectId = 0;
            this.testSuiteId = 0;
            this.testCaseId = 0;

            // Views
            this.navigationTreeView = new NavigationTreeView();
            this.viewNodeItemView = new ViewNodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();
            this.newTestCaseView = new NewTestCaseView();
            this.testSuiteView = new TestSuiteView();
            this.confirmDeleteTestSuiteView = new ConfirmDeleteTestSuiteView();
            this.testCaseView = new TestCaseView();

            // Collections
            this.executionTypesCollection = new ExecutionTypesCollection();

            // Events
            Backbone.on('nestor:navigationtree:project_changed', this.updateNavigationTree);
            Backbone.on('nestor:navigationtree_changed', this.updateNavigationTree);

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.viewNodeItemView = this.viewNodeItemView;
            this.subviews.newTestSuiteView = this.newTestSuiteView;
            this.subviews.testSuiteView = this.testSuiteView;
            this.subviews.confirmDeleteTestSuiteView = this.confirmDeleteTestSuiteView;
            this.subviews.newTestCaseView = this.newTestCaseView;
            this.subviews.testCaseView = this.testCaseView;
        },


        render: function() {
            var compiledTemplate = _.template(viewProjectTemplate, {});
            this.$el.html(compiledTemplate);

            this.$('#content-main').empty();
            this.$('#navigation-tree').replaceWith(this.navigationTreeView.el);
        },

        setProjectId: function(projectId) {
            // update project ID in models
            this.projectModel = new ProjectModel();
            this.projectModel.id = projectId;

            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = projectId;

            this.testCaseModel = new TestCaseModel();
            this.testCaseModel.project_id = projectId;

            if (this.projectId !== projectId || !$.trim($(this.navigationTreeView.el).html())) {
                this.navigationTreeView.projectId = projectId;
                this.projectId = projectId;
                Backbone.trigger('nestor:navigationtree:project_changed');
            }
        },

        setTestSuiteId: function(testSuiteId) {
            // update test suite ID in models
            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = this.projectId;
            this.testSuiteModel.id = testSuiteId;
            this.testSuiteId = testSuiteId;

            this.testCaseModel = new TestCaseModel();
            this.testCaseModel.project_id = this.projectId;
            this.testCaseModel.testsuite_id = testSuiteId;
        },

        setTestCaseId: function(testCaseId) {
            // update test suite ID in models
            this.testCaseModel = new TestCaseModel();
            this.testCaseModel.project_id = this.projectId;
            this.testCaseModel.testsuite_id = this.testSuiteId;
            this.testCaseModel.id = testCaseId;

            this.testCaseId = testCaseId;
        },

        updateNavigationTree: function(event) {
            console.log('Rendering navigation tree!');
            this.navigationTreeView.render();
            this.navigationTreeView.delegateEvents();
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayProject: function() {
            var self = this;
            this.projectModel.fetch({
                success: function(responseData) {
                    var data = {
                        project: self.projectModel,
                        _: _
                    };

                    var compiledTemplate = _.template(projectNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    this.$('#content-main').empty();
                    this.$('#content-main').append(self.viewNodeItemView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

        /**
         * Display new test suite form.
         */
        displayNewTestSuite: function() {
            this.newTestSuiteView.render({
                parent_id: this.parentId,
                project_id: this.projectId
            });
            this.newTestSuiteView.delegateEvents();
            this.$('#content-main').empty();
            this.$('#content-main').append(this.newTestSuiteView.el);
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayTestSuite: function() {
            var self = this;
            this.testSuiteModel.set('id', this.testSuiteId);
            this.testSuiteModel.fetch({
                success: function(responseData) {
                    var data = {
                        testsuite: self.testSuiteModel,
                        _: _
                    };

                    var compiledTemplate = _.template(testSuiteNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        },

        /**
         * Display test suite item on the right panel of the screen for edit.
         */
        displayShowTestSuite: function() {
            var self = this;
            this.testSuiteModel.set('id', this.testSuiteId);
            this.testSuiteModel.fetch({
                success: function(responseData) {
                    self.testSuiteView.render({
                        model: self.testSuiteModel,
                        project_id: self.projectId
                    });
                    self.testSuiteView.delegateEvents();
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.testSuiteView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        },

        displayConfirmDeleteTestSuite: function() {
            var self = this;
            this.testSuiteModel.set('id', this.testSuiteId);
            this.testSuiteModel.fetch({
                success: function(responseData) {
                    self.confirmDeleteTestSuiteView.render({
                        model: self.testSuiteModel,
                        project_id: self.projectId
                    });
                    self.confirmDeleteTestSuiteView.delegateEvents();
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.confirmDeleteTestSuiteView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        },

        /**
         * Display new test case form.
         */
        displayNewTestCase: function() {
            var self = this;
            this.executionTypesCollection.fetch({
                success: function() {
                    self.newTestCaseView.render({
                        project_id: self.projectId,
                        testsuite_id: self.testSuiteId,
                        execution_types: self.executionTypesCollection.models
                    });
                    self.newTestCaseView.delegateEvents();
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.newTestCaseView.el);
                },
                error: function() {
                    throw new Error('Failure to retrieve executiont types!');
                }
            });
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayTestCase: function() {
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.fetch({
                success: function(responseData) {
                    var data = {
                        testcase: self.testCaseModel,
                        _: _
                    };

                    var compiledTemplate = _.template(testCaseNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch test case");
                }
            });
        },

        /**
         * Display test case item on the right panel of the screen for edit.
         */
        displayShowTestCase: function() {
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.fetch({
                success: function(responseData) {
                    self.executionTypesCollection.fetch({
                        success: function() {
                            self.testCaseView.render({
                                model: self.testCaseModel,
                                project_id: self.projectId,
                                test_suite_id: self.testSuiteId,
                                execution_types: self.executionTypesCollection.models
                            });
                            self.testCaseView.delegateEvents();
                            self.$('#content-main').empty();
                            self.$('#content-main').append(self.testCaseView.el);
                        },
                        error: function() {
                            throw new Error('Failure to retrieve executiont types!');
                        }
                    });
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        },

    });

    return ViewProjectView;

});