define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/testcase/TestCaseModel',
    'text!templates/testcases/testCaseTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestCaseModel, testCaseTemplate) {

    var TestCaseView = Backbone.View.extend({

        events: {
            'submit form': 'save'
        },

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.project_id    = 0;
            this.test_suite_id = 0;
            this.test_case_id  = 0;
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            
            this.model = options.model;
            var executionTypes = options.execution_types;
            this.projectId   = options.project_id;
            this.testSuiteId = options.test_suite_id;
            this.testCaseId  = options.test_case_id;
            var data = {
                testcase: this.model,
                projectId: options.project_id,
                testSuiteId: options.test_suite_id,
                testCaseId: options.test_case_id,
                execution_types: executionTypes,
                _: _
            }
            var compiledTemplate = _.template(testCaseTemplate, data);
            this.$el.html(compiledTemplate);
            this.description_simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                autofocus: false,
                autosave: {
                    enabled: false
                },
                element: this.$('#testcase-description-input')[0],
                indentWithTabs: false,
                spellChecker: false,
                tabSize: 4
            });
            this.prerequisite_simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                autofocus: false,
                autosave: {
                    enabled: false
                },
                element: this.$('#testcase-prerequisite-input')[0],
                indentWithTabs: false,
                spellChecker: false,
                tabSize: 4
            });
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#testcase-form").parsley().validate()) {
                this.model.save({
                    name: this.$("#testcase-name-input").val(),
                    description: this.description_simplemde.value(),
                    prerequisite: this.prerequisite_simplemde.value(),
                    execution_type_id: this.$("#testcase-executiontype_id-input").val(),
                    test_suite_id: this.testSuiteId,
                    test_case_id: this.testCaseId,
                    project_id: this.projectId,
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Test case ' + this.$("#testcase-name-input").val() + ' updated!', 'success')
                        Backbone.trigger('nestor:navigationtree_changed');
                        Backbone.history.history.back();
                    },
                    error: function(model, response, options) {
                        var message = _.has(response, 'statusText') ? response.statusText : 'Unknown error!';
                        if (
                            _.has(response, 'responseJSON') &&
                            _.has(response.responseJSON, 'name') &&
                            _.has(response.responseJSON.name, 'length') &&
                            response.responseJSON.name.length > 0
                        ) {
                            message = response.responseJSON.name[0];
                        }
                        app.showAlert('Failed to add new Test Case', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }

            return false;
        }

    });

    return TestCaseView;

});