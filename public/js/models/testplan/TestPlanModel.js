define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestPlanModel = BaseModel.extend({

        defaults: {
            id: null,
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
            if (this.get('id') != undefined && this.get('id') > 0) {
                url += '/' + this.get('id');
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