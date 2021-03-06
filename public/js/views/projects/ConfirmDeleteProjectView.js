define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/projects/confirmDeleteProjectTemplate.html'
], function($, _, Backbone, app, confirmDeleteProjectTemplate) {

    var ConfirmDeleteProjectView = Backbone.View.extend({
        el: $("#page"),

        initialize: function(options) {
            this.collection = options.collection;
            this.projectId = options.projectId;
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-project-btn': 'doDelete'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            var self = this;
            var project = this.collection.get(this.projectId);
            project.fetch({
                success: function(project) {
                    var data = {
                        project: project,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteProjectTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    app.showAlert('Failed to delete Project', 'Error fetching project!', 'error');
                    Backbone.history.navigate("#/projects", { trigger: false });
                }
            });
            this.delegateEvents();
        },

        doDelete: function(event) {
            var project = this.collection.get(this.projectId);
            project.destroy({
                wait: true,
                success: function(mod, res) {
                    app.showAlert('Success!', 'Project deleted!', 'success');
                    Backbone.history.navigate("#/projects", { trigger: true });
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

    return ConfirmDeleteProjectView;

});
