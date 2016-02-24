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

      //var projectsCollection = new ProjectsCollection();
      //var projectsListView = new ProjectsListView({collection: projectsCollection}); 
    },

    onSaveAttempt: function(event) {
      if(event) {
        event.preventDefault();
      }
      if(this.$("#new-project-form").parsley().validate()) {
        // var project = new ProjectModel({
        //   name: this.$("#project-name-input").val(),
        //   description: this.$("#project-description-input").val(),
        // });
        this.collection.create(
          {
            name: this.$("#project-name-input").val(),
            description: this.$("#project-description-input").val(),
          }, {
          wait: true,
          success: function(mod, res) {
            // console.log(mod);
            // console.log(res);
            app.showAlert('Success!', 'New project ' + this.$("#project-name-input").val() + ' created!', 'success')
            Backbone.history.navigate("#/projects", {trigger: true});
          },
          error: function(model, response, options) {
            //console.log(model);
            //console.log(response);
            //console.log(options);
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
        // project.save({
        //   name: this.$("#project-name-input").val(),
        //   description: this.$("#project-description-input").val(),
        // }, {
        //   success: function(mod, res){
        //     if(typeof DEBUG != 'undefined' && DEBUG) console.log("SUCCESS", mod, res);
        //     console.log('Success!')
        //   },
        //   error: function(err){
        //     if(typeof DEBUG != 'undefined' && DEBUG) console.log("ERROR", err);
        //     app.showAlert('Error saving the project', err, 'error');
        //   }
        // });
      } else {
        // Invalid clientside validations thru parsley
        if(typeof DEBUG != 'undefined' && DEBUG) console.log("Did not pass clientside validation");
      }
    }

  });

  return NewProjectView;
  
});
