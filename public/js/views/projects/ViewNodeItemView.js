define([
    'jquery',
    'underscore',
    'backbone',
    'app'
], function($, _, Backbone, app) {

    var ViewNodeItemView = Backbone.View.extend({
        //el: $("#content-area"),

        tagName: 'div',

        initialize: function() {
            _.bindAll(this, 'render');
            this.$el
                .attr('id', 'content-area')
                .attr('class', 'twelve wide column')
            ;
        },

        events: {
            
        },

        render: function() {
            //this.$el.html(newProjectTemplate);
        }

    });

    return ViewNodeItemView;

});