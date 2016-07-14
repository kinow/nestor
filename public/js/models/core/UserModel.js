define([
  'underscore',
  'backbone'
], function(_, Backbone) {

  var UserModel = Backbone.Model.extend({

  	defaults: {
  		name: '',
      email: '',
      password: ''
  	},
  	
  	url: '/api/users'

  });

  return UserModel;

});
