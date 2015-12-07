define([
  'jquery',
  'underscore',
  'backbone',
  'app',
  'text!templates/auth/signUpTemplate.html'
], function($, _, Backbone, app, signUpTemplate){

  var SignUpView = Backbone.View.extend({
    el: $("#page"),

    initialize: function() {
      _.bindAll(this, 'onSignupAttempt', 'render');

      // Listen for session logged_in state changes and re-render
      app.session.on("change:logged_in", this.render);
    },

    events: {
      'click #signup-btn'            : 'onSignupAttempt'
    },

    onSignupAttempt: function(event) {
      if(event) event.preventDefault();

      if(this.$("#signup-form").parsley('validate')) {
          app.session.login({
              username: this.$("#login-username-input").val(),
              password: this.$("#login-password-input").val()
          }, {
              success: function(mod, res){
                  if(DEBUG) console.log("SUCCESS", mod, res);

              },
              error: function(err){
                  if(DEBUG) console.log("ERROR", err);
                  app.showAlert('Bummer dude!', err.error, 'alert-danger');
              }
          });
      } else {
          // Invalid clientside validations thru parsley
          if(DEBUG) console.log("Did not pass clientside validation");

      }
    },

    render: function() {
      $('.menu a').removeClass('active');

      this.$el.html(signUpTemplate);
    }

  });

  return SignUpView;

});
