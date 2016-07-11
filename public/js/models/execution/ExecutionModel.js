define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestPlanModel = BaseModel.extend({

        urlRoot: '/api/executions',

        defaults: {
            project_id: 0,
            name: 'No execution name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'parse');
        },

        parse: function(obj) {
            if (typeof(obj.test_plan) != 'undefined')
                return obj.test_plan;
            return obj;
        }

    });

    return TestPlanModel;

});