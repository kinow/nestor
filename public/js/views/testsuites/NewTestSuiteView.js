define([
    'jquery',
    'underscore',
    'backbone',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testsuites/newTestSuiteTemplate.html'
], function($, _, Backbone, ProjectModel, TestSuiteModel, TestSuitesCollection, newTestSuiteTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        events: {},

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
        },

        render: function() {
            var compiledTemplate = _.template(newTestSuiteTemplate, {});
            return compiledTemplate;
        },

        save: function() {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#new-project-form").parsley().validate()) {
                this.collection.create({
                    name: this.$("#project-name-input").val(),
                    description: this.$("#project-description-input").val(),
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New project ' + this.$("#project-name-input").val() + ' created!', 'success')
                        Backbone.history.navigate("#/projects", {
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
                        app.showAlert('Failed to add new Project', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return NewTestSuiteView;

});