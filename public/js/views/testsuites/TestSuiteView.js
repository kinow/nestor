define([
    'jquery',
    'underscore',
    'backbone',
    'models/testsuite/TestSuiteModel',
    'text!templates/testsuites/testSuiteTemplate.html'
], function($, _, Backbone, TestSuiteModel, testSuiteTemplate) {

    var TestSuiteView = Backbone.View.extend({

        initialize: function() {
            _.bindAll(this, 'render', 'save');
        },

        render: function(options) {
            this.model = options.model;
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');
            var data = {
                testsuite: this.model,
                projectId: options.project_id,
                _: _
            }
            var compiledTemplate = _.template(testSuiteTemplate, data);
            $("#content-area").html(compiledTemplate);
        },

        save: function(e) {
            e.preventDefault();
            var arr = this.$('form').serializeArray();
            var data = _(arr).reduce(function(acc, field) {
                acc[field.name] = field.value;
                return acc;
            }, {});
            Backbone.history.navigate('#projects', true);
            // this.model.save();
            return false;
        }

    });

    return TestSuiteView;

});