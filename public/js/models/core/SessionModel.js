// File: SessionMode.js

define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
    'models/core/UserModel',
    'models/project/ProjectModel',
    'collections/project/ProjectsCollection'
], function(_, Backbone, BaseModel, UserModel, ProjectModel, ProjectsCollection) {

    var SessionModel = BaseModel.extend({

      	defaults: {
      		logged_in : false,
            user_id: 0
      	},

      	initialize: function (options) {
            _.bindAll(this, 'onProjectPositioned', 'updateSessionUser', 'updateSessionProject', 'checkAuth', 'login', 'logout', 'signup');

            // Singleton user object
            // Access or listen on this throughout any module with app.session.user
            this.user = new UserModel({});
            this.project = new ProjectModel({});

            Backbone.on('project:position', this.onProjectPositioned);
      	},

        onProjectPositioned: function(objects) {
            this.set('project_id', objects[0].id);
        },

        url: function() {
            return 'api/auth';
        },

        // Fxn to update user attributes after receiving API response
        updateSessionUser: function( userData ){
            this.user.set(_.pick(userData, _.keys(this.user.defaults)));
        },

        updateSessionProject: function(project) {
            var projectId = parseInt(project.id);
            if (projectId !== this.get('project_id')) {
                this.set('project_id', project.id);
                this.set('project', project);
            }
        },

        /*
         * Check for session from API
         * The API will parse client cookies using its secret token
         * and return a user object if authenticated
         */
        checkAuth: function(callback, args) {
            var self = this;
            this.fetch({
                wait: true,
                success: function(mod, res, options){
                    if(!res.error && res.id){
                        self.updateSessionUser(res);
                        self.set({ 'logged_in' : true });
                        self.set({ 'user_id': parseInt(res.id) });
                        var project_id = options.xhr.getResponseHeader('X-NESTORQA-PROJECT-ID');
                        if (typeof project_id !== typeof undefined && project_id !== null && project_id != self.get('project_id')) {
                            self.set('project_id', project_id);
                            new ProjectsCollection().position(project_id, false);
                        }
                        if('success' in callback) callback.success(mod, res, options);
                    } else {
                        self.set({ 'logged_in': false });
                        self.set({ 'user_id': 0 });
                        self.set({ 'project_id': 0 });
                        if('error' in callback) callback.error(mod, res, options);
                    }
                }, error:function(mod, res){
                    self.set({ 'logged_in': false });
                    self.set({ 'user_id': 0 });
                    self.set({ 'project_id': 0 });
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
