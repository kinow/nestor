define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'views/navigationtree/NavigationTreeView',
    'views/projects/ViewNodeItemView',
    'views/testsuites/TestSuiteView',
    'views/testcases/TestCaseView',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'models/testcase/TestCaseModel',
    'models/testplan/TestPlanModel',
    'models/testrun/TestRunModel',
    'collections/navigationtree/NavigationTreeCollection',
    'collections/testruns/TestRunsCollection',
    'text!templates/testruns/viewTestRunTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html',
    'text!templates/testcases/testCaseNodeItemTemplate.html'
], function(
    $,
    _,
    Backbone,
    app,
    NavigationTreeView,
    ViewNodeItemView,
    TestSuiteView,
    TestCaseView,
    ProjectModel,
    TestSuiteModel,
    TestCaseModel,
    TestPlanModel,
    TestRunModel,
    NavigationTreeCollection,
    TestRunsCollection,
    viewTestRunTemplate,
    projectNodeItemTemplate,
    testSuiteNodeItemTemplate,
    testCaseNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewTestRunView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'click #save-testplan-btn': 'addTestCasesToTestPlan',
            'click #cancel-testplan-btn': 'cancelAndGoBack'
        },

        initialize: function(options) {
            _.bindAll(this,
                'render', 'render2',
                'setProjectId',
                'setTestSuiteId',
                'setTestCaseId',
                'setTestPlanId',
                'setTestRunId',
                'updateNavigationTree',
                'displayLoading',
                'displayProject',
                'displayTestSuite',
                'displayTestCase',
                'cancelAndGoBack');

            var self = this;

            this.projectId = parseInt(options.projectId);
            this.testSuiteId = 0;
            this.testCaseId = 0;
            this.testPlanId = parseInt(options.testPlanId);
            this.testRunId = parseInt(options.testRunId);

            // Collections
            this.navigationTreeCollection = new NavigationTreeCollection({
                projectId: self.projectId
            });
            this.testRunsCollection = new TestRunsCollection({ test_plan_id: this.testPlanId });

            // Models
            this.testPlanModel = new TestPlanModel();
            this.testPlanModel.set('id', this.testPlanId);
            this.testRunModel = new TestRunModel({ test_plan_id: this.testPlanId });
            this.testRunModel.set('id', this.testPlanId);

            // Views
            this.navigationTreeView = new NavigationTreeView({
                draggable: false,
                checkboxes: false,
                rootNodeUrl: '#/testplans/' + options.testPlanId + '/testruns/' + options.testRunId + '/execute',
                nodeUrlPrefix: '#/testplans/' + options.testPlanId + '/testruns/' + options.testRunId,
                nodeUrlSuffix: '/execute',
                collection: self.navigationTreeCollection
            });
            this.viewNodeItemView = new ViewNodeItemView();
            this.testSuiteView = new TestSuiteView();
            this.testCaseView = new TestCaseView();

            // Events
            Backbone.on('nestor:navigationtree:project_changed', this.updateNavigationTree);
            Backbone.on('nestor:navigationtree_changed', this.updateNavigationTree);

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.viewNodeItemView = this.viewNodeItemView;
            this.subviews.testSuiteView = this.testSuiteView;
            this.subviews.testCaseView = this.testCaseView;
        },

        cancelAndGoBack: function(evt) {
            Backbone.history.navigate("#/execution", { trigger: false });
        },

        render: function() {
            var self = this;
            $.when(this.testRunModel.fetch(), this.navigationTreeCollection.fetch({ reset: true }))
                .done(function() {
                    self.render2();
                })
            ;
        },

        render2: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');

            var compiledTemplate = _.template(viewTestRunTemplate, {});
            this.$el.html(compiledTemplate);

            this.$('#content-main').empty();
            this.updateNavigationTree();
            this.delegateEvents();
        },

        updateNavigationTree: function(event) {
            var self = this;
            if (app.currentView == this) {
                console.log('Rendering navigation tree!');
                this.$('#navigation-tree').fancytree({});
                self.navigationTreeView.render({
                    el: self.$('#navigation-tree'),
                    selected: self.testPlanModel.get('test_cases')
                });
            }
        },

        setProjectId: function(id) {
            var projectId = parseInt(id);
            // update project ID in models
            this.projectModel = new ProjectModel();
            this.projectModel.set('id', projectId);

            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.set('project_id', projectId);

            this.testCaseModel = new TestCaseModel();
            this.testCaseModel.set('project_id', projectId);

            this.navigationTreeView.projectId = projectId;

            if (this.projectId !== projectId/* || !$.trim($(this.navigationTreeView.el).html())*/) {
                this.projectId = projectId;
                Backbone.trigger('nestor:navigationtree:project_changed');
                if (app.currentView == this) {
                    this.displayProject();
                }
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

        setTestPlanId: function(testPlanId) {
            this.testPlanId = testPlanId;
            this.testPlanModel.set('id', this.testPlanId);
        },

        setTestRunId: function(testRunId) {
            this.testRunId = testRunId;
            this.testRunModel.set('id', this.testRunId);
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
            $('.item').removeClass('active');
            $('.item a[href="#/planning"]').parent().addClass('active');
            var self = this;
            this.projectModel.fetch({
                success: function(responseData) {
                    var project = self.projectModel;
                    var data = {
                        project: project,
                        _: _,
                        editable: false
                    };

                    var compiledTemplate = _.template(projectNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    this.$('#content-main').unbind();
                    this.$('#content-main').empty();
                    this.$('#content-main').append(self.viewNodeItemView.el);
                    self.viewNodeItemView.delegateEvents();
                    console.log(project);
                    var tree = this.$('#navigation-tree').fancytree('getTree');
                    if (typeof tree !== typeof undefined && typeof tree.getNodeByKey !== typeof undefined) {
                        var node = tree.getNodeByKey("1-" + project.get('id'));
                        if (node && typeof node !== typeof undefined && typeof node.setActive !== typeof undefined) {
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
         * Display project node item on the right panel of the screen.
         */
        displayTestSuite: function() {
            var self = this;
            this.testSuiteModel.set('id', this.testSuiteId);
            this.testSuiteModel.fetch({
                success: function(responseData) {
                    var data = {
                        testsuite: self.testSuiteModel,
                        _: _,
                        editable: false
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
         * Display project node item on the right panel of the screen.
         */
        displayTestCase: function() {
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.fetch({
                success: function(responseData) {
                    var testcase = self.testCaseModel;
                    var data = {
                        testcase: testcase,
                        _: _,
                        editable: false
                    };

                    var compiledTemplate = _.template(testCaseNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);
                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                    self.$('#navigation-tree').fancytree('getTree').getNodeByKey("3-" + testcase.get('id')).setActive();
                },
                error: function() {
                    throw new Error("Failed to fetch test case");
                }
            });
        }

    });

    return ViewTestRunView;

});