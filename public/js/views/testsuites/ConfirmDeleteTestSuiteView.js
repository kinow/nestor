define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/testsuites/confirmDeleteTestSuiteTemplate.html'
], function($, _, Backbone, app, confirmDeleteTestSuiteTemplate) {

    var ConfirmDeleteTestSuiteView = Backbone.View.extend({

        initialize: function() {
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-testsuite-btn': 'doDelete'
        },

        render: function(options) {
            $('.item').removeClass('active');
            $('.item a[href="#/specification"]').parent().addClass('active');
            var self = this;
            this.model = options.model;
            this.projectId = options.project_id;
            this.model.fetch({
                success: function(testsuite) {
                    var data = {
                        testsuite: testsuite,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteTestSuiteTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    app.showAlert('Failed to delete Test Suite', 'Error fetching test suite!', 'error');
                    Backbone.history.navigate("#/specification", {
                        trigger: false
                    });
                }
            });
        },

        doDelete: function(event) {
            var self = this;
            this.model.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Test Suite deleted!', 'success');
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
                    app.showAlert('Failed to delete Test Suite', message, 'error');
                    Backbone.history.navigate("#/specification", {
                        trigger: false
                    });
                }
            });
        }

    });

    return ConfirmDeleteTestSuiteView;

});
