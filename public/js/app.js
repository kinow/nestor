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

		// Show alert classes and hide after specified timeout
		showAlert: function(title, text, klass) {
			$("#header-alert").removeClass("negative warning success positive error");
			$("#header-alert").addClass(klass);
			$("#header-alert").html('<i class="close icon"></i><div class="header">' + title + '</div><p>' + text + '</p>');
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
                console.log('Closing current view...');
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
                        $('#content').html( self.currentView.render().$el);
                    }, error: function(res){
                        self.navigate("/", { trigger: true, replace: true });
                    }
                });
            } else {
                // Render inside the page wrapper
                //$('#content').html(this.currentView.render().$el);
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
