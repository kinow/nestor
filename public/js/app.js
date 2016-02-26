// Filename: app.js
define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/SessionModel'
], function($, _, Backbone, SessionModel){
    'use strict';

    var app = {
		root : "/",                     // The root path to run the application through.
		URL : "/",                      // Base application URL
		API : "/api",                   // Base API URL (used by models & collections)

        getAlertText: function(alert) {
            var text = '';

            if (alert.hasOwnProperty('message')) {
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
						.hide()
					;
				})
			;

			setTimeout(function() {
		  		$("#header-alert").hide();
			}, 7000 );
		},

		showView: function(view, options) {
            // Close and unbind any existing page view
            if(this.currentView && _.isFunction(this.currentView.close)) {
                this.currentView.close();
            }

            // Establish the requested view into scope
            this.currentView = view;

			// Need to be authenticated before rendering view.
            // For cases like a user's settings page where we need to double check against the server.
            if (typeof options !== 'undefined' && options.requiresAuth){        
                var self = this;
                app.session.checkAuth({
                    success: function(res){
                        // If auth successful, render inside the page wrapper
                        self.currentView.render();
                    }, error: function(res){
                        self.showAlert('Authorization error', 'You must authenticate first', 'error');
                        Backbone.history.navigate("#/signin", {trigger: false});
                    }
                });
            } else {
                // Render inside the page wrapper
                this.currentView.render();
                //this.currentView.delegateEvents(this.currentView.events);        // Re-delegate events (unbound when closed)
            }
		}
    };

	// Create a new session model and scope it to the app global
	// This will be a singleton, which other modules can access
	app.session = new SessionModel({});

	// force ajax call on all browsers
	$.ajaxSetup({ cache: false });

	// Global event aggregator
	app.eventAggregator = _.extend({}, Backbone.Events);

	// View.close() event for garbage collection
	Backbone.View.prototype.close = function() {
        this.$el.empty();
		this.unbind();
		if (this.onClose) {
	  		this.onClose();
	  	}
	};

	return app;

});
