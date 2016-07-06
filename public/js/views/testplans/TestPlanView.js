define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'text!templates/testplans/testPlanTemplate.html'
], function($, _, Backbone, app, SimpleMDE, testPlanTemplate) {

    var TestPlanView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'click #testplan-btn': 'save'
        },

        initialize: function(options) {
            _.bindAll(this, 'render', 'save');
            this.collection = options.collection;
            this.testplanId = options.testplanId;
            this.description_simplemde = null;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/planning"]').parent().addClass('active');
            var self = this;
            var testplan = this.collection.get(this.testplanId);
            console.log(this.testplanId);

            var data = {
                testplan: testplan,
                _: _
            }
            var compiledTemplate = _.template(testPlanTemplate, data);
            self.$el.html(compiledTemplate);
            this.description_simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                autofocus: false,
                autosave: {
                    enabled: false
                },
                element: $('#testplan-description-input')[0],
                indentWithTabs: false,
                spellChecker: false,
                tabSize: 4
            });
            this.delegateEvents();
        },

        save: function(event) {
            if (this.$("#testplan-form").parsley().validate()) {
                var testplan = this.collection.get(this.testplanId);
                testplan.save({
                    name: this.$("#testplan-name-input").val(),
                    description: this.description_simplemde.value(),
                    project_id: this.model.get('project_id'),
                    created_by: app.session.get('user_id')
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Test plan ' + this.$("#testplan-name-input").val() + ' updated!', 'success')
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

    return TestPlanView;

});