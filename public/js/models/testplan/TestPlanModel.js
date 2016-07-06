define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestPlanModel = BaseModel.extend({

        defaults: {
            project_id: 0,
            name: 'No test plan name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        url: function() {
            var url = '/api/testplans';
            var id = this.get('id');
            if (typeof id !== typeof undefined && id > 0) {
                url = url + '/' + id;
            }
            return url;
        },

        parse: function(obj) {
            if (typeof(obj.test_plan) != 'undefined')
                return obj.test_plan;
            return obj;
        }

    });

    return TestPlanModel;

});