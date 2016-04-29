define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'models/project/ProjectModel',
    'collections/project/ProjectsCollection',
    'views/projects/ProjectsListView',
    'text!templates/projects/newProjectTemplate.html'
], function($, _, Backbone, app, SimpleMDE, ProjectModel, ProjectsCollection, ProjectsListView, newProjectTemplate) {

    var NewProjectView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            _.bindAll(this, 'render', 'save');
            this.collection = new ProjectsCollection();
        },

        events: {
            'click #new-project-btn': 'save'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/projects"]').parent().addClass('active');

            this.$el.html(newProjectTemplate);
            this.simplemde = new SimpleMDE({
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
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#new-project-form").parsley().validate()) {
                this.collection.create({
                    name: this.$("#project-name-input").val(),
                    description: this.simplemde.value(),
                    created_by: app.session.user_id
                }, {
                    wait: true,
                    success: function(mod, res) {
                        app.showAlert('Success!', 'New project ' + this.$("#project-name-input").val() + ' created!', 'success')
                        Backbone.history.navigate("#/projects", {
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
                        app.showAlert('Failed to add new Project', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return NewProjectView;

});