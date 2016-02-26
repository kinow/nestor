define([
    'jquery',
    'underscore',
    'backbone'
], function($, _, Backbone) {

    var BreadcrumbView = Backbone.View.extend({
        el: $("#breadcrumb"),

        tagName: 'ul',

        initialize: function(options) {
            _.bindAll(this, 'render');
            var navigation = options.navigation;
            var self = this;
            navigation.getBreadcrumbs().done(function() {
                self.collection = navigation.breadcrumbs;
                self.render();
                self.listenTo(self.collection, 'add', self.render);
                self.listenTo(self.collection, 'reset', self.render);
                self.listenTo(self.collection, 'change', self.render);
            });
        },

        render: function() {
            //this.$el.html(homeTemplate);
            var self = this;
            self.$el.empty();
            this.collection.each(function(breadcrumb, i) {
                if ((i + 1) != self.collection.length) {
                    self.$el.append("<a class='section' href='#/" + breadcrumb.get('url') + "'>" + breadcrumb.get('text') + "</a>");
                    self.$el.append('<i class="right angle icon divider"></i>');
                } else {
                    self.$el.append("<div class='active section'>" + breadcrumb.get('text') + "</div>");
                }
            });
        }
    });

    return BreadcrumbView;

});