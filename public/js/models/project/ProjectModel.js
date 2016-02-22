define([
  'underscore',
  'backbone',
  'models/core/BaseModel',
], function(_, Backbone, BaseModel) {
  
  var ProjectModel = BaseModel.extend({

  	defaults: {
  		id: 0,
  		name: 'No project name set',
      description: 'No description set',
      url: '#/404'
  	},

  	initialize: function (options) {
      if (options != undefined && _.has(options, 'id'))
        this.set('url', '/api/projects/' + options.id);
  	},

    url: function() {
      return '/api/projects';
    }

  });

  return ProjectModel;

});
