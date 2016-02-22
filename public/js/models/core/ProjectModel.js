define([
  'underscore',
  'backbone'
], function(_, Backbone) {

  var ProjectModel = Backbone.Model.extend({

  	defaults: {
  		id: 0,
  		name: '',
      description: ''
  	},

  	initialize: function (options) {
      _.bindAll(this);
  	},

    url: function() {
      return '/api/projects';
    }

  });

  return ProjectModel;

});
