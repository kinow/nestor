define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'collections/testruns/TestRunsCollection',
    'text!templates/testruns/newTestRunTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestRunsCollection, newTestRunTemplate) {

    var NewTestRunView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'save', 'setTestPlanId');
            this.projectId = 0;
            this.collection = new TestRunsCollection({ test_plan_id: options.test_plan_id });
        },

        events: {
            'click #new-testrun-btn': 'save'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');

            this.$el.html(newTestRunTemplate);
            this.simplemde = new SimpleMDE({
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

        setTestPlanId: function(testPlanId) {
            this.testPlanId = testPlanId;
        },

        save: function(event) {
            var self = this;
            if (this.$("#new-testrun-form").parsley().validate()) {
                var testRun = this.collection.create({
                    name: this.$("#testrun-name-input").val(),
                    description: this.simplemde.value(),
                    test_plan_id: self.testPlanId
                }, {
                    wait: true,
                    success: function(mod, res) {
                        var changedAttributes = testRun.changedAttributes();
                        var testRunId = changedAttributes.id;
                        app.showAlert('Success!', 'New test run ' + this.$("#testrun-name-input").val() + ' created!', 'success')
                        Backbone.history.navigate("#/execution/" + self.testPlanId + '/testruns/' + testRunId, {
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
                        app.showAlert('Failed to add new Test Plan', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return NewTestRunView;

});