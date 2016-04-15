define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/testcase/TestCaseModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testcases/newTestCaseTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestSuiteModel, TestSuitesCollection, newTestCaseTemplate) {

    var NewTestCaseView = Backbone.View.extend({

        events: {
            'click #new-testcase-btn': 'save'
        },

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
        },

        render: function(options) {
            this.parentId = options.parent_id; // FIXME: remove this comment when we prevent insecure object direct reference
            this.projectId = options.project_id;
            var compiledTemplate = _.template(newTestCaseTemplate, {});
            this.$el.html(compiledTemplate);
            this.simplemde = new SimpleMDE({
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
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#new-testcase-form").parsley().validate()) {
                this.collection.create({
                    name: this.$("#testcase-name-input").val(),
                    description: this.simplemde.value(),
                    parent_id: this.parentId,
                    project_id: this.projectId,
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New test suite ' + this.$("#testcase-name-input").val() + ' created!', 'success')
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
                        app.showAlert('Failed to add new Test Suite', message, 'error');
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