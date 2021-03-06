// Filename: app.js
define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/SessionModel'
], function($, _, Backbone, SessionModel) {
    'use strict';

    /**
     * The app object. Used throughtout the system, providing basic properties and common methods.
     */
    var app = {
        root: "/", // The root path to run the application through.
        URL: "/", // Base application URL
        API: "/api", // Base API URL (used by models & collections)

        /**
         * Gets the text of the alert message.
         * 
         * If the alert is a string, then returns the alert itself.
         * 
         * If the alert is an object, with the message attribute, then returns alert.message.
         * 
         * Otherwise, returns a &lt;ul&gt; element, where each attribute of the object is added as a
         * &lt;li*gt; element.
         * 
         * Does not handle null, undefined and other values. Cleaning must be done before calling this
         * function.
         * 
         * @param mixed alert
         * @return string text
         * @since 0.13
         */
        getAlertText: function(alert) {
            var text = '';

            if (typeof(alert) == 'string') {
                text = alert;
            } else if (alert.hasOwnProperty('message')) {
                text = alert.message;
            } else {
                text = '<ul>';
                for (var prop in alert) {
                    if (alert.hasOwnProperty(prop)) {
                        text += ('<li>' + alert[prop] + '</li>');
                    }
                }
                text += '</ul>';
            }

            return text;
        },

        // Show alert classes and hide after specified timeout
        showAlert: function(title, alert, klass) {
            var text = this.getAlertText(alert);
            $("#header-alert").removeClass("negative warning success positive error");
            $("#header-alert").addClass(klass);
            $("#header-alert").html('<i class="close icon"></i><div class="header">' + title + '</div>' + text);
            $("#header-alert").show();

            $('.message .close')
                .on('click', function() {
                    $(this)
                        .closest('.message')
                        .hide();
                });

            setTimeout(function() {
                $("#header-alert").hide();
            }, 7000);
        },

        showView: function(view, options) {
            // Close and unbind any existing page view
            if (this.currentView && _.isFunction(this.currentView.close)) {
                this.currentView.close(view);
            }

            // Establish the requested view into scope
            this.currentView = view;

            // Need to be authenticated before rendering view.
            // For cases like a user's settings page where we need to double check against the server.
            if (typeof options !== 'undefined' && options.requiresAuth) {
                var self = this;
                app.session.checkAuth({
                    success: function(res) {
                        // If auth successful, render inside the page wrapper
                        self.currentView.render();
                        if (typeof options.onSuccess == 'function') {
                            options.onSuccess();
                        }
                    },
                    error: function(res) {
                        self.showAlert('Authorization error', 'You must authenticate first', 'error');
                        Backbone.history.navigate("#/signin", {
                            trigger: false
                        });
                    }
                });
            } else {
                // Render inside the page wrapper
                this.currentView.render();
                if (typeof options !== 'undefined' && typeof options.onSuccess == 'function') {
                    options.onSuccess();
                }
                //this.currentView.delegateEvents(this.currentView.events);        // Re-delegate events (unbound when closed)
            }
        }
    };

    // Create a new session model and scope it to the app global
    // This will be a singleton, which other modules can access
    app.session = new SessionModel({});

    // force ajax call on all browsers
    $.ajaxSetup({
        cache: false
    });

    // Global event aggregator
    app.eventAggregator = _.extend({}, Backbone.Events);

    // View.close() event for garbage collection
    Backbone.View.prototype.close = function() {
        this.$el.empty();
        this.unbind();
        if (this.onClose) {
            this.onClose();
        }
        if (typeof(this.subviews) == 'object') {
            _.each(this.subviews, function(subview) {
                subview.$el.empty();
                subview.unbind();
                if (subview.onClose) {
                    subview.onClose();
                }
            })
        }
        this.off();
        this.undelegateEvents();
    };

    // Debug events
    Backbone.on("all", function(eventName){
        console.log(eventName + ' was triggered!');
    });

    return app;

});
