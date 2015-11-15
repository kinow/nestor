define([
  'jquery',
  'underscore',
  'backbone',
  'models/testsuite/TestSuiteModel',
  'text!templates/testsuites/viewTestSuiteTemplate.html'
], function($, _, Backbone, TestSuiteModel, viewTestSuiteTemplate){

  var ViewTestSuiteView = Backbone.View.extend({

    initialize: function (options) {
      this.id = options.testSuiteId;
      this.projectId = options.projectId;
      this.model = new TestSuiteModel({id: this.id, projectId: this.projectId});
    },

    render: function() {
      $('.menu a').removeClass('active');
      $('.menu a[href="#/projects"]').addClass('active');
      var self = this;
      this.model.fetch({
        success: function () {
          var data = {
            testsuite: self.model,
            projectId: self.projectId,
            _: _
          }
          var compiledTemplate = _.template( viewTestSuiteTemplate, data );
          $("#content-area").html(compiledTemplate);
        },
        error: function() {
          throw new Error("Failed to fetch test suite");
        }
      });
    }

  });

  return ViewTestSuiteView;

});
