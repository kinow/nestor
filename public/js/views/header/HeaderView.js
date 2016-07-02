define([
    'jquery',
    'underscore',
    'backbone',
    'app',
    'views/header/PositionProjectComboboxView',
    'text!templates/header/headerTemplate.html'
], function($, _, Backbone, app, PositionProjectComboboxView, headerTemplate) {

    var HeaderView = Backbone.View.extend({

        el: $("#header"),

        initialize: function() {
            _.bindAll(this, 'onLoginStatusChange', 'onPositionProject', 'render');

            // Listen for session logged_in state changes and re-render
            app.session.on("change:logged_in", this.onLoginStatusChange);
            app.session.on("change:project_id", this.onPositionProject);
            if (!this.positionProjectComboboxView) {
                this.positionProjectComboboxView = new PositionProjectComboboxView();
            }

            // For GC
            this.subviews = new Object();
            this.subviews.positionProjectComboboxView = this.positionProjectComboboxView;
        },

        events: {
            "click #logout-link": "onLogoutClick",
            "click #remove-account-link": "onRemoveAccountClick"
        },

        onPositionProject: function(evt) {
            this.render();
        },

        onLoginStatusChange: function(evt) {
            this.render();
            var logged_in = app.session.get("logged_in");
            if (logged_in) {
                //app.showAlert("Success!", "Logged in as " + app.session.user.get('name'), "success");
                //Backbone.history.navigate('/#/projects');
                //window.location = '/#/projects';
            }
            /*else 
            {
              else app.showAlert("See ya!", "Logged out successfully", "success");
            }*/
        },

        onLogoutClick: function(evt) {
            evt.preventDefault();
            app.session.logout({}); // No callbacks needed b/c of session event listening
            Backbone.history.navigate("#/signin", {
                trigger: true
            });
        },

        onRemoveAccountClick: function(evt) {
            evt.preventDefault();
            //app.session.removeAccount({});
        },

        render: function() {
            // data to be passed to UI
            var data = {
                logged_in: app.session.get("logged_in"),
                project_id: app.session.get('project_id'),
                user: app.session.user.toJSON()
            };
            // render the template
            var compiledTemplate = _.template(headerTemplate, data);

            // update the HTML element of this view
            this.$el.html(compiledTemplate);

            this.$('#position-project-combobox').empty();
            this.positionProjectComboboxView.render();
            this.positionProjectComboboxView.delegateEvents();
            this.$('#position-project-combobox').replaceWith(this.positionProjectComboboxView.el);

            return this;
        }
    });

    return HeaderView;

});