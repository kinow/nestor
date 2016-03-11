define([
    'jquery',
    'underscore',
    'backbone',
    'views/navigationtree/NavigationTreeView',
    'views/projects/ViewNodeItemView',
    'views/testsuites/NewTestSuiteView',
    'views/testsuites/TestSuiteView',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'text!templates/projects/viewProjectTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/newTestSuiteTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html'
], function($, _, Backbone, NavigationTreeView, ViewNodeItemView, NewTestSuiteView, TestSuiteView, ProjectModel, TestSuiteModel, viewProjectTemplate, projectNodeItemTemplate, newTestSuiteTemplate, testSuiteNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'setProjectId', 'updateNavigationTree', 'displayProject', 'displayNewTestSuite', 'displayTestSuite', 'displayShowTestSuite');

            this.projectId = 0;
            this.testSuiteId = 0;

            // Views
            this.navigationTreeView = new NavigationTreeView();
            this.viewNodeItemView = new ViewNodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();
            this.testSuiteView = new TestSuiteView();

            // Events
            Backbone.on('nestor:navigationtree:project_changed', this.updateNavigationTree);
            Backbone.on('nestor:navigationtree_changed', this.updateNavigationTree);

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.viewNodeItemView = this.viewNodeItemView;
            this.subviews.newTestSuiteView = this.newTestSuiteView;
            this.subviews.testSuiteView = this.testSuiteView;
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
            this.navigationTreeView.projectId = projectId;

            // update project ID in models
            this.projectModel = new ProjectModel();
            this.projectModel.id = projectId;

            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = projectId;

            if (this.projectId !== projectId) {
                this.projectId = projectId;
                Backbone.trigger('nestor:navigationtree:project_changed');
            }
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
                parent_id: this.parentId
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
        }

    });

    return ViewProjectView;

});