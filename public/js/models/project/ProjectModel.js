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
      console.log(options);
      this.set('url', '#/projects/' + options.id);
  	}

  });

  return ProjectModel;

});
