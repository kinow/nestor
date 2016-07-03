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
    'views/testcases/ConfirmDeleteTestCaseView',
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
    ConfirmDeleteTestCaseView,
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

        initialize: function(options) {
            _.bindAll(this,
                'render',
                'setProjectId',
                'setTestSuiteId',
                'setTestCaseId',
                'onProjectPositioned',
                'updateNavigationTree',
                'displayProject',
                'displayNewTestSuite',
                'displayTestSuite',
                'displayShowTestSuite',
                'displayConfirmDeleteTestSuite',
                'displayNewTestCase',
                'displayTestCase',
                'displayShowTestCase',
                'displayConfirmDeleteTestCase');

            this.projectId = 0;
            if (typeof options !== typeof undefined && typeof options.projectId !== typeof undefined) {
                this.projectId = parseInt(options.projectId);
            }
            this.testSuiteId = 0;
            this.testCaseId = 0;

            // Views
            this.navigationTreeView = new NavigationTreeView({
                draggable: true,
                checkboxes: false,
                rootNodeUrl: '#/specification',
                nodeUrlPrefix: '#/projects'
            });
            this.viewNodeItemView = new ViewNodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();
            this.newTestCaseView = new NewTestCaseView();
            this.testSuiteView = new TestSuiteView();
            this.confirmDeleteTestSuiteView = new ConfirmDeleteTestSuiteView();
            this.testCaseView = new TestCaseView();
            this.confirmDeleteTestCaseView = new ConfirmDeleteTestCaseView();

            // Collections
            this.executionTypesCollection = new ExecutionTypesCollection();

            // Events
            Backbone.on('nestor:navigationtree:project_changed', this.updateNavigationTree);
            Backbone.on('nestor:navigationtree_changed', this.updateNavigationTree);
            Backbone.on('project:position', this.onProjectPositioned);

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.viewNodeItemView = this.viewNodeItemView;
            this.subviews.newTestSuiteView = this.newTestSuiteView;
            this.subviews.testSuiteView = this.testSuiteView;
            this.subviews.confirmDeleteTestSuiteView = this.confirmDeleteTestSuiteView;
            this.subviews.newTestCaseView = this.newTestCaseView;
            this.subviews.testCaseView = this.testCaseView;
            this.subviews.confirmDeleteTestCaseView = this.confirmDeleteTestCaseView;
        },


        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/specification"]').parent().addClass('active');
            var compiledTemplate = _.template(viewProjectTemplate, {});
            this.$el.html(compiledTemplate);

            this.$('#content-main').empty();
            this.updateNavigationTree();
        },

        setProjectId: function(id) {
            var projectId = parseInt(id);
            // update project ID in models
            this.projectModel = new ProjectModel();
            this.projectModel.set('id', projectId);
            this.projectModel.projectId = projectId;

            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = projectId;

            this.testCaseModel = new TestCaseModel();
            this.testCaseModel.project_id = projectId;

            this.navigationTreeView.projectId = projectId;

            if (this.projectId !== projectId/* || !$.trim($(this.navigationTreeView.el).html())*/) {
                this.projectId = projectId;
                Backbone.trigger('nestor:navigationtree:project_changed');
                this.displayProject();
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

        onProjectPositioned: function(project) {
            this.setProjectId(project.id);
        },

        updateNavigationTree: function(event) {
            console.log('Rendering navigation tree!');
            this.$('#navigation-tree').fancytree({});
            this.navigationTreeView.render({
                el: this.$('#navigation-tree')
            });
        },

        displayLoading: function() {
            this.$('#content-main').empty();
            this.$('#content-main').html('<div class="ui active dimmer"><div class="ui loader"></div></div>');
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayProject: function() {
            this.displayLoading();
            var self = this;
            this.projectModel.fetch({
                success: function(responseData) {
                    var project = self.projectModel;
                    var data = {
                        project: project,
                        _: _
                    };

                    var compiledTemplate = _.template(projectNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    this.$('#content-main').empty();
                    this.$('#content-main').append(self.viewNodeItemView.el);
                    var tree = this.$('#navigation-tree').fancytree('getTree');
                    if (typeof tree !== typeof undefined && typeof tree.getNodeByKey !== typeof undefined) {
                        var node = tree.getNodeByKey("1-" + project.get('id'));
                        if (typeof node !== typeof undefined && typeof node.setActive !== typeof undefined) {
                            node.setActive();
                        } else {
                            console.log('Invalid tree node for node ' + project.get('id'));
                        }
                    } else {
                        console.log('Invalid navigation tree for project ' + self.projectId);
                    }
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
            this.displayLoading();
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
            this.displayLoading();
            var self = this;
            this.testSuiteModel.set('id', this.testSuiteId);
            this.testSuiteModel.fetch({
                success: function(responseData) {
                    var testsuite = self.testSuiteModel;
                    var data = {
                        testsuite: testsuite,
                        _: _
                    };

                    var compiledTemplate = _.template(testSuiteNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                    this.$('#navigation-tree').fancytree('getTree').getNodeByKey("2-" + testsuite.get('id')).setActive();
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
            this.displayLoading();
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
                    setTimeout(function() {
                        self.testSuiteView.simplemde.value(self.testSuiteView.model.get('description'));
                        self.testSuiteView.simplemde.codemirror.refresh();
                    }, 1);
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        },

        displayConfirmDeleteTestSuite: function() {
            this.displayLoading();
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
            this.displayLoading();
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
            this.displayLoading();
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.fetch({
                success: function(responseData) {
                    var testcase = self.testCaseModel;
                    var data = {
                        testcase: testcase,
                        _: _
                    };

                    var compiledTemplate = _.template(testCaseNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                    this.$('#navigation-tree').fancytree('getTree').getNodeByKey("3-" + testcase.get('id')).setActive();
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
            this.displayLoading();
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
                                test_case_id: self.testCaseId,
                                project_id: self.projectId,
                                test_suite_id: self.testSuiteId,
                                execution_types: self.executionTypesCollection.models
                            });
                            self.testCaseView.delegateEvents();
                            self.$('#content-main').empty();
                            self.$('#content-main').append(self.testCaseView.el);
                            setTimeout(function() {
                                self.testCaseView.description_simplemde.value(self.testCaseView.model.get('version')['description']);
                                self.testCaseView.description_simplemde.codemirror.refresh();
                            }, 1);
                            setTimeout(function() {
                                self.testCaseView.prerequisite_simplemde.value(self.testCaseView.model.get('version')['prerequisite']);
                                self.testCaseView.prerequisite_simplemde.codemirror.refresh();
                            }, 1);
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

        displayConfirmDeleteTestCase: function() {
            this.displayLoading();
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.fetch({
                success: function(responseData) {
                    self.executionTypesCollection.fetch({
                        success: function() {
                            self.confirmDeleteTestCaseView.render({
                                model: self.testCaseModel,
                                project_id: self.projectId,
                                test_suite_id: self.testSuiteId,
                                execution_types: self.executionTypesCollection.models
                            });
                            self.confirmDeleteTestCaseView.delegateEvents();
                            self.$('#content-main').empty();
                            self.$('#content-main').append(self.confirmDeleteTestCaseView.el);
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