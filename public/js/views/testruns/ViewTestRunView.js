define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'views/navigationtree/NavigationTreeView',
    'views/projects/ViewNodeItemView',
    'views/testsuites/TestSuiteView',
    'views/testcases/TestCaseView',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'models/testcase/TestCaseModel',
    'models/testplan/TestPlanModel',
    'models/testrun/TestRunModel',
    'collections/core/ExecutionStatusesCollection',
    'collections/navigationtree/NavigationTreeCollection',
    'collections/testruns/TestRunsCollection',
    'text!templates/testruns/viewTestRunTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html',
    'text!templates/testcases/testCaseExecuteNodeItemTemplate.html'
    ], function(
        $,
        _,
        Backbone,
        app,
        SimpleMDE,
        NavigationTreeView,
        ViewNodeItemView,
        TestSuiteView,
        TestCaseView,
        ProjectModel,
        TestSuiteModel,
        TestCaseModel,
        TestPlanModel,
        TestRunModel,
        ExecutionStatusesCollection,
        NavigationTreeCollection,
        TestRunsCollection,
        viewTestRunTemplate,
        projectNodeItemTemplate,
        testSuiteNodeItemTemplate,
        testCaseExecuteNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
     var ViewTestRunView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'click #save-testplan-btn': 'addTestCasesToTestPlan',
            'click #cancel-testplan-btn': 'cancelAndGoBack',
            'click #execution-history-btn': 'toggleExecutionHistory',
            'click #execute-testcase-btn': 'executeTestCase'
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
                'cancelAndGoBack',
                'toggleExecutionHistory',
                'executeTestCase');

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
            this.executionStatusCollection = new ExecutionStatusesCollection();

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
            this.testCaseSimplemde = null;

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

        toggleExecutionHistory: function() {
            this.$("#execution-history-panel").toggle();
        },

        /**
         * Display project node item on the right panel of the screen.
         */
         displayProject: function() {
            this.displayLoading();
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');
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
         * Display test suite node item on the right panel of the screen.
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
         * Display test case node item on the right panel of the screen.
         */
         displayTestCase: function() {
            var self = this;
            this.testCaseModel.set('project_id', this.projectId);
            this.testCaseModel.set('test_suite_id', this.testSuiteId);
            this.testCaseModel.set('id', this.testCaseId);
            this.testCaseModel.url = '/api/testplans/' + this.testPlanId + '/testruns/' + this.testRunId + '/testsuites/' + this.testSuiteId + '/testcases/' + this.testCaseId + '/executions';
            $.when(this.testCaseModel.fetch(), this.executionStatusCollection.fetch())
                .done(function(testCaseResponse, executionStatusesResponse) {
                    var testcase = self.testCaseModel;
                    var execution_statuses = self.executionStatusCollection.models;
                    var data = {
                        testcase: testcase,
                        execution_statuses: execution_statuses,
                        _: _
                    };

                    var compiledTemplate = _.template(testCaseExecuteNodeItemTemplate, data);
                    self.viewNodeItemView.$el.html(compiledTemplate);

                    self.$('#content-main').empty();
                    self.$('#content-main').append(self.viewNodeItemView.el);
                    self.$('#navigation-tree').fancytree('getTree').getNodeByKey("3-" + testcase.get('id')).setActive();

                    self.testCaseSimplemde = new SimpleMDE({
                        autoDownloadFontAwesome: true, 
                        autofocus: false,
                        autosave: {
                            enabled: false
                        },
                        element: $('#testcase-notes-input')[0],
                        indentWithTabs: false,
                        spellChecker: false,
                        tabSize: 4
                    });
                })
            ;
        },

        /**
         * Execute test case.
         */
        executeTestCase: function() {
            var self = this;
            if (this.$("#execute-testcase-form").parsley().validate()) {
                var executionStatusesId = $('input[type=radio][name=testcase-executionstatus_id-input]:checked').attr('id');
                var notes = this.testCaseSimplemde.value();

                var testRun = this.
                    testRunModel.
                    execute(
                        {execution_statuses_id: executionStatusesId, notes: notes},
                        self.testPlanId,
                        self.testRunId,
                        self.testSuiteId,
                        self.testCaseModel.get('version')['id']
                    ).
                    done(function(response) {
                        app.showAlert('Success!', 'Test case ' + $("#testrun-name-input").val() + ' execution status updated!', 'success')
                        Backbone.history.navigate("#/testplans/" + self.testPlanId + '/testruns/' + self.testRunId + '/testsuites/' + self.testSuiteId + '/testcases/' + self.testCaseId + '/execute', {
                            trigger: true
                        });
                    })
                    .fail(function(response) {
                        var message = _.has(response, 'statusText') ? response.statusText : 'Unknown error!';
                        if (
                            _.has(response, 'responseJSON') &&
                            _.has(response.responseJSON, 'name') &&
                            _.has(response.responseJSON.name, 'length') &&
                            response.responseJSON.name.length > 0
                            ) {
                            message = response.responseJSON.name[0];
                        }
                        app.showAlert('Failed to execute Test Case', message, 'error');
                    })
                ;
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return ViewTestRunView;

});