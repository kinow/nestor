define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var ProjectModel = BaseModel.extend({

        defaults: {
            project_statuses_id: 1,
            name: 'No project name set',
            description: 'No description set'
        },

        urlRoot: '/api/projects',

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        parse: function(obj) {
            if (typeof(obj.project) != 'undefined')
                return obj.project;
            return obj;
        }

    });

    return ProjectModel;

});