define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'text!templates/projects/projectTemplate.html'
], function($, _, Backbone, app, SimpleMDE, projectTemplate) {

    var ProjectView = Backbone.View.extend({
        el: $("#page"),

        events: {
            'click #project-btn': 'save'
        },

        initialize: function(options) {
            _.bindAll(this, 'render', 'save');
            this.collection = options.collection;
            this.projectId = options.projectId;
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');
            var self = this;
            var project = this.collection.get(this.projectId);

            var data = {
                project: project,
                _: _
            }
            var compiledTemplate = _.template(projectTemplate, data);
            self.$el.html(compiledTemplate);
            var simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                autofocus: false,
                autosave: {
                    enabled: false
                },
                element: $('#project-description-input')[0],
                indentWithTabs: false,
                spellChecker: false,
                tabSize: 4
            });
            this.delegateEvents();
        },

        save: function(event) {
            if (this.$("#project-form").parsley().validate()) {
                var self = this;
                var project = this.collection.get(this.projectId);
                project.save({
                    name: this.$("#project-name-input").val(),
                    description: this.$("#project-description-input").val(),
                }, {
                    success: function(mod, res) {
                        app.showAlert('Success!', 'Project ' + this.$("#project-name-input").val() + ' updated!', 'success')
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
                        app.showAlert('Failed to update Project', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return ProjectView;

});