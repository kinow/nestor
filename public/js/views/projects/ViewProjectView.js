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
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'collections/core/ExecutionTypesCollection',
    'text!templates/projects/viewProjectTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html'
], function($, _, Backbone, NavigationTreeView, ViewNodeItemView, NewTestSuiteView, TestSuiteView, ConfirmDeleteTestSuiteView, NewTestCaseView, ProjectModel, TestSuiteModel, ExecutionTypesCollection, viewProjectTemplate, projectNodeItemTemplate, testSuiteNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'setProjectId', 'updateNavigationTree', 'displayProject', 'displayNewTestSuite', 'displayTestSuite', 'displayShowTestSuite', 'displayConfirmDeleteTestSuite', 'displayNewTestCase');

            this.projectId = 0;
            this.testSuiteId = 0;

            // Views
            this.navigationTreeView = new NavigationTreeView();
            this.viewNodeItemView = new ViewNodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();
            this.newTestCaseView = new NewTestCaseView();
            this.testSuiteView = new TestSuiteView();
            this.confirmDeleteTestSuiteView = new ConfirmDeleteTestSuiteView();

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
        },


        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

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

            if (this.projectId !== projectId || !$.trim($(this.navigationTreeView.el).html())) {
                this.navigationTreeView.projectId = projectId;
                this.projectId = projectId;
                Backbone.trigger('nestor:navigationtree:project_changed');
            }
        },

        setTestSuiteId: function(testsuiteId) {
            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = this.projectId;
            this.testsuiteId = testsuiteId;
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

    });

    return ViewProjectView;

});