define([
  'jquery',
  'underscore',
  'backbone',
  'app',
  'models/project/ProjectModel',
  'collections/projects/ProjectsCollection',
  'views/projects/ProjectsListView',
  'text!templates/projects/newProjectTemplate.html'
], function($, _, Backbone, app, ProjectModel, ProjectsCollection, ProjectsListView, newProjectTemplate){

  var NewProjectView = Backbone.View.extend({
    el: $("#page"),

    initialize: function() {
      _.bindAll(this, 'render', 'onSaveAttempt');
      this.collection = new ProjectsCollection();
    },

    events: {
      'click #new-project-btn': 'onSaveAttempt'
    },

    render: function() {
      $('.menu a').removeClass('active');
      $('.menu a[href="#/projects"]').addClass('active');

      this.$el.html(newProjectTemplate);
    },

    onSaveAttempt: function(event) {
      event.preventDefault();
      event.stopPropagation();
      
      if(this.$("#new-project-form").parsley().validate()) {
        this.collection.create(
          {
            name: this.$("#project-name-input").val(),
            description: this.$("#project-description-input").val(),
          }, {
          wait: true,
          success: function(mod, res) {
            app.showAlert('Success!', 'New project ' + this.$("#project-name-input").val() + ' created!', 'success')
            Backbone.history.navigate("#/projects", {trigger: false});
          },
          error: function(model, response, options) {
            var message = _.has(response, 'statusText') ? response.statusText : 'Unknown error!';
            if (
              _.has(response, 'responseJSON') && 
              _.has(response.responseJSON, 'name') &&
              _.has(response.responseJSON.name, 'length') &&
              response.responseJSON.name.length > 0
            ) {
              message = response.responseJSON.name[0];
            }
            app.showAlert('Failed to add new Project', message, 'error');
          }
        });
      } else {
        if(typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
      }
    }

  });

  return NewProjectView;
  
});
