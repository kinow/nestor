define([
  'jquery',
  'underscore',
  'backbone'
], function($, _, Backbone){

  var BreadcrumbView = Backbone.View.extend({
    el: $("#breadcrumb"),

    tagName: 'ul',

    initialize: function (options) {
      _.bindAll(this, 'render');
      var navigation = options.navigation;
      var self = this;
      navigation.getBreadcrumbs().done(function() {
        console.log('Got breadcrumbs!');
        self.collection = navigation.breadcrumbs;
        self.render();
        self.listenTo(self.collection, 'add', self.render);
        self.listenTo(self.collection, 'reset', self.render);
        self.listenTo(self.collection, 'change', self.render);
      });
    },

    render: function() {
      //this.$el.html(homeTemplate);
      var self  = this;
      this.collection.each(function(breadcrumb) {
        self.$el.append("<a href='#/" + breadcrumb.get('url') + "'>" + breadcrumb.get('text') + "</a>");
      });
    }
  });

  return BreadcrumbView;
  
});
