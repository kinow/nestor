define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'simplemde',
    'collections/testplan/TestPlansCollection',
    'collections/project/ProjectsCollection',
    'text!templates/testplans/newTestPlanTemplate.html'
], function($, _, Backbone, app, SimpleMDE, TestPlansCollection, ProjectsCollection, newTestPlanTemplate) {

    var NewTestPlanView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            _.bindAll(this, 'render', 'save', 'setProjectId');
            this.projectId = 0;
            this.collection = new TestPlansCollection();
            this.projectsCollection = new ProjectsCollection();
        },

        events: {
            'click #new-testplan-btn': 'save'
        },

        render: function() {
            $('.item').removeClass('active');
            $('.item a[href="#/testplans"]').parent().addClass('active');

            var self = this;
            this.projectsCollection.fetch({
                success: function(collection, response, options) {
                    var projects = collection;
                    var compiledTemplate = _.template(newTestPlanTemplate, {
                        projects: projects
                    });
                    self.$el.html(compiledTemplate);
                    self.$el.html(newTestPlanTemplate);
                    self.simplemde = new SimpleMDE({
                        autoDownloadFontAwesome: true, 
                        autofocus: false,
                        autosave: {
                            enabled: false
                        },
                        element: $('#testplan-description-input')[0],
                        indentWithTabs: false,
                        spellChecker: false,
                        tabSize: 4
                    });
                },
                error: function(collection, response, options) {
                    throw new Error('Failure to retrieve projects!');
                }
            });
        },

        setProjectId: function(projectId) {
            this.projectId = projectId;
        },

        save: function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.$("#new-testplan-form").parsley().validate()) {
                var testPlan = this.collection.create({
                    name: this.$("#testplan-name-input").val(),
                    description: this.simplemde.value(),
                    project_id: this.projectId
                }, {
                    wait: true,
                    success: function(mod, res) {
                        var changedAttributes = testPlan.changedAttributes();
                        var testPlanId = changedAttributes.id;
                        app.showAlert('Success!', 'New test plan ' + this.$("#testplan-name-input").val() + ' created!', 'success')
                        Backbone.history.navigate("#/testplans/" + testPlanId, {
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
                        app.showAlert('Failed to add new Test Plan', message, 'error');
                    }
                });
            } else {
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        }

    });

    return NewTestPlanView;

});