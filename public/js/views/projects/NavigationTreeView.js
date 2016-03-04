define([
    'jquery',
    'underscore',
    'backbone',
    'app'
], function($, _, Backbone, app) {

    var NavigationTreeView = Backbone.View.extend({
        el: $("#navigation-tree"),

        initialize: function() {
            _.bindAll(this, 'render');
        },

        events: {
            
        },

        render: function() {
            $('.menu a').removeClass('active');
            $('.menu a[href="#/projects"]').addClass('active');

            //this.$el.html(newProjectTemplate);
        }

    });

    return NavigationTreeView;

});