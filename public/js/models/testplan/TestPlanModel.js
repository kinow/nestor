define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestPlanModel = BaseModel.extend({

        urlRoot: '/api/projects',

        defaults: {
            id: null,
            project_id: 0,
            name: 'No test plan name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url');
        },

        url: function() {
            var url = '/api/testplans/' + this.id;
            return url;
        }

    });

    return TestPlanModel;

});