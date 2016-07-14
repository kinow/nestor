define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/testrun/TestRunModel',
    'text!templates/testruns/testRunTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestRunModel, testRunTemplate) {

    var TestRunView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'click #testrun-btn': 'save'
        },

        initialize: function(options) {
            _.bindAll(this, 'render', 'save');
            this.testRunId = 0;
            this.testPlanId = options.test_plan_id;
            this.testRunModel = new TestRunModel({ test_plan_id: this.testPlanId });
            this.description_simplemde = null;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');
            var self = this;

            this.testRunModel.set('id', this.testRunId);
            this.testRunModel.fetch({
                data: {
                    id: this.testRunId,
                    test_plan_id: self.testPlanId
                },
                success: function(testrun) {
                    // data to be passed to UI
                    var data = {
                        testrun: testrun,
                        _: _
                    };
                    var compiledTemplate = _.template(testRunTemplate, data);
                    self.$el.html(compiledTemplate);
                    var inputField = $('#testrun-description-input');
                    self.description_simplemde = new SimpleMDE({
                        autoDownloadFontAwesome: true,
                        autofocus: false,
                        autosave: {
                            enabled: false
                        },
                        element: $('#testrun-description-input')[0],
                        indentWithTabs: false,
                        spellChecker: false,
                        tabSize: 4
                    });
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
            this.delegateEvents();
        },

        save: function(event) {
            var self = this;
            if (this.$("#testrun-form").parsley().validate()) {
                this.testRunModel.save({
                    name: this.$("#testrun-name-input").val(),
                    description: self.description_simplemde.value(),
                    test_plan_id: self.testRunModel.get('test_plan_id'),
                    created_by: app.session.get('user_id')
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Test run ' + this.$("#testrun-name-input").val() + ' updated!', 'success')
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
                        app.showAlert('Failed to add new Test Run', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }

            return false;
        }

    });

    return TestRunView;

});