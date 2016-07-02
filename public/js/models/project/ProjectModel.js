define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var ProjectModel = BaseModel.extend({

        defaults: {
            project_statuses_id: 1,
            name: 'No project name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        url: function() {
            var url = '/api/projects';
            var id = this.get('id');
            if (typeof id !== typeof undefined && id > 0) {
                url = url + '/' + id;
            }
            return url;
        },

        parse: function(obj) {
            if (typeof(obj.project) != 'undefined')
                return obj.project;
            return obj;
        }

    });

    return ProjectModel;

});