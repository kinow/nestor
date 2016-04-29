define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'collections/testcase/TestCasesCollection',
    'text!templates/testcases/newTestCaseTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestCasesCollection, newTestCaseTemplate) {

    var NewTestCaseView = Backbone.View.extend({

        events: {
            'click #new-testcase-btn': 'save'
        },

        initialize: function(options) {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestCasesCollection();
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            this.testsuite_id = options.testsuite_id; // FIXME: remove this comment when we prevent insecure object direct reference
            this.projectId = options.project_id;
            var executionTypes = options.execution_types;
            var compiledTemplate = _.template(newTestCaseTemplate, {
                execution_types: executionTypes
            });
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

            if (this.$("#new-testcase-form").parsley().validate()) {
                this.collection.create({
                    name: this.$("#testcase-name-input").val(),
                    description: this.description_simplemde.value(),
                    prerequisite: this.prerequisite_simplemde.value(),
                    execution_type_id: this.$("#testcase-executiontype_id-input").val(),
                    test_suite_id: this.testsuite_id,
                    project_id: this.projectId,
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New test case ' + this.$("#testcase-name-input").val() + ' created!', 'success')
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

    return NewTestCaseView;

});