define([
  'underscore',
  'backbone'
], function(_, Backbone) {

  var UserModel = Backbone.Model.extend({

    defaults: {
      id: 0,
      username: '',
      name: '',
      email: '',
      password: ''
    },
      
    urlRoot: '/api/users'

  });

  return UserModel;

});
