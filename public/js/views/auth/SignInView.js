define([
  'jquery',
  'underscore',
  'backbone',
  'app',
  'text!templates/auth/signInTemplate.html'
], function($, _, Backbone, app, signInTemplate){

  var SignInView = Backbone.View.extend({
    el: $("#page"),

    initialize: function() {
      _.bindAll(this, 'render');
    },

    events: {
      'click #login-btn': 'onLoginAttempt'
    },

    onLoginAttempt: function(event){
      if(event) event.preventDefault();
      if(this.$("#login-form").parsley().validate()) {
        app.session.login({
          username: this.$("#login-username-input").val(),
          password: this.$("#login-password-input").val()
        }, {
          success: function(mod, res){
            if(typeof DEBUG != 'undefined' && DEBUG) console.log("SUCCESS", mod, res);
            app.showAlert('Welcome!', 'Log in successful!', 'success')
            Backbone.history.navigate("#/projects", {trigger: true});
          },
          error: function(err){
            if(typeof DEBUG != 'undefined' && DEBUG) console.log("ERROR", err);
            var obj = JSON.parse(err);
            console.log(obj);
            if (obj.hasOwnProperty('message')) {
              app.showAlert('Sign In error', obj.message, 'error');
            } else {
              app.showAlert('Sign In error', err, 'error');
            }
          }
        });
      } else {
        // Invalid clientside validations thru parsley
        if(typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
      }
    },

    render: function() {
      $('.menu a').removeClass('active');

      this.$el.html(signInTemplate);
    }

  });

  return SignInView;

});
