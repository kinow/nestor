define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'collections/navigationtree/NavigationTreeCollection',
    'text!templates/navigationtree/navigationTreeTemplate.html'
], function($, _, Backbone, app, NavigationTreeCollection, navigationTreeTemplate) {

    var NavigationTreeView = Backbone.View.extend({
        el: $("#navigation-tree"),

        initialize: function(options) {
            _.bindAll(this, 'render');
            this.el = options.element;
            this.projectId = options.projectId;

            var rootId = '1-' + this.projectId;
            this.rootId = rootId;
            this.collection = new NavigationTreeCollection({
                root_id: rootId
            });
            this.listenTo(this.collection, 'reset', this.render);
        },

        events: {

        },

        render: function(options) {
            var self = this;
            this.collection.fetch({
                success: function() {
                    var element = options.element;
                    self.setElement(options.element);

                    var data = {
                        collection: self.collection
                    };

                    var compiledTemplate = _.template(navigationTreeTemplate, data);
                    self.$el.html(compiledTemplate);
                    console.log(self.collection);
                    console.log('Navigation tree created, root ID: ' + self.rootId);
                },
                error: function() {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return NavigationTreeView;

});