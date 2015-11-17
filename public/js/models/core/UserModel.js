define([
  'underscore',
  'backbone'
], function(_, Backbone) {

  var UserModel = Backbone.Model.extend({

  	defaults: {
  		id: 0,
  		name: '',
      email: '',
      password: ''
  	},

  	initialize: function (options) {
      _.bindAll(this);
  	},

    url: function() {
      return '/api/users';
    }

  });

  return UserModel;

});
