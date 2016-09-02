define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'text!templates/auth/userProfileTemplate.html'
], function($, _, Backbone, app, userProfileTemplate) {

    var UserProfileView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            _.bindAll(this, 'onUpdateProfile', 'render');
        },

        events: {
            'click #profile-btn': 'onUpdateProfile'
        },

        onUpdateProfile: function(event) {
            if (event) event.preventDefault();
            if (this.$("#profile-form").parsley().validate()) {
                app.session.signup({
                    username: this.$("#signup-username-input").val(),
                    name: this.$("#signup-name-input").val(),
                    email: this.$("#signup-email-input").val(),
                    password: this.$("#signup-password-input").val()
                }, {
                    success: function(mod, res) {
                        if (typeof DEBUG != 'undefined' && DEBUG) console.log("SUCCESS", mod, res);
                        app.showAlert('Welcome!', 'You have been signed up and automatically logged in!', 'success')
                        Backbone.history.navigate("#/projects", {
                            trigger: true
                        });
                    },
                    error: function(err) {
                        if (typeof DEBUG != 'undefined' && DEBUG) console.log("ERROR", err);
                        app.showAlert('Sign Up error', err, 'error');
                    }
                });
            } else {
                // Invalid clientside validations thru parsley
                if (typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
            }
        },

        render: function() {
            $('.item').removeClass('active');
            $('a[href="#/me"]').addClass('active');
            var data = {
                user: app.session.user,
                _: _
            };
            var compiledTemplate = _.template(userProfileTemplate, data);
            this.$el.html(compiledTemplate);
            $("#profile-username-input").focus();
        }

    });

    return UserProfileView;

});