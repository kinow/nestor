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
    },

    events: {
      'click #signup-btn': 'onSignupAttempt'
    },

    onSignupAttempt: function(event) {
      if(event) event.preventDefault();
      if(this.$("#signup-form").parsley().validate()) {
        app.session.signup({
          username: this.$("#signup-username-input").val(),
          name: this.$("#signup-name-input").val(),
          email: this.$("#signup-email-input").val(),
          password: this.$("#signup-password-input").val()
        }, {
          success: function(mod, res){
            if(typeof DEBUG != 'undefined' && DEBUG) console.log("SUCCESS", mod, res);
            Backbone.history.navigate("#/projects", {trigger: true});
          },
          error: function(err){
            if(typeof DEBUG != 'undefined' && DEBUG) console.log("ERROR", err);
            app.showAlert('Sign Up error', err, 'error');
          }
        });
      } else {
        // Invalid clientside validations thru parsley
        if(typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
      }
    },

    render: function() {
      $('.menu a').removeClass('active');

      this.$el.html(signUpTemplate);
    }

  });

  return SignUpView;

});
