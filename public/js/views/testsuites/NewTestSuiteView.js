define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/testsuite/TestSuiteModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testsuites/newTestSuiteTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestSuiteModel, TestSuitesCollection, newTestSuiteTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        events: {
            'click #new-testsuite-btn': 'save'
        },

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/specification"]').parent().addClass('active');
            this.parentId = options.parent_id; // FIXME: remove this comment when we prevent insecure object direct reference
            this.projectId = options.project_id;
            var compiledTemplate = _.template(newTestSuiteTemplate, {});
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
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();
            var self = this;

            if (this.$("#new-testsuite-form").parsley().validate()) {
                var testSuite = this.collection.create({
                    name: this.$("#testsuite-name-input").val(),
                    description: this.simplemde.value(),
                    parent_id: this.parentId,
                    project_id: this.projectId,
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New test suite ' + this.$("#testsuite-name-input").val() + ' created!', 'success')
                        var changedAttributes = testSuite.changedAttributes();
                        var testSuiteId = changedAttributes.id;
                        Backbone.trigger('nestor:navigationtree_changed');
                        Backbone.history.navigate("#/projects/" + self.projectId + '/testsuites/' + testSuiteId + '/view', {
                            trigger: false
                        });
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

    return NewTestSuiteView;

});