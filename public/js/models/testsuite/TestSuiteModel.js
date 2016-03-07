define([
    'underscore',
    'backbone',
    'models/core/BaseModel',
], function(_, Backbone, BaseModel) {

    var TestSuiteModel = BaseModel.extend({

        defaults: {
            id: null,
            project_id: 1,
            name: 'No project name set',
            description: 'No description set',
            url: '#/404'
        },

        initialize: function(options) {
            _.bindAll(this, 'url', 'parse');
        },

        url: function() {
            var url = '/api/projects/' + this.project_id + '/testsuites/' + this.id;
            return url;
        },

        parse: function(obj) {
            if (typeof(obj.test_suite) != 'undefined')
                return obj.test_suite;
            return obj;
        }

    });

    return TestSuiteModel;

});