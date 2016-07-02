define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'collections/project/ProjectsCollection',
    'text!templates/header/positionProjectComboboxViewTemplate.html'
], function($, _, Backbone, app, ProjectsCollection, positionProjectComboboxViewTemplate) {

    var PositionProjectComboboxView = Backbone.View.extend({

        tagName: "div",

        className: "ui simple dropdown item",

        initialize: function() {
            _.bindAll(this, 'render', 'onClickPositionProjectItem');
            this.page = 0;

            this.projectsCollection = new ProjectsCollection();
            this.projectsCollection.setPage(this.page);
        },

        events: {
            "click .position-project-item": "onClickPositionProjectItem"
        },

        onClickPositionProjectItem: function(evt) {
            console.log(evt);
        },

        render: function() {
            var self = this;
            this.projectsCollection.fetch({
                success: function() {
                    // data to be passed to UI
                    var data = {
                        projects: self.projectsCollection.models,
                        collection: self.projectsCollection
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