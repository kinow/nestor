define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  
  var TestSuiteModel = Backbone.Model.extend({

  	defaults: {
  		id: 0,
      projectId: 0,
  		name: 'No project name set',
      description: 'No description set',
      url: '#/404'
  	},

  	initialize: function (options) {
      this.id = options.id;
      this.projectId = options.projectId;
      this.set('url', '#/projects/' + options.projectId + '/testsuites/' + options.id + '/view');
  	},

    url: function() {
      return '/api/projects/' + this.projectId + '/testsuites/' + this.id;
    }

  });

  return TestSuiteModel;

});
