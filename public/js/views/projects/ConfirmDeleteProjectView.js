define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/project/ProjectModel',
    'text!templates/projects/confirmDeleteProjectTemplate.html'
], function($, _, Backbone, app, ProjectModel, confirmDeleteProjectTemplate) {

    var ConfirmDeleteProjectView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            this.model = new ProjectModel();
            _.bindAll(this, 'render', 'doDelete');
        },

        events: {
            'click #remove-project-btn': 'doDelete'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            var self = this;
            this.model.fetch({
                success: function(project) {
                    var data = {
                        project: project,
                        _: _
                    }
                    var compiledTemplate = _.template(confirmDeleteProjectTemplate, data);
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

    return ConfirmDeleteProjectView;

});