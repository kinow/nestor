define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestRunModel = BaseModel.extend({

        defaults: {
            test_plan_id: 0,
            name: 'No test run name set',
            description: 'No description set'
        },

        initialize: function(options) {
            _.bindAll(this, 'parse', 'url');
            this.set('test_plan_id', options.test_plan_id);
        },

        parse: function(obj) {
            if (typeof(obj.test_run) != 'undefined')
                return obj.test_run;
            return obj;
        },

        url: function() {
            var url = '/api/testplans/' + this.get('test_plan_id') + '/testruns';
            var id = this.get('id');
            if (id != null) {
                url += '/' + id;
            }
            return url;
        }

    });

    return TestRunModel;

});