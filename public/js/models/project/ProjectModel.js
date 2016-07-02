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
            return '/api/projects/' + this.get('id');
        },

        parse: function(obj) {
            if (typeof(obj.project) != 'undefined')
                return obj.project;
            return obj;
        }

    });

    return ProjectModel;

});