define([
  'jquery',
  'underscore',
  'backbone',
  'text!templates/auth/signUpTemplate.html'
], function($, _, Backbone, signUpTemplate){

  var SignUpView = Backbone.View.extend({
    el: $("#page"),

    render: function() {
      $('.menu a').removeClass('active');

      this.$el.html(signUpTemplate);
    }

  });

  return SignUpView;

});
