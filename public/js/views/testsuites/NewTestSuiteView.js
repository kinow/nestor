define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/project/ProjectModel',
    'models/testsuite/TestSuiteModel',
    'collections/testsuite/TestSuitesCollection',
    'text!templates/testsuites/newTestSuiteTemplate.html'
], function($, _, Backbone, app, ProjectModel, TestSuiteModel, TestSuitesCollection, newTestSuiteTemplate) {

    var NewTestSuiteView = Backbone.View.extend({

        events: {
            'click #new-testsuite-btn': 'save'
        },

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new TestSuitesCollection();
        },

        render: function(options) {
            this.parent_id = options.parent_id; // FIXME: remove this comment when we prevent insecure object direct reference
            var compiledTemplate = _.template(newTestSuiteTemplate, {});
            this.$el.html(compiledTemplate);
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            return false;

            if (this.$("#new-testsuite-form").parsley().validate()) {
                this.collection.create({
                    name: this.$("#testsuite-name-input").val(),
                    description: this.$("#testsuite-description-input").val(),
                    parent_id: this.parent_id,
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New test suite ' + this.$("#testsuite-name-input").val() + ' created!', 'success')
                        Backbone.history.history.back();
                        return false;
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

            return false;
        }

    });

    return NewTestSuiteView;

});