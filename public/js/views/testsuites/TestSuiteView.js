define([
    'jquery',
    'underscore',
    'backbone',
    'simplemde',
    'models/testsuite/TestSuiteModel',
    'text!templates/testsuites/testSuiteTemplate.html'
], function($, _, Backbone, SimpleMDE, TestSuiteModel, testSuiteTemplate) {

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
            this.$el.html(compiledTemplate);
            this.simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true, 
                autofocus: false,
                autosave: {
                    enabled: false
                },
                element: this.$('#testsuite-description-input')[0],
                indentWithTabs: false,
                spellChecker: false,
                tabSize: 4
            });
            this.simplemde.value(this.model.get('description'));
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