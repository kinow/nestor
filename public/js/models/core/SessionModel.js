// File: SessionMode.js

define([
    'underscore',
    'backbone',
    'models/core/UserModel',
    'models/core/BaseModel',
], function(_, Backbone, UserModel, BaseModel) {

    var SessionModel = BaseModel.extend({

      	defaults: {
      		logged_in : false,
            user_id: 0
      	},

      	initialize: function (options) {
            _.bindAll(this, 'updateSessionUser', 'checkAuth', 'login', 'logout', 'signup');

            // Singleton user object
            // Access or listen on this throughout any module with app.session.user
            this.user = new UserModel({});
      	},

        url: function() {
            return 'api/auth';
        },

        // Fxn to update user attributes after receiving API response
        updateSessionUser: function( userData ){
            this.user.set(_.pick(userData, _.keys(this.user.defaults)));
            this.user_id = this.user.id;
        },

        /*
         * Check for session from API
         * The API will parse client cookies using its secret token
         * and return a user object if authenticated
         */
        checkAuth: function(callback, args) {
            var self = this;
            this.fetch({
                success: function(mod, res){
                    if(!res.error && res.user){
                        self.updateSessionUser(res.user);
                        self.set({ logged_in : true });
                        self.set({ user_id: res.user.id });
                        if('success' in callback) callback.success(mod, res);
                    } else {
                        self.set({ logged_in : false });
                        self.set({ user_id: 0 });
                        if('error' in callback) callback.error(mod, res);
                    }
                }, error:function(mod, res){
                    self.set({ logged_in : false });
                    self.set({ user_id: 0 });
                    if('error' in callback) callback.error(mod, res);
                }
            }).complete( function(){
                if('complete' in callback) callback.complete();
            });
        },

        login: function(opts, callback, args){
            this.postAuth(_.extend(opts, { method: 'login' }), callback);
        },

        logout: function(opts, callback, args){
            this.postAuth(_.extend(opts, { method: 'logout' }), callback);
        },

        signup: function(opts, callback, args){
            this.postAuth(_.extend(opts, { method: 'signup' }), callback);
        }

        // removeAccount: function(opts, callback, args){
        //     this.postAuth(_.extend(opts, { method: 'remove_account' }), callback);
        // }

    });

    return SessionModel;

});
