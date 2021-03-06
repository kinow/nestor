define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/testsuite/TestSuiteModel',
    'text!templates/testsuites/testSuiteTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestSuiteModel, testSuiteTemplate) {

    var TestSuiteView = Backbone.View.extend({

        events: {
            'click #testsuite-btn': 'save'
        },

        initialize: function() {
            _.bindAll(this, 'render', 'save');
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/specification"]').parent().addClass('active');
            this.model = options.model;
            this.projectId = options.project_id;
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
        },

        save: function(event) {
            if (this.$("#testsuite-form").parsley().validate()) {
                var self = this;
                this.model.save({
                    name: this.$("#testsuite-name-input").val(),
                    description: this.$("#testsuite-description-input").val(),
                }, {
                    wait: false,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Test Suite ' + this.$("#testsuite-name-input").val() + ' updated!', 'success')
                        Backbone.trigger('nestor:navigationtree_changed');
                        Backbone.history.navigate("#/projects/" + self.projectId + '/testsuites/' + self.model.get('id') + '/view', {
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
                        app.showAlert('Failed to update Test Suite', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return TestSuiteView;

});
