define([
    'jquery',
    'underscore',
    'backbone',
    'views/navigationtree/NavigationTreeView',
    'views/projects/ViewNodeItemView',
    'views/testsuites/NewTestSuiteView',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'text!templates/projects/viewProjectTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/newTestSuiteTemplate.html',
    'text!templates/testsuites/testSuiteNodeItemTemplate.html'
], function($, _, Backbone, NavigationTreeView, ViewNodeItemView, NewTestSuiteView, ProjectModel, TestSuiteModel, viewProjectTemplate, projectNodeItemTemplate, newTestSuiteTemplate, testSuiteNodeItemTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function(options) {
            _.bindAll(this, 'render', 'displayProject', 'displayNewTestSuite', 'displayTestSuite');

            this.projectId = options.projectId;
            this.testSuiteId = 0;

            // Views
            this.navigationTreeView = new NavigationTreeView({
                projectId: this.projectId
            });
            this.viewNodeItemView = new ViewNodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();

            // Events
            Backbone.on('navigationtree_changed', this.navigationTreeView.render);

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.viewNodeItemView = this.viewNodeItemView;
            this.subviews.newTestSuiteView = this.newTestSuiteView;
        },


        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

            var compiledTemplate = _.template(viewProjectTemplate, {});
            this.$el.html(compiledTemplate);

            this.navigationTreeView.render();
            this.navigationTreeView.delegateEvents();
            this.$('#content-main').empty();
            this.$('#navigation-tree').replaceWith(this.navigationTreeView.el);
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayProject: function(projectId) {
            this.navigationTreeView.projectId = projectId;

            this.projectModel = new ProjectModel();
            this.projectModel.id = projectId;
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
            // var compiledTemplate = _.template(newTestSuiteTemplate, {});
            // this.viewNodeItemView.$el.html(compiledTemplate);
            // this.$('#content-area').replaceWith(this.viewNodeItemView.el);

            this.newTestSuiteView.render({parent_id: this.parentId});
            this.newTestSuiteView.delegateEvents();
            this.$('#content-main').empty();
            this.$('#content-main').append(this.newTestSuiteView.el);
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayTestSuite: function() {
            this.navigationTreeView.projectId = this.projectId;

            this.testSuiteModel = new TestSuiteModel();
            this.testSuiteModel.project_id = this.projectId;
            this.testSuiteModel.id = this.testSuiteId;
            var self = this;
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

        rendered: function() {
            return !$("#navigation-tree").length == 0;
        }

    });

    return ViewProjectView;

});