define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'models/project/ProjectModel',
    'text!templates/projects/projectTemplate.html'
], function($, _, Backbone, app, ProjectModel, projectTemplate) {

    var ProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'submit form': 'save'
        },

        initialize: function(options) {
            this.model = new ProjectModel();
            _.bindAll(this, 'render', 'save');
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');
            var self = this;
            this.model.fetch({
                success: function(project) {
                    var data = {
                        project: project,
                        _: _
                    }
                    var compiledTemplate = _.template(projectTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch project");
                }
            });
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#project-form").parsley().validate()) {
                var self = this;
                console.log(this.model.isNew());
                console.log(this.model.id);
                this.model.save({
                    name: this.$("#project-name-input").val(),
                    description: this.$("#project-description-input").val(),
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Project ' + this.$("#project-name-input").val() + ' updated!', 'success')
                        //Backbone.history.navigate("#/projects/" + self.model.id, { trigger: false });
                        Backbone.history.history.back();
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

    return ProjectView;

});