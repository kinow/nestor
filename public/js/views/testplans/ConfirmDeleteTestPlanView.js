define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/testplan/TestPlanModel',
    'text!templates/testplans/confirmDeleteTestPlanTemplate.html'
], function($, _, Backbone, app, TestPlanModel, confirmDeleteTestPlanTemplate) {

    var ConfirmDeleteTestPlanView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            this.model = new TestPlanModel();
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-testplan-btn': 'doDelete'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/testplans"]').parent().addClass('active');
            var self = this;
            this.model.fetch({
                success: function(testplan) {
                    console.log(testplan);
                    var data = {
                        testplan: testplan,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteTestPlanTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    app.showAlert('Failed to delete Test Plan', 'Error fetching Test Plan!', 'error');
                    Backbone.history.history.back();
                }
            });
        },

        doDelete: function(event) {
            event.preventDefault();
            event.stopPropagation();

            this.model.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Test Plan deleted!', 'success')
                    Backbone.history.navigate("#/planning", { trigger: false });
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
                    app.showAlert('Failed to delete Test Plan', message, 'error');
                    Backbone.history.history.back();
                }
            });
        }

    });

    return ConfirmDeleteTestPlanView;

});
