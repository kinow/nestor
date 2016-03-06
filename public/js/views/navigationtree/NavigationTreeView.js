define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'collections/navigationtree/NavigationTreeCollection',
    'views/navigationtree/NavigationTreeView',
    'text!templates/navigationtree/navigationTreeTemplate.html'
], function($, _, Backbone, app, NavigationTreeCollection, NavigationTreeView, navigationTreeTemplate) {

    var NavigationTreeView = Backbone.View.extend({
        el: $("#navigation-tree"),

        initialize: function(options) {
            _.bindAll(this, 'render');
            this.el = options.element;
            this.projectId = options.projectId;

            this.collection = new NavigationTreeCollection();
            this.listenTo(this.collection, 'reset', this.render);
        },

        events: {

        },

        render: function(options) {
            var self = this;
            this.collection.setRootId(this.projectId);
            this.collection.fetch({
                success: function() {
                    var element = options.element;
                    self.setElement(options.element);
                    var models = self.collection.models;
                    var model = null;
                    if (models.length > 0) {
                        model = models[0].toJSON();
                    }
                    var data = {
                        items: model,
                        project_id: self.projectId
                    };
                    var compiledTemplate = _.template(navigationTreeTemplate, data);
                    self.$el.html(compiledTemplate);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return NavigationTreeView;

});