define([
  'jquery',
  'underscore',
  'backbone',
  'models/testsuite/TestSuiteModel',
  'text!templates/testsuites/testSuiteTemplate.html'
], function($, _, Backbone, TestSuiteModel, testSuiteTemplate){

  var TestSuiteView = Backbone.View.extend({

    initialize: function (options) {
      this.id = options.testSuiteId;
      this.projectId = options.projectId;
      this.model = new TestSuiteModel({id: this.id, projectId: this.projectId});
      _.bindAll(this, 'render', 'save');
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
          var compiledTemplate = _.template( testSuiteTemplate, data );
          $("#content-area").html(compiledTemplate);
        },
        error: function() {
          throw new Error("Failed to fetch test suite");
        }
      });
    },

    save: function(e) {
      e.preventDefault();
      var arr = this.$('form').serializeArray();
      var data = _(arr).reduce(function(acc, field) {
        acc[field.name] = field.value;
        return acc;
      }, {});
      Backbone.history.navigate('#projects', true);
      // this.model.save();
      return false;
    }

  });

  return TestSuiteView;
  
});