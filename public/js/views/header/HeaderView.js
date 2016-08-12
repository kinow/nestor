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

        initialize: function(options) {
            _.bindAll(this, 'onLoginStatusChange', 'render', 'onProjectPositioned', 'onProjectUpdated', 'onProjectRemoved');

            // Collections
            this.collection = options.collection;

            // Listen for session logged_in state changes and re-render
            app.session.on("change:logged_in", this.onLoginStatusChange);

            this.collection.bind('change add reset', this.onProjectUpdated);
            this.collection.bind('remove', this.onProjectRemoved);

            if (!this.positionProjectComboboxView) {
                var projectId = app.session.get('project_id');
                this.positionProjectComboboxView = new PositionProjectComboboxView({'project_id': projectId, 'collection': options.collection});
            }

            // For GC
            this.subviews = new Object();
            this.subviews.positionProjectComboboxView = this.positionProjectComboboxView;

            Backbone.on('project:position', this.onProjectPositioned);
        },

        events: {
            "click #logout-link": "onLogoutClick"
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

        onProjectPositioned: function(objects) {
            var title = 'Choose a Project';
            var object = undefined;
            if (typeof objects !== typeof undefined && objects.length > 0) {
                title = objects[0]['name'];
                object = objects[0];
            }

            this.positionProjectComboboxView.title = title;
            app.session.updateSessionProject(object);
            this.render();
        },

        onProjectUpdated: function(evt) {
            var currentProjectId = app.session.get('project_id');
            if (typeof evt !== typeof undefined && typeof undefined !== typeof currentProjectId) {
                currentProjectId = parseInt(currentProjectId);
                if (currentProjectId === evt.get('id')) {
                    if (this.positionProjectComboboxView && this.positionProjectComboboxView.title !== evt.get('name'))
                        this.positionProjectComboboxView.title = evt.get('name');
                }
            }
            this.render();
        },

        onProjectRemoved: function(evt) {
            var currentProjectId = app.session.get('project_id');
            if (typeof undefined !== typeof currentProjectId) {
                currentProjectId = parseInt(currentProjectId);
                if (currentProjectId === evt.get('id')) {
                    if (this.positionProjectComboboxView && this.positionProjectComboboxView.title === evt.get('name'))
                        this.collection.position(0, true);
                }
            }
            this.render();
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

            this.positionProjectComboboxView.render();
            this.positionProjectComboboxView.delegateEvents();
            this.$('#position-project-combobox').html(this.positionProjectComboboxView.el);
        }
    });

    return HeaderView;

});
