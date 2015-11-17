// Filename: app.js
define([
  'jquery',
  'underscore',
  'backbone',
  'router', // Request router.js
  'views/HeaderView'
], function($, _, Backbone, Router, HeaderView){
  'use strict';
  var initialize = function(){
    if (!this.headerView) {
      this.headerView = new HeaderView();
      this.headerView.setElement($(".header")).render();
    }

    // Pass in our Router module and call it's initialize function
    Router.initialize();
  };

  return {
    initialize: initialize
  };
});
