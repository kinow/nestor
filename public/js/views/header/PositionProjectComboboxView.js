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
            _.bindAll(this, 'render', 'onClickPositionProjectItem');
            this.page = 0;
            this.title = 'Choose a Project';

            this.projectsCollection = options.collection;
            this.projectsCollection.setPage(this.page);
            if (typeof options !== typeof undefined && typeof options.project_id !== typeof undefined) {
                this.projectsCollection.position(options.project_id, true);
            }
        },

        events: {
            "click .position-project-item": "onClickPositionProjectItem"
        },

        onClickPositionProjectItem: function(evt) {
            var projectId = evt.target.dataset.value;
            if (projectId != app.session.get('project_id')) {
                this.projectsCollection.position(projectId, true);
            }
        },

        render: function() {
            var self = this;
            this.projectsCollection.fetch({
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