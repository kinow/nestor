define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/testcases/confirmDeleteTestCaseTemplate.html'
], function($, _, Backbone, app, confirmDeleteTestCaseTemplate) {

    var ConfirmDeleteTestCaseView = Backbone.View.extend({

        initialize: function() {
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-testcase-btn': 'doDelete'
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/specification"]').parent().addClass('active');
            var self = this;
            this.model = options.model;
            this.projectId = options.project_id;
            this.testSuiteId = options.test_suite_id;
            this.model.fetch({
                success: function(testcase) {
                    var data = {
                        testcase: testcase,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteTestCaseTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    app.showAlert('Failed to delete Test Case', 'Error fetching test case!', 'error');
                    Backbone.history.navigate("#/specification", {
                        trigger: false
                    });
                }
            });
        },

        doDelete: function(event) {
            event.preventDefault();
            event.stopPropagation();

            var self = this;
            this.model.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Test Case deleted!', 'success');
                    Backbone.trigger('nestor:navigationtree_changed');
                    Backbone.history.navigate("#/specification", {
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
                    app.showAlert('Failed to delete Test Case', message, 'error');
                    Backbone.history.navigate("#/specification", {
                        trigger: false
                    });
                }
            });
        }

    });

    return ConfirmDeleteTestCaseView;

});
