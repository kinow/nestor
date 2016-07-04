define([
    'jquery',
    'underscore',
    'backbone',
    'app'
], function($, _, Backbone, app) {

    var ViewNodeItemView = Backbone.View.extend({

        tagName: 'div',

        initialize: function() {
            _.bindAll(this, 'render');
            this.$el
                .attr('id', 'content-main')
                .attr('class', 'twelve wide column');
        },

        events: {

        },

        render: function() {
            this.delegateEvents();
        }

    });

    return ViewNodeItemView;

});