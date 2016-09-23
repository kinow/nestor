define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/header/positionProjectComboboxViewTemplate.html'
], function($, _, Backbone, app, positionProjectComboboxViewTemplate) {

    var PositionProjectComboboxView = Backbone.View.extend({

        tagName: "div",

        className: "ui simple dropdown item",

        initialize: function(options) {
            _.bindAll(this, 'render', 'onClickPositionProjectItem', 'onClickNext');
            this.page = 1;
            this.title = 'Choose a Project';

            this.projectsCollection = options.collection;
            if (typeof options !== typeof undefined && typeof options.project_id !== typeof undefined) {
                this.projectsCollection.position(options.project_id, true);
            }
        },

        events: {
            "click .position-project-item": "onClickPositionProjectItem",
            'click #position-project-next': 'onClickNext',
            'click #position-project-previous': 'onClickPrevious'
        },

        onClickPositionProjectItem: function(evt) {
            var projectId = evt.target.dataset.value;
            if (projectId != app.session.get('project_id')) {
                this.projectsCollection.position(projectId, true);
            }
        },

        onClickNext: function(evt) {
            var self = this;
            this.$el.find('#position-project-list').empty();
            if (this.page < this.projectsCollection.lastPage) {
                this.page += 1;
                this.projectsCollection.fetch({
                    reset: false,
                    data: {
                        page: self.page
                    },
                    success: function() {
                        // data to be passed to UI
                        var projects = self.projectsCollection.models;
                        var list = self.$el.find('#position-project-list');
                        _.each(projects, function(project) {
                            list.append('<div class="item position-project-item" data-value="'+ project.get('id') + '">' + project.get('name') + '</div>');
                        });
                    },
                    error: function() {
                        throw new Error("Failed to fetch projects");
                    }
                });
            }
        },

        onClickPrevious: function(evt) {
            var self = this;
            this.$el.find('#position-project-list').empty();
            if (this.page > 1) {
                this.page -= 1;
                this.projectsCollection.fetch({
                    reset: false,
                    data: {
                        page: self.page
                    },
                    success: function() {
                        // data to be passed to UI
                        var projects = self.projectsCollection.models;
                        _.each(projects, function(project) {
                            self.$el.append('<div class="item position-project-item" data-value="'+ project.get('id') + '">' + project.get('name') + '</div>');
                        });
                    },
                    error: function() {
                        throw new Error("Failed to fetch projects");
                    }
                });
            }
        },

        render: function() {
            var self = this;
            this.projectsCollection.fetch({
                reset: false,
                data: {
                    page: self.page
                },
                success: function() {
                    // data to be passed to UI
                    var data = {
                        projects: self.projectsCollection.models,
                        collection: self.projectsCollection,
                        title: self.title
                    };
                    // render the template
                    var compiledTemplate = _.template(positionProjectComboboxViewTemplate, data);

                    // update the HTML element of this view
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }
    });

    return PositionProjectComboboxView;

});