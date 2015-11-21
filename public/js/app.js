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
          $("#header-alert").removeClass("alert-danger alert-warning alert-success alert-info");
          $("#header-alert").addClass(klass);
          $("#header-alert").html('<button class="close" data-dismiss="alert">×</button><strong>' + title + '</strong> ' + text);
          $("#header-alert").show('fast');
          setTimeout(function() {
              $("#header-alert").hide();
          }, 7000 );
      }
  };

  // Create a new session model and scope it to the app global
  // This will be a singleton, which other modules can access
  app.session = new SessionModel({});

  $.ajaxSetup({ cache: false });          // force ajax call on all browsers

  // Global event aggregator
  app.eventAggregator = _.extend({}, Backbone.Events);

  // View.close() event for garbage collection
  Backbone.View.prototype.close = function() {
      this.remove();
      this.unbind();
      if (this.onClose) {
          this.onClose();
      }
  };

  return app;

});
