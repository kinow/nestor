define([
    'jquery',
    'underscore',
    'backbone',
    'models/testsuite/TestSuiteModel',
    'text!templates/testsuites/viewTestSuiteTemplate.html'
], function($, _, Backbone, TestSuiteModel, viewTestSuiteTemplate) {

    var ViewTestSuiteView = Backbone.View.extend({

        initialize: function(options) {
            this.projectId = 0;
            this.testSuiteId = 0;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            this.model = new TestSuiteModel({
                id: this.testSuiteId,
                projectId: this.projectId
            });
            var self = this;
            this.model.fetch({
                success: function() {
                    var data = {
                        testsuite: self.model,
                        projectId: self.projectId,
                        _: _
                    }
                    var compiledTemplate = _.template(viewTestSuiteTemplate, data);
                    $("#content-area").html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch test suite");
                }
            });
        }

    });

    return ViewTestSuiteView;

});