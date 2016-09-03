define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'collections/core/UserCollection',
    'text!templates/auth/userProfileTemplate.html'
], function($, _, Backbone, app, UserCollection, userProfileTemplate) {

    var UserProfileView = Backbone.View.extend({
        el: $("#page"),

        initialize: function() {
            _.bindAll(this, 'onUpdateProfile', 'render');
            // Collections
            this.userCollection = new UserCollection();
        },

        events: {
            'submit': 'onUpdateProfile'
        },

        onUpdateProfile: function(event) {
            if (event) event.preventDefault();
            if (this.$("#profile-form").parsley().validate()) {
                // get user in the session
                var user = app.session.user;
                // update values

                user.save({
                    username: this.$("#profile-username-input").val(),
                    name: this.$("#profile-name-input").val(),
                    email: this.$("#profile-email-input").val(),
                }, {
                    success: function(mod, res) {
                        if (typeof DEBUG != 'undefined' && DEBUG) console.log("SUCCESS", mod, res);
                        app.showAlert('Success', 'Profile updated!', 'success')
                        Backbone.history.navigate("#/me", {
                            trigger: true
                        });
                    },
                    error: function(err) {
                        if (typeof DEBUG != 'undefined' && DEBUG) console.log("ERROR", err);
                        app.showAlert('Error updating profile', err, 'error');
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