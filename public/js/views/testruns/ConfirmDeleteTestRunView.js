define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testrun/TestRunModel',
    'text!templates/testruns/confirmDeleteTestRunTemplate.html'
], function($, _, Backbone, app, TestRunModel, confirmDeleteTestRunTemplate) {

    var ConfirmDeleteTestRunView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            _.bindAll(this, 'render', 'doDelete', 'setTestPlanId');
            this.model = new TestRunModel({ test_plan_id: options.test_plan_id });
        },

        events: {
            'click #remove-testrun-btn': 'doDelete'
        },

        setTestPlanId: function(testPlanId) {
            this.model.set('test_plan_id', testPlanId);
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/execution"]').parent().addClass('active');
            var self = this;
            this.model.fetch({
                success: function(testrun) {
                    var data = {
                        testrun: testrun,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteTestRunTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    app.showAlert('Failed to delete Test Run', 'Error fetching Test Run!', 'error');
                    Backbone.history.history.back();
                }
            });
        },

        doDelete: function(event) {
            var self = this;
            this.model.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Test Run deleted!', 'success')
                    Backbone.history.navigate("#/testplans/" + self.model.get('test_plan_id') + '/testruns', { trigger: false });
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
                    app.showAlert('Failed to delete Test Run', message, 'error');
                    Backbone.history.history.back();
                }
            });
        }

    });

    return ConfirmDeleteTestRunView;

});
