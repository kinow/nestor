define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testsuites/newTestSuiteTemplate.html'
], function($, _, Backbone, ProjectModel, TestSuiteModel, TestSuitesCollection, newTestSuiteTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
        },

        render: function() {
            var compiledTemplate = _.template(newTestSuiteTemplate, {});
            return compiledTemplate;
        },

        save: function() {
            console.log('TODO: save test suite');
        }

    });

    return NewTestSuiteView;

});