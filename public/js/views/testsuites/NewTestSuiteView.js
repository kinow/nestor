define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'text!templates/projects/projectAreaTemplate.html',
    'text!templates/testsuites/newTestSuiteTemplate.html'
], function($, _, Backbone, ProjectModel, TestSuiteModel, projectAreaTemplate, newTestSuiteTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        el: $("#page"),

        events: {},
        
        initialize: function(options) {
            _.bindAll(this, 'render');
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');
            var project = new ProjectModel({
                id: this.projectId
            });
            var self = this;
            project.fetch({
                success: function() {
                    var data = {
                        project: project,
                        _: _
                    };
                    var compiledTemplate = _.template(projectAreaTemplate, data);
                    self.$el.html(compiledTemplate);
                    var compiledTemplate = _.template(newTestSuiteTemplate, data);
                    $("#content-area").html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

    });

    return NewTestSuiteView;

});