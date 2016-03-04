define([
    'jquery',
    'underscore',
    'backbone',
    'app'
], function($, _, Backbone, app) {

    var NodeItemView = Backbone.View.extend({
        el: $("#content-area"),

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

    return NodeItemView;

});