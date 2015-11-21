// Filename: app.js
define([
  'jquery',
  'underscore',
  'backbone'
], function($, _, Backbone, Router, HeaderView){
  'use strict';

  var app = {
      root : "/",                     // The root path to run the application through.
      URL : "/",                      // Base application URL
      API : "/api",                   // Base API URL (used by models & collections)
      API_VERSION: 'v1',              // API version

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
