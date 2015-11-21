define([
  'jquery',
  'underscore',
  'backbone',
  'text!templates/auth/signInTemplate.html'
], function($, _, Backbone, signInTemplate){

  var SignInView = Backbone.View.extend({
    el: $("#page"),

    render: function() {
      $('.menu a').removeClass('active');

      this.$el.html(signInTemplate);
    }

  });

  return SignInView;

});
