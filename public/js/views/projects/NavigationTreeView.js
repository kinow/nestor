define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/projects/navigationTreeTemplate.html'
], function($, _, Backbone, app, navigationTreeTemplate) {

    var NavigationTreeView = Backbone.View.extend({
        el: $("#navigation-tree"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        events: {
            
        },

        render: function() {
            var compiledTemplate = _.template(navigationTreeTemplate, {});
            return compiledTemplate;
        }

    });

    return NavigationTreeView;

});