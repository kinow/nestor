define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testsuite/TestSuiteModel',
    'text!templates/testsuites/confirmDeleteTestSuiteTemplate.html'
], function($, _, Backbone, app, TestSuiteModel, confirmDeleteTestSuiteTemplate) {

    var ConfirmDeleteTestSuiteView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            this.model = new TestSuiteModel();
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-testsuite-btn': 'doDelete'
        },

        render: function() {
            var self = this;
            this.model.fetch({
                success: function(project) {
                    var data = {
                        project: project,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteTestSuiteTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    //throw new Error("Failed to fetch project");
                    app.showAlert('Failed to delete Project', 'Error fetching project!', 'error');
                    Backbone.history.navigate("#/projects", { trigger: false });
                }
            });
        },

        doDelete: function(event) {
            event.preventDefault();
            event.stopPropagation();

            this.model.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Project deleted!', 'success')
                    Backbone.history.navigate("#/projects", { trigger: false });
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
                    app.showAlert('Failed to delete Project', message, 'error');
                    Backbone.history.navigate("#/projects", { trigger: false });
                }
            });
        }

    });

    return ConfirmDeleteTestSuiteView;

});