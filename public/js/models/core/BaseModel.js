define([
    'underscore',
    'backbone',
    'app'
], function(_, Backbone, app) {

    var BaseModel = Backbone.Model.extend({

        initialize: function(options) {
            this.bindAll(this, 'postAuth');
            this.set('url', '#/projects/' + options.id + '/view');
        },

        /*
         * Abstracted fxn to make a POST request to the auth endpoint
         * This takes care of the CSRF header for security, as well as
         * updating the user and session after receiving an API response
         */
        postAuth: function(opts, callback, args) {
            var self = this;
            var postData = _.omit(opts, 'method');
            if (typeof DEBUG != 'undefined' && DEBUG) console.log(postData);
            $.ajax({
                url: self.url() + '/' + opts.method,
                contentType: 'application/json',
                dataType: 'json',
                type: 'POST',
                beforeSend: function(xhr) {
                    // Set the CSRF Token in the header for security
                    var token = $('meta[name="csrf-token"]').attr('content');
                    if (token) xhr.setRequestHeader('X-CSRF-Token', token);

                    // Set the API version
                    // TODO: get api tree and sub application name from config
                    xhr.setRequestHeader('Accept', 'application/vnd.nestorqa.v1+json');
                },
                data: JSON.stringify(_.omit(opts, 'method')),
                success: function(data, textStatus, request) {
                    if (!data.error) {
                        if (_.indexOf(['login', 'signup'], opts.method) !== -1) {
                            self.updateSessionUser(data || {});
                            self.set({
                                user_id: data.id,
                                logged_in: true
                            });
                        } else {
                            self.set({
                                logged_in: false
                            });
                            var project_id = request.getResponseHeader('X-NESTORQA-PROJECT-ID');
                            if (typeof app !== typeof undefined) {
                                app.session.updateSessionProject(parseInt(project_id));
                            }
                        }

                        if (callback && 'success' in callback) callback.success(data);
                    } else {
                        if (callback && 'error' in callback) callback.error(data);
                    }
                },
                error: function(mod, res) {
                    if (callback && 'error' in callback) {
                        callback.error(mod.responseText);
                    }
                }
            }).complete(function() {
                if (callback && 'complete' in callback) callback.complete(res);
            });
        },

    });

    return BaseModel;

});