define([
    'jquery',
    'dragster',
    'underscore',
    'backbone',
    'app',
    'collections/navigationtree/NavigationTreeCollection',
    'views/navigationtree/NavigationTreeView',
    'text!templates/navigationtree/navigationTreeTemplate.html'
], function($, dragster, _, Backbone, app, NavigationTreeCollection, NavigationTreeView, navigationTreeTemplate) {

    var NavigationTreeView = Backbone.View.extend({

        initialize: function(options) {
            _.bindAll(this, 'render');

            if (typeof options.draggable !== typeof undefined) {
                this.draggable = options.draggable;
            } else {
                this.draggable = false;
            }

            this.collection = new NavigationTreeCollection();
            //this.listenTo(this.collection, 'reset', this.render);

            this.$el.attr('id', 'navigation-tree');
            this.$el.attr('class', 'ui list');
        },

        events: {

        },

        render: function() {
            console.log('Rendering navigation tree for project ID [' + this.projectId + ']');
            var self = this;
            this.collection.setProjectId(this.projectId);
            this.collection.fetch({
                reset: true,
                success: function(results) {
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

                    // enable drag and drop
                    if (self.draggable) {
                        var dragster = new window.Dragster({
                            elementSelector: '.draggable',
                            regionSelector: '.item',
                            onBeforeDragStart: function(event) {
                                console.log('started!!!');
                            }
                        });
                        console.log('drag...');
                        console.log(dragster);
                        dragster.update();
                    }
                },
                error: function(collection, response, options) {
                    throw new Error("Failed to fetch projects");
                }
            });
        }

    });

    return NavigationTreeView;

});