define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testsuites/newTestSuiteTemplate.html',
    'text!templates/projects/projectAreaTemplate.html'
], function($, _, Backbone, ProjectModel, TestSuiteModel, TestSuitesCollection, newTestSuiteTemplate, projectAreaTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        el: $("#page"),

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
            this.projectAreaTemplate = null;
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');
            console.log(this.projectAreaTemplate);
            if (!this.projectAreaTemplate) {
                console.log('creating new project area template');
                var project = new ProjectModel({
                    id: this.projectId
                });
                var self = this;
                project.fetch({
                    success: function(data) {
                        var data = {
                            project: project,
                            _: _
                        };
                        // FIXME: wrong code here...
                        var compiledTemplate = _.template(projectAreaTemplate, data);
                        self.$el.html(compiledTemplate);
                        self.projectAreaTemplate = projectAreaTemplate;
                    },
                    error: function() {
                        throw new Error("Failed to fetch project");
                    }
                });
            }

            var compiledTemplate = _.template(newTestSuiteTemplate, {});
            $("#content-area").html(compiledTemplate);
        },

        save: function() {

        },

        close: function(newView) {
            this.unbind();
            if (_.has(newView, 'projectAreaTemplate')) {
                console.log('Emptying content area');
                $("#content-area").empty();
                this.projectAreaTemplate = newView.projectAreaTemplate;
            } else {
                console.log('Emptying the whole page');
                this.$el.empty();
                newView.projectAreaTemplate = this.projectAreaTemplate;
                this.projectAreaTemplate = undefined;
            }
        }

    });

    return NewTestSuiteView;

});