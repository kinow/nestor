define([
    'jquery',
    'underscore',
    'backbone',
    'views/projects/NavigationTreeView',
    'views/projects/NodeItemView',
    'views/testsuites/NewTestSuiteView',
    'models/project/ProjectModel',
    'text!templates/projects/viewProjectTemplate.html',
    'text!templates/projects/projectNodeItemTemplate.html',
    'text!templates/testsuites/newTestSuiteTemplate.html',
], function($, _, Backbone, NavigationTreeView, NodeItemView, NewTestSuiteView, ProjectModel, viewProjectTemplate, projectNodeItemTemplate, newTestSuiteTemplate) {

    /**
     * Displays the navigation tree.
     */
    var ViewProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'displayProject', 'displayNewTestSuite');

            // Views
            this.navigationTreeView = new NavigationTreeView();
            this.nodeItemView = new NodeItemView();
            this.newTestSuiteView = new NewTestSuiteView();

            // For GC
            this.subviews = new Object();
            this.subviews.navigationTreeView = this.navigationTreeView;
            this.subviews.nodeItemView = this.nodeItemView;
            this.subviews.newTestSuiteView = this.newTestSuiteView;
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

            var compiledTemplate = _.template(viewProjectTemplate, {});
            this.$el.html(compiledTemplate);

            var navigationTreeContent = this.navigationTreeView.render();
            console.log('Created navigation tree!!');
            this.$el.find('#navigation-tree').html(navigationTreeContent);
        },

        /**
         * Display project node item on the right panel of the screen.
         */
        displayProject: function(projectId) {
            this.projectModel = new ProjectModel();
            this.projectModel.id = projectId;
            var self = this;
            this.projectModel.fetch({
                success: function(data) {
                    var data = {
                        project: self.projectModel,
                        _: _
                    };

                    var compiledTemplate = _.template(projectNodeItemTemplate, data);
                    self.nodeItemView.$el.html(compiledTemplate);
                    self.$('#content-area').replaceWith(self.nodeItemView.el);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

        displayNewTestSuite: function() {
            var compiledTemplate = _.template(newTestSuiteTemplate, {});
            this.nodeItemView.$el.html(compiledTemplate);
            this.$('#content-area').replaceWith(this.nodeItemView.el);
        },

        rendered: function() {
            return !$("#navigation-tree").length == 0;
        }

    });

    return ViewProjectView;

});