define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  
  var ProjectModel = Backbone.Model.extend({

  	defaults: {
  		id: 0,
  		name: 'No project name set',
      description: 'No description set',
      url: '#/404'
  	},

  	initialize: function (options) {
      this.set('url', '#/projects/' + options.id);
  	},

    url: function() {
      return '/api/projects/' + this.id;
    }

  });

  return ProjectModel;

});
