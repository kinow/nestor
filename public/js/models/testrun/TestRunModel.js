define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestRunModel = BaseModel.extend({

        defaults: {
            test_plan_id: 0,
            name: 'No execution name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'parse', 'url');
        },

        parse: function(obj) {
            if (typeof(obj.test_plan) != 'undefined')
                return obj.test_plan;
            return obj;
        }

    });

    return TestRunModel;

});