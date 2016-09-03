define([
    'jquery',
    'underscore',
    'backbone',
    'models/core/UserModel'
], function($, _, Backbone, UserModel) {
    var UserCollection = Backbone.Collection.extend({

        model: UserModel,

        url: 'api/users/',

        parse: function(response) {
            return response ? response.users : [];
        }

    });

    return UserCollection;
});