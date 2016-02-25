define([
  'jquery',
  'underscore',
  'backbone',
  'models/project/ProjectModel'
], function($, _, Backbone, ProjectModel){
  var ProjectsCollection = Backbone.Collection.extend({
    model: ProjectModel,
    url: 'api/projects',
    models: [],
    
    initialize: function(){
    },

    fetchSuccess: function(collection, response) {
      this.models = collection.models;
    },

    fetchError: function(collection, response) {
      throw new Error("Projects fetch error");
    },

    parse: function(response) {
        console.log(response);
        return response ? response.data : [];
    }

  });
 
  return ProjectsCollection;
});
