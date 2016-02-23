define([
  'underscore',
  'backbone',
  'models/core/BaseModel',
], function(_, Backbone, BaseModel) {
  
  var ProjectModel = BaseModel.extend({

    urlRoot: 'api/projects',

  	defaults: {
      id: null,
      project_statuses_id: 1,
  		name: 'No project name set',
      description: 'No description set',
      url: '#/404'
  	},

  	initialize: function (options) {
      //_.bindAll(this, 'url');
  	}

    // url: function() {
    //   return '/api/projects/' + this.id;
    // }

  });

  return ProjectModel;

});
